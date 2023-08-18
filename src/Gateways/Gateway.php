<?php


namespace Carpedx\EasyPush\Gateways;


use Carpedx\EasyPush\Contracts\GatewayInterface;
use Carpedx\EasyPush\Support\Config;

/**
 * Class Gateway.
 */
abstract class Gateway implements GatewayInterface
{
    const DEFAULT_TIMEOUT = 5.0;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var float
     */
    protected $timeout;

    /**
     * constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Return timeout.
     *
     * @return int|mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: $this->config->get('timeout', self::DEFAULT_TIMEOUT);
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);

        return $this;
    }
}