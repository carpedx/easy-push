<?php


namespace Carpedx\EasyPush\Strategies;


use Carpedx\EasyPush\Contracts\StrategyInterface;

/**
 * Class OrderStrategy.
 */
class OrderStrategy implements StrategyInterface
{
    /**
     * Apply the strategy and return result.
     *
     * @param array $gateways
     *
     * @return array
     */
    public function apply(array $gateways)
    {
        return array_keys($gateways);
    }
}