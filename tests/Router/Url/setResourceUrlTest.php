<?php
namespace tests\Router\Url;


use MultiRouting\Router\Exceptions\ResourceUrlException;

class setResourceUrlTest extends \PHPUnit_Framework_TestCase
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
        $exception = new ResourceUrlException('Invalid.');

        $this->object = \Mockery::mock('\\MultiRouting\\Router\\Url')->makePartial();
        $this->object->shouldAllowMockingProtectedMethods();
        $this->object->shouldReceive('filterResourceUrl')
            ->once()
            ->with($resourceUrl)
            ->andThrow($exception);

        $this->helper = new \ProtectedHelper($this->object);

        try {
            $this->helper->call('setResourceUrl', [$resourceUrl]);
            static::fail();

        } catch (ResourceUrlException $e) {
            $message = 'Resource URL Exception: The requested resource URL is invalid.';
            static::assertEquals($message, $e->getMessage());
            static::assertEquals(400, $e->getCode());
            static::assertSame($exception, $e->getPrevious());
        }
    }
}
