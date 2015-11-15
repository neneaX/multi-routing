<?php

class Registry
{
    protected static $storage = [];

    public static function set($name, $value)
    {
        self::$storage[$name] = $value;
    }

    public static function get($name)
    {
        if (array_key_exists($name, self::$storage)) {
            return self::$storage[$name];
        }
    }
}
