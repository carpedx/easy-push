<?php


namespace Carpedx\EasyPush;


use Carpedx\EasyPush\Contracts\GatewayInterface;
use Carpedx\EasyPush\Contracts\StrategyInterface;
use Carpedx\EasyPush\Exceptions\InvalidArgumentException;
use Carpedx\EasyPush\Exceptions\NoGatewayAvailableException;
use Carpedx\EasyPush\Strategies\OrderStrategy;
use Carpedx\EasyPush\Support\Config;

/**
 * Class EasyPush.
 */
class EasyPush
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * @var Messenger
     */
    protected $messenger;

    /**
     * @var array
     */
    protected $strategies = [];

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
     * Push a message.
     *
     * @param array $pushload
     *
     * @return array
     *
     * @throws NoGatewayAvailableException
     */
    public function push($pushload)
    {
        $gateways = $this->config->get('default.gateways', []);

        return $this->getMessenger()->push($pushload, $this->formatGateways($gateways));
    }

    /**
     * Create a gateway.
     *
     * @param string|null $name
     *
     * @return GatewayInterface|mixed
     *
     * @throws InvalidArgumentException
     */
    public function gateway($name)
    {
        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }

    /**
     * 网关调用策略
     * OrderStrategy顺序调用
     * RandomStrategy随机调用
     */
    /**
     * Get a strategy instance.
     *
     * @param string|null $strategy
     *
     * @return StrategyInterface
     *
     * @throws InvalidArgumentException
     */
    public function strategy($strategy = null)
    {
        if (is_null($strategy)) {
            $strategy = $this->config->get('default.strategy', OrderStrategy::class);
        }

        if (!class_exists($strategy)) {
            $strategy = __NAMESPACE__ . '\Strategies\\' . ucfirst($strategy);
        }

        if (!class_exists($strategy)) {
            throw new InvalidArgumentException("Unsupported strategy \"{$strategy}\"");
        }

        if (empty($this->strategies[$strategy]) || !($this->strategies[$strategy] instanceof StrategyInterface)) {
            $this->strategies[$strategy] = new $strategy($this);
        }

        return $this->strategies[$strategy];
    }

    /**
     * @return Messenger
     */
    public function getMessenger()
    {
        return $this->messenger ?: $this->messenger = new Messenger($this);
    }

    /**
     * Create a new driver instance.
     *
     * @param string $name
     *
     * @return GatewayInterface
     *
     * @throws InvalidArgumentException
     */
    protected function createGateway($name)
    {
        $config = $this->config->get("gateways.{$name}", []);

        $className = $this->formatGatewayClassName($name);
        $gateway = $this->makeGateway($className, $config);

        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" must implement interface %s.', $name, GatewayInterface::class));
        }

        return $gateway;
    }

    /**
     * Make gateway instance.
     *
     * @param string $gateway
     * @param array $config
     *
     * @return GatewayInterface
     *
     * @throws InvalidArgumentException
     */
    protected function makeGateway($gateway, $config)
    {
        if (!class_exists($gateway) || !in_array(GatewayInterface::class, class_implements($gateway))) {
            throw new InvalidArgumentException(sprintf('Class "%s" is a invalid easy-push gateway.', $gateway));
        }

        return new $gateway($config);
    }

    /**
     * Format gateway name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function formatGatewayClassName($name)
    {
        if (class_exists($name) && in_array(GatewayInterface::class, class_implements($name))) {
            return $name;
        }

        $name = ucfirst(str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__ . "\\Gateways\\{$name}Gateway";
    }

    /**
     * @param array $gateways
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    protected function formatGateways(array $gateways)
    {
        $formatted = [];

        foreach ($gateways as $gateway => $setting) {
            if (is_int($gateway) && is_string($setting)) {
                $gateway = $setting;
                $setting = [];
            }

            $formatted[$gateway] = $setting;
            $globalSettings = $this->config->get("gateways.{$gateway}", []);

            if (is_string($gateway) && is_array($setting) && !empty($globalSettings)) {
                $formatted[$gateway] = new Config(array_merge($globalSettings, $setting));
            }
        }

        $result = [];

        foreach ($this->strategy()->apply($formatted) as $name) {
            $result[$name] = $formatted[$name];
        }

        return $result;
    }
}