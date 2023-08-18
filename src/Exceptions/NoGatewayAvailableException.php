<?php


namespace Carpedx\EasyPush\Exceptions;


/**
 * Class NoGatewayAvailableException.
 */
class NoGatewayAvailableException extends Exception
{
    /**
     * @var array
     */
    public $results = [];

    /**
     * @var array
     */
    public $exceptions = [];

    /**
     * constructor.
     *
     * @param array $results
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $results = [], $code = 0, Throwable $previous = null)
    {
        $this->results = $results;
        $this->exceptions = array_column($results, 'exception', 'gateway');

        parent::__construct('All the gateways have failed. You can get error details by `$exception->getExceptions()`', $code, $previous);
    }
}