<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Service;

final class VpcService extends AbstractService
{
    /**
     * 删除网络分组内节点
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMytVpc(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/mytVpc', $query, $options, ['vpcID']);
    }

    /**
     * 编辑VPC节点
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function putMytVpc(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('PUT', '/mytVpc', $body, $options, ['vpcID']);
    }

    /**
     * 指定云机VPC节点
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcAddRule(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/addRule', $body, $options, ['name', 'vpcID']);
    }

    /**
     * 批零指定云机VPC节点
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcAddRuleBatch(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/addRule/batch', $body, $options, ['names', 'vpcID']);
    }

    /**
     * 已设置云机VPC节点
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytVpcContainerRule(array $options = []): array|string
    {
        return $this->request('GET', '/mytVpc/containerRule', $options);
    }

    /**
     * 清除云机VPC节点
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcDelRule(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/delRule', $body, $options, ['name']);
    }

    /**
     * 批量清除云机VPC节点
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcDelRuleBatch(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/delRule/batch', $body, $options, ['name']);
    }

    /**
     * 清除容器域名直连
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMytVpcDomainDirect(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/mytVpc/domainDirect', $query, $options, ['containerID']);
    }

    /**
     * 查询容器域名直连
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytVpcDomainDirect(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/mytVpc/domainDirect', $query, $options, ['containerID']);
    }

    /**
     * 设置容器域名直连
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcDomainDirect(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/domainDirect', $body, $options, ['containerID', 'domains']);
    }

    /**
     * 清除容器域名过滤
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMytVpcDomainFilter(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/mytVpc/domainFilter', $query, $options, ['containerID']);
    }

    /**
     * 查询容器域名过滤
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytVpcDomainFilter(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/mytVpc/domainFilter', $query, $options, ['containerID']);
    }

    /**
     * 设置容器域名过滤
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcDomainFilter(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/domainFilter', $body, $options, ['containerID', 'domains']);
    }

    /**
     * 清除全局域名过滤
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMytVpcDomainFilterGlobal(array $options = []): array|string
    {
        return $this->request('DELETE', '/mytVpc/domainFilter/global', $options);
    }

    /**
     * 查询全局域名过滤
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytVpcDomainFilterGlobal(array $options = []): array|string
    {
        return $this->request('GET', '/mytVpc/domainFilter/global', $options);
    }

    /**
     * 设置全局域名过滤
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcDomainFilterGlobal(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/domainFilter/global', $body, $options, ['domains']);
    }

    /**
     * 删除网络分组列表
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function deleteMytVpcGroup(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('DELETE', '/mytVpc/group', $query, $options, []);
    }

    /**
     * 获取网络分组列表
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytVpcGroup(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/mytVpc/group', $query, $options, []);
    }

    /**
     * 增加网络分组列表
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcGroup(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/group', $body, $options, ['alias']);
    }

    /**
     * 更新网络分组别名
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcGroupAlias(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/group/alias', $body, $options, ['id', 'newAlias']);
    }

    /**
     * 更新指定网络分组
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcGroupUpdate(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/group/update', $body, $options, ['id']);
    }

    /**
     * 增加socks5节点
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcSocks(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/socks', $body, $options, ['alias']);
    }

    /**
     * VPC节点延迟测试
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function getMytVpcTest(array $query = [], array $options = []): array|string
    {
        return $this->requestWithQuery('GET', '/mytVpc/test', $query, $options, []);
    }

    /**
     * 开关DNS白名单
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function postMytVpcWhiteListDns(array $body = [], array $options = []): array|string
    {
        return $this->requestWithJson('POST', '/mytVpc/whiteListDns', $body, $options, ['ruleID']);
    }
}
