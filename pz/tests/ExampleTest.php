<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testAddition()
    {
        $result = 1 + 2;
        $this->assertEquals(3, $result);
    }

    public function testSubtraction()
    {
        $result = 2 - 1;
        $this->assertEquals(1, $result);
    }
}
