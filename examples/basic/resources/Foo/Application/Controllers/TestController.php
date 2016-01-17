<?php
namespace Example\Foo\Application\Controllers;

use Example\Foo\Domain\Services\RandomService;
use Example\Foo\Presentation\Models\FooBar;

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
}