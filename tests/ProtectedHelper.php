<?php

class ProtectedHelper
{
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function call($sMethod, array $args = [])
    {
        $class = new \ReflectionClass(get_class($this->object));
        $method = $class->getMethod($sMethod);
        $method->setAccessible(true);
        return $method->invokeArgs($this->object, $args);
    }

    public function getValue($attributeName)
    {
        return \PHPUnit_Framework_Assert::readAttribute($this->object, $attributeName);
    }

    public function setValue($attributeName, $value)
    {
        $refObject   = new \ReflectionObject($this->object);
        $refProperty = $refObject->getProperty($attributeName);
        $refProperty->setAccessible(true);
        $refProperty->setValue($this->object, $value);

        return $this;
    }
}
