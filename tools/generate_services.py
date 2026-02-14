#!/usr/bin/env python3
"""
Generate service classes from api-1.json.

Usage:
  python3 tools/generate_services.py [openapi.json]
"""

from __future__ import annotations

import json
import re
import sys
from collections import defaultdict
from pathlib import Path

TAG_TO_CLASS = {
    "云机操作": "AndroidService",
    "云机操作V2镜像": "AndroidV2Service",
    "云机备份": "BackupService",
    "接口认证": "AuthService",
    "基本信息": "InfoService",
    "终端": "TerminalService",
    "大模型管理": "LlmService",
    "macVlan网卡管理": "MacVlanService",
    "myt_bridge网卡管理": "MytBridgeService",
    "魔云腾VPC": "VpcService",
    "本地机型数据管理": "PhoneModelService",
    "服务": "ServerService",
}


def pascal_segment(segment: str) -> str:
    parts = re.split(r"[^A-Za-z0-9]+", segment.strip("/"))
    out = []
    for part in parts:
        if not part:
            continue
        out.append(part[0].upper() + part[1:])
    return "".join(out)


def method_name(http_method: str, path: str) -> str:
    segments = [x for x in path.strip("/").split("/") if x]
    return http_method.lower() + "".join(pascal_segment(seg) for seg in segments)


def resolve_ref(ref: str, schemas: dict) -> dict:
    prefix = "#/components/schemas/"
    if not ref.startswith(prefix):
        return {}
    return schemas.get(ref[len(prefix) :], {})


def required_from_schema(schema: dict, schemas: dict) -> list[str]:
    if "$ref" in schema:
        schema = resolve_ref(schema["$ref"], schemas)
    return [str(x) for x in schema.get("required", [])]


def build_services(spec: dict) -> dict[str, list[dict]]:
    schemas = spec.get("components", {}).get("schemas", {})
    services: dict[str, list[dict]] = defaultdict(list)

    for path, methods in spec.get("paths", {}).items():
        for http_method, operation in methods.items():
            if not isinstance(operation, dict):
                continue

            tag = (operation.get("tags") or ["Default"])[0]
            class_name = TAG_TO_CLASS.get(tag)
            if class_name is None:
                continue

            summary = (operation.get("summary") or "").replace("*/", "* /")
            req_type = "none"
            required_query = [
                p.get("name")
                for p in (operation.get("parameters") or [])
                if p.get("in") == "query" and p.get("required")
            ]
            required_query = [x for x in required_query if x]
            required_body: list[str] = []

            request_body = operation.get("requestBody") or {}
            content = request_body.get("content") or {}

            if "multipart/form-data" in content:
                req_type = "multipart"
                schema = content["multipart/form-data"].get("schema") or {}
                required_body = required_from_schema(schema, schemas)
                if not required_body:
                    resolved = resolve_ref(schema.get("$ref", ""), schemas) if "$ref" in schema else schema
                    if "file" in (resolved.get("properties") or {}):
                        required_body = ["file"]
            elif "application/json" in content:
                req_type = "json"
                schema = content["application/json"].get("schema") or {}
                required_body = required_from_schema(schema, schemas)
            elif operation.get("parameters"):
                req_type = "query"

            services[class_name].append(
                {
                    "method_name": method_name(http_method, path),
                    "http_method": http_method.upper(),
                    "path": path,
                    "summary": summary,
                    "req_type": req_type,
                    "required_query": required_query,
                    "required_body": required_body,
                }
            )

    for entries in services.values():
        entries.sort(key=lambda x: (x["path"], x["http_method"]))

    return services


def render_service(class_name: str, operations: list[dict]) -> str:
    lines = [
        "<?php",
        "",
        "declare(strict_types=1);",
        "",
        "namespace Myt\\PhpSdk\\Service;",
        "",
        f"final class {class_name} extends AbstractService",
        "{",
    ]

    for op in operations:
        req_type = op["req_type"]
        lines.append("    /**")
        if op["summary"]:
            lines.append(f"     * {op['summary']}")

        if req_type == "query":
            lines.append("     * @param array<string, mixed> $query")
            lines.append("     * @param array<string, mixed> $options")
        elif req_type == "json":
            lines.append("     * @param array<string, mixed> $body")
            lines.append("     * @param array<string, mixed> $options")
        elif req_type == "multipart":
            lines.append("     * @param array<string, mixed> $form")
            lines.append("     * @param array<string, mixed> $options")
        else:
            lines.append("     * @param array<string, mixed> $options")

        lines.append("     * @return array<string, mixed>|string")
        lines.append("     */")

        mname = op["method_name"]
        http = op["http_method"]
        path = op["path"]

        if req_type == "query":
            rq = ", ".join(f"'{x}'" for x in op["required_query"])
            lines.append(f"    public function {mname}(array $query = [], array $options = []): array|string")
            lines.append("    {")
            lines.append(f"        return $this->requestWithQuery('{http}', '{path}', $query, $options, [{rq}]);")
            lines.append("    }")
        elif req_type == "json":
            rb = ", ".join(f"'{x}'" for x in op["required_body"])
            lines.append(f"    public function {mname}(array $body = [], array $options = []): array|string")
            lines.append("    {")
            lines.append(f"        return $this->requestWithJson('{http}', '{path}', $body, $options, [{rb}]);")
            lines.append("    }")
        elif req_type == "multipart":
            rb = ", ".join(f"'{x}'" for x in op["required_body"])
            lines.append(f"    public function {mname}(array $form = [], array $options = []): array|string")
            lines.append("    {")
            lines.append(f"        return $this->requestWithMultipart('{http}', '{path}', $form, $options, [{rb}]);")
            lines.append("    }")
        else:
            lines.append(f"    public function {mname}(array $options = []): array|string")
            lines.append("    {")
            lines.append(f"        return $this->request('{http}', '{path}', $options);")
            lines.append("    }")

        lines.append("")

    if lines[-1] == "":
        lines.pop()
    lines.append("}")
    lines.append("")

    return "\n".join(lines)


def main() -> int:
    openapi_path = Path(sys.argv[1] if len(sys.argv) > 1 else "api-1.json")
    if not openapi_path.exists():
        print(f"OpenAPI file not found: {openapi_path}")
        return 1

    spec = json.loads(openapi_path.read_text(encoding="utf-8"))
    services = build_services(spec)

    target_dir = Path("src/Service")
    target_dir.mkdir(parents=True, exist_ok=True)

    for class_name, operations in sorted(services.items()):
        content = render_service(class_name, operations)
        (target_dir / f"{class_name}.php").write_text(content, encoding="utf-8")

    total = sum(len(ops) for ops in services.values())
    print(f"Generated {len(services)} services and {total} operations")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
