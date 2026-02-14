<?php

declare(strict_types=1);

namespace Myt\PhpSdk;

use Myt\PhpSdk\Config\ClientConfig;
use Myt\PhpSdk\Contract\HttpTransportInterface;
use Myt\PhpSdk\Http\GuzzleTransport;
use Myt\PhpSdk\Service\AndroidService;
use Myt\PhpSdk\Service\AndroidV2Service;
use Myt\PhpSdk\Service\AuthService;
use Myt\PhpSdk\Service\BackupService;
use Myt\PhpSdk\Service\InfoService;
use Myt\PhpSdk\Service\LlmService;
use Myt\PhpSdk\Service\MacVlanService;
use Myt\PhpSdk\Service\MytBridgeService;
use Myt\PhpSdk\Service\PhoneModelService;
use Myt\PhpSdk\Service\ServerService;
use Myt\PhpSdk\Service\TerminalService;
use Myt\PhpSdk\Service\VpcService;

final class MytSdk
{
    private readonly ApiClient $apiClient;

    private ?AndroidService $androidService = null;
    private ?AndroidV2Service $androidV2Service = null;
    private ?BackupService $backupService = null;
    private ?AuthService $authService = null;
    private ?InfoService $infoService = null;
    private ?TerminalService $terminalService = null;
    private ?LlmService $llmService = null;
    private ?MacVlanService $macVlanService = null;
    private ?MytBridgeService $mytBridgeService = null;
    private ?VpcService $vpcService = null;
    private ?PhoneModelService $phoneModelService = null;
    private ?ServerService $serverService = null;

    /**
     * @param array<string, mixed>|ClientConfig $config
     */
    public function __construct(array|ClientConfig $config, ?HttpTransportInterface $transport = null)
    {
        $clientConfig = is_array($config) ? ClientConfig::fromArray($config) : $config;
        $this->apiClient = new ApiClient($transport ?? new GuzzleTransport($clientConfig));
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>|string
     */
    public function request(string $method, string $path, array $options = []): array|string
    {
        return $this->apiClient->request($method, $path, $options);
    }

    public function android(): AndroidService
    {
        return $this->androidService ??= new AndroidService($this->apiClient);
    }

    public function androidV2(): AndroidV2Service
    {
        return $this->androidV2Service ??= new AndroidV2Service($this->apiClient);
    }

    public function backup(): BackupService
    {
        return $this->backupService ??= new BackupService($this->apiClient);
    }

    public function auth(): AuthService
    {
        return $this->authService ??= new AuthService($this->apiClient);
    }

    public function info(): InfoService
    {
        return $this->infoService ??= new InfoService($this->apiClient);
    }

    public function terminal(): TerminalService
    {
        return $this->terminalService ??= new TerminalService($this->apiClient);
    }

    public function llm(): LlmService
    {
        return $this->llmService ??= new LlmService($this->apiClient);
    }

    public function macVlan(): MacVlanService
    {
        return $this->macVlanService ??= new MacVlanService($this->apiClient);
    }

    public function mytBridge(): MytBridgeService
    {
        return $this->mytBridgeService ??= new MytBridgeService($this->apiClient);
    }

    public function vpc(): VpcService
    {
        return $this->vpcService ??= new VpcService($this->apiClient);
    }

    public function phoneModel(): PhoneModelService
    {
        return $this->phoneModelService ??= new PhoneModelService($this->apiClient);
    }

    public function server(): ServerService
    {
        return $this->serverService ??= new ServerService($this->apiClient);
    }
}
