<?php
namespace Tests\Unit\Services\Order;

use App\Models\Dealer;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderOption;
use App\Services\Orders\OrderCalculator;
use PHPUnit\Framework\TestCase;

class OrderCalculatorTest extends \TestCase
{
    /**
     * @test
     */
    public function testCreation(): void
    {
        $calculator = new OrderCalculator();

        $this->assertInstanceOf(OrderCalculator::class, $calculator);
    }

    /**
     * @test
     */
    public function testCalculateRtoAmount(): void
    {
        $order = new Order();
        $order->rto_net_buydown = 100.55;
        $order->total_sales_price = 200;
        $order->total_rto_options = 49.45;
        $order->payment_type = 'custom';

        $calculator = new OrderCalculator();
        $calculator->setOrder($order);

        $result = $calculator->calculateRtoAmount($order);

        $expected = 249.45;

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function testCalculateSalesTax(): void
    {
        $order = new Order();
        $order->rto_net_buydown = 10.00;
        $order->total_sales_price = 200;

        $order->total_taxable_options = 125.50;
        $order->tax_factor = 0.14;

        $dealer = new Dealer();
        $dealer->tax_factor = 1.5;

        $calculator = new OrderCalculator();
        $calculator->setOrder($order)->setDealer($dealer);

        $result = $this->invokeMethod($calculator, 'calculateSalesTax');

        $expected = 45.57000000000001;

        $this->assertEquals($expected, $result);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}