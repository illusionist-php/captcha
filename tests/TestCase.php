<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    /**
     * Mock the property of an object
     *
     * @param  mixed  $object
     * @param  string  $name
     * @param  mixed  $value
     * @return void
     */
    public function mockProperty($object, $name, $value)
    {
        $class = new ReflectionClass($object);
        $property = $class->getProperty($name);

        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }
}
