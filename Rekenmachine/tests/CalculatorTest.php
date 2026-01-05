<?php

namespace Rekenmachine\Tests;



require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Rekenmachine\classes\Rekenmachine;

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Rekenmachine();
    }

    //test 1 voor subtract methode
    public function testSubtractTwoPositiveNumbers()
    {
        echo "Test: 10 - 4 = 6";
        $result = $this->calculator->subtract(10, 4);
        $this->assertEquals(6, $result, "10 - 4 moet 6 zijn");
    }

    public function testSubtractPositiveMinusNegative()
    {
        echo "Test: 5 - (-3) = 8";
        $result = $this->calculator->subtract(5, -3);
        $this->assertEquals(8, $result, "5 - (-3) moet 8 zijn");
    }

    public function testSubtractZeroMinusPositive()
    {
        echo "Test: 0 - 7 = -7";
        $result = $this->calculator->subtract(0, 7);
        $this->assertEquals(-7, $result, "0 - 7 moet -7 zijn");
    }

    public function testSubtractTwoNegativeNumbers()
    {
        echo "Test: -5 - (-2) = -3";
        $result = $this->calculator->subtract(-5, -2);
        $this->assertEquals(-3, $result, "-5 - (-2) moet -3 zijn");
    }

   //test 2 voor multiply methode

    public function testMultiplyTwoPositiveNumbers()
    {
        echo "Test: 6 * 7 = 42";
        $result = $this->calculator->multiply(6, 7);
        $this->assertEquals(42, $result, "6 * 7 moet 42 zijn");
    }

    public function testMultiplyPositiveByNegative()
    {
        echo "Test: 5 * (-4) = -20";
        $result = $this->calculator->multiply(5, -4);
        $this->assertEquals(-20, $result, "5 * (-4) moet -20 zijn");
    }

    public function testMultiplyByZero()
    {
        echo "Test: 0 * 15 = 0";
        $result = $this->calculator->multiply(0, 15);
        $this->assertEquals(0, $result, "0 * 15 moet 0 zijn");
    }

    public function testMultiplyTwoNegativeNumbers()
    {
        echo "Test: -3 * (-5) = 15";
        $result = $this->calculator->multiply(-3, -5);
        $this->assertEquals(15, $result, "-3 * (-5) moet 15 zijn");
    }

    //test 3 voor delen methode
    public function testDivideTwoPositiveNumbers()
    {
        echo "Test: 10 / 2 = 5";
        $result = $this->calculator->divide(10, 2);
        $this->assertEquals(5, $result, "10 / 2 moet 5 zijn");
    }

    public function testDividePositiveByNegative()
    {
        echo "Test: 15 / (-3) = -5";
        $result = $this->calculator->divide(15, -3);
        $this->assertEquals(-5, $result, "15 / (-3) moet -5 zijn");
    }

    public function testDivideWithDecimalResult()
    {
        echo "Test: 5 / 2 = 2.5";
        $result = $this->calculator->divide(5, 2);
        $this->assertEquals(2.5, $result, "5 / 2 moet 2.5 zijn");
    }

    public function testDivideByZeroThrowsException()
    {
        echo "Test: 10 / 0 = Exception verwacht";
        $this->expectException(\Exception::class); 
        $this->expectExceptionMessage("Delen door nul is niet mogelijk!");

        $this->calculator->divide(10, 0);
    }

    public function testDivideZeroByPositiveNumber()
    {
        echo "Test: 0 / 5 = 0";
        $result = $this->calculator->divide(0, 5);
        $this->assertEquals(0, $result, "0 / 5 moet 0 zijn");
    }

    public function testDivideNegativeByNegative()
    {
        echo "Test: -12 / (-4) = 3";
        $result = $this->calculator->divide(-12, -4);
        $this->assertEquals(3, $result, "-12 / (-4) moet 3 zijn");
    }

    public function testDivideLargeNumbers()
    {
        echo "Test: 100 / 25 = 4";
        $result = $this->calculator->divide(100, 25);
        $this->assertEquals(4, $result, "100 / 25 moet 4 zijn");
    }
}
