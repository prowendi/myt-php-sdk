<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Support;

use RuntimeException;
use SplFileInfo;

final class MultipartBuilder
{
    /**
     * @param array<string, mixed> $payload
     * @return array{0: array<int, array<string, mixed>>, 1: array<int, resource>}
     */
    public static function build(array $payload): array
    {
        $multipart = [];
        $openedResources = [];

        foreach ($payload as $name => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value) && self::isMultipartPart($value)) {
                if (!isset($value['name'])) {
                    $value['name'] = (string) $name;
                }
                $multipart[] = $value;
                continue;
            }

            $filename = null;
            $part = [
                'name' => (string) $name,
                'contents' => self::normalizeContents($value, $openedResources, $filename),
            ];

            if ($filename !== null) {
                $part['filename'] = $filename;
            }

            $multipart[] = $part;
        }

        return [$multipart, $openedResources];
    }

    /**
     * @param array<string, mixed> $part
     */
    private static function isMultipartPart(array $part): bool
    {
        return array_key_exists('contents', $part);
    }

    /**
     * @param array<int, resource> $openedResources
     * @return mixed
     */
    private static function normalizeContents(mixed $value, array &$openedResources, ?string &$filename)
    {
        $filename = null;

        if (is_resource($value)) {
            return $value;
        }

        if ($value instanceof SplFileInfo) {
            $path = $value->getRealPath() ?: $value->getPathname();
            return self::openFile($path, $openedResources, $filename);
        }

        if (is_string($value) && is_file($value)) {
            return self::openFile($value, $openedResources, $filename);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($encoded === false) {
            throw new RuntimeException('Failed to encode multipart field to JSON.');
        }

        return $encoded;
    }

    /**
     * @param array<int, resource> $openedResources
     * @return resource
     */
    private static function openFile(string $path, array &$openedResources, ?string &$filename)
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new RuntimeException(sprintf('Cannot open file: %s', $path));
        }

        $openedResources[] = $handle;
        $filename = basename($path);

        return $handle;
    }
}
