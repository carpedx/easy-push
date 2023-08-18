<?php


namespace Carpedx\EasyPush\Strategies;


use Carpedx\EasyPush\Contracts\StrategyInterface;

/**
 * Class RandomStrategy.
 */
class RandomStrategy implements StrategyInterface
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
        uasort($gateways, function () {
            return mt_rand() - mt_rand();
        });

        return array_keys($gateways);
    }
}