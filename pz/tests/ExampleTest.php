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
        $result = 2 - 0;
        $this->assertEquals(2, $result);
    }
}
