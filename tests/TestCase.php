<?php


namespace Carpedx\EasyPush\Tests;


class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    public function registerMockery()
    {
        \Mockery::globalHelpers();
    }

    /**
     * @after
     */
    public function closeMockery()
    {
        \Mockery::close();
    }
}