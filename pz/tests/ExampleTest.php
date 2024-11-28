<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testAddition()
    {
        $result = 1 + 7;
        $this->assertEquals(8, $result);
    }

    public function testSubtraction()
    {
        $result = 2 - 0;
        $this->assertEquals(2, $result);
    }
}
