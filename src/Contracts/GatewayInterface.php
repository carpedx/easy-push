<?php


namespace Carpedx\EasyPush\Contracts;


use Carpedx\EasyPush\Support\Config;

/**
 * Interface GatewayInterface.
 */
interface GatewayInterface
{
    /**
     * Push a short message.
     *
     * @param array $pushload
     * @param Config $config
     *
     * @return array
     */
    public function push(array $pushload, Config $config);
}