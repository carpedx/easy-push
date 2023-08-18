<?php


namespace Carpedx\EasyPush\Gateways;


use Carpedx\EasyPush\Contracts\GatewayInterface;
use Carpedx\EasyPush\Support\Config;

/**
 * Class Gateway.
 */
abstract class Gateway implements GatewayInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }
}