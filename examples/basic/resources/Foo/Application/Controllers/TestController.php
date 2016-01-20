<?php
namespace Example\Foo\Application\Controllers;

use Example\Foo\Domain\Services\RandomService;
use Example\Foo\Presentation\Models\FooBar;
use Example\Foo\Presentation\Models\Item;
use MultiRouting\Adapters\JsonRpc\Exceptions\NotificationException;

class TestController
{

    /**
     * @var RandomService
     */
    protected $randomService;

    public function __construct(RandomService $randomService)
    {
        $this->randomService = $randomService;
    }

    public function getHomepage()
    {
        return 'Welcome!';
    }

    /**
     * Get some FooBar!!!
     *
     * @param mixed $foo
     * @param mixed $bar
     * @return FooBar
     */
    public function getSomeFooBar($foo = null, $bar = null)
    {
        $foo = $foo ?: $this->randomService->generate();
        $bar = $bar ?: $this->randomService->generate();

        $fooBar = new FooBar($foo, $bar);

        return $fooBar;
    }

    public function getSomeFooBarWithSandwich()
    {
        $fooBar = new FooBar('sand', 'witch');

        return $fooBar;
    }

    public function getDetailsSuccess($input = '')
    {
        return [
            'content' => $input,
            'length' => strlen($input),
            'type' => gettype($input),
        ];
    }

    public function getErrorException($input = '')
    {
        $message = sprintf('Something went wrong [%s].', $input);
        throw new \Exception($message, -10011);
    }

    public function getErrorSoapFault($input = '')
    {
        $message = sprintf('Could not find [%s].', $input);
        throw new \SoapFault($message, -10010);
    }

    public function getItemSuccess($name)
    {
        $item = new Item($name, 501, 'unique_code_guaranteed', true);

        return $item;
    }

    public function deliverWSDLFile()
    {
        // @note error: FastCGI: comm with server "/php-fpm" aborted: error parsing headers: duplicate header 'Content-Type'
        // header('Content-Type: text/xml');
        // @todo set proper XML content headers

        return file_get_contents(WSDL_PATH);
    }
}
