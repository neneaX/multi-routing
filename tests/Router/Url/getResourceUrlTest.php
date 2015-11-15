<?php
namespace tests\Router\Url;


use MultiRouting\Router\Exceptions\ResourceUrlException;

class getResourceUrlTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $helper;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the filter throws an exception then throw exception.
     */
    public function testWhenTheFilterThrowsAnExceptionThenThrowException()
    {
        $resourceUrl = '/some/url/part.html';

        $this->object = \Mockery::mock('\\MultiRouting\\Router\\Url')->makePartial();
        $this->helper = new \ProtectedHelper($this->object);
        $this->helper->setValue('resourceUrl', $resourceUrl);

        static::assertEquals($resourceUrl, $this->object->getResourceUrl());
    }
}
