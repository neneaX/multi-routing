<?php
namespace Example\Foo\Domain\Services;

class RandomService
{

    /**
     * A foo bar list
     *
     * @var array
     */
    protected $labels = ['foo', 'bar', 'testing', 'is', 'great', 'random'];

    /**
     * @return int
     */
    protected function lastKey()
    {
        return count($this->labels) - 1;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $key = rand(0, $this->lastKey());

        return $this->labels[$key];
    }
}
