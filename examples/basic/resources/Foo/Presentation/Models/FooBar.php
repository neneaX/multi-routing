<?php
namespace Example\Foo\Presentation\Models;

use Example\Common\Infrastructure\Contracts\Jsonable;

class FooBar implements Jsonable
{
    /**
     * @var mixed
     */
    protected $foo;

    /**
     * @var mixed
     */
    protected $bar;

    /**
     * FooBar constructor.
     *
     * @param $foo
     * @param $bar
     */
    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'foo' => $this->foo,
            'bar' => $this->bar
        ]);
    }

    public function __toString()
    {
        return $this->foo . ' | ' . $this->bar;
    }
}