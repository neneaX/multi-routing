<?php
namespace Example\Foo\Presentation\Models;

use Example\Common\Infrastructure\Contracts\Jsonable;

class Item implements Jsonable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * FooBar constructor.
     *
     * @param $name
     * @param $quantity
     * @param $code
     * @param $enabled
     */
    public function __construct($name, $quantity = 1, $code = '', $enabled = true)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->code = $quantity;
        $this->enabled = (bool)$enabled;
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'Code' => $this->code,
            'Name' => $this->name,
            'Quantity' => $this->quantity,
            'Enabled' => $this->enabled,
        ]);
    }

    public function __toString()
    {
        return $this->name . ' | ' . $this->quantity . ' | ' . $this->code . ' | ' . $this->enabled;
    }
}