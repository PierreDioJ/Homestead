<?php

/*
* The MIT License
*
* Copyright (c) 2024 "YooMoney", NBСO LLC
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/

namespace Tests\YooKassa\Model\Deal;

use Exception;
use Tests\YooKassa\AbstractTestCase;
use Datetime;
use YooKassa\Model\Metadata;
use YooKassa\Model\Deal\SettlementPayoutRefund;

/**
 * SettlementPayoutPaymentTest
 *
 * @category    ClassTest
 * @author      cms@yoomoney.ru
 * @link        https://yookassa.ru/developers/api
 */
class SettlementPayoutRefundTest extends AbstractTestCase
{
    protected SettlementPayoutRefund $object;

    /**
     * @return SettlementPayoutRefund
     */
    protected function getTestInstance(): SettlementPayoutRefund
    {
        return new SettlementPayoutRefund();
    }

    /**
     * @return void
     */
    public function testSettlementPayoutPaymentClassExists(): void
    {
        $this->object = $this->getMockBuilder(SettlementPayoutRefund::class)->getMockForAbstractClass();
        $this->assertTrue(class_exists(SettlementPayoutRefund::class));
        $this->assertInstanceOf(SettlementPayoutRefund::class, $this->object);
    }

    /**
     * Test property "type"
     * @dataProvider validTypeDataProvider
     * @param mixed $value
     * @throws Exception
     */
    public function testType(mixed $value): void
    {
        $instance = $this->getTestInstance();
        self::assertEmpty($instance->getType());
        self::assertEmpty($instance->type);
        $instance->setType($value);
        self::assertEquals($value, is_array($value) ? $instance->getType()->toArray() : $instance->getType());
        self::assertEquals($value, is_array($value) ? $instance->type->toArray() : $instance->type);
        if (!empty($value)) {
            self::assertNotNull($instance->getType());
            self::assertNotNull($instance->type);
            self::assertContains($instance->getType(), ['payout']);
            self::assertContains($instance->type, ['payout']);
        }
    }

    /**
     * Test invalid property "type"
     * @dataProvider invalidTypeDataProvider
     * @param mixed $value
     * @param string $exceptionClass
     * @return void
     */
    public function testInvalidType(mixed $value, string $exceptionClass): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClass);
        $instance->setType($value);
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function validTypeDataProvider(): array
    {
        $instance = $this->getTestInstance();
        return $this->getValidDataProviderByType($instance->getValidator()->getRulesByPropName('_type'));
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function invalidTypeDataProvider(): array
    {
        $instance = $this->getTestInstance();
        return $this->getInvalidDataProviderByType($instance->getValidator()->getRulesByPropName('_type'));
    }

    /**
     * Test property "amount"
     * @dataProvider validAmountDataProvider
     * @param mixed $value
     *
     * @return void
     * @throws Exception
     */
    public function testAmount(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->setAmount($value);
        self::assertNotNull($instance->getAmount());
        self::assertNotNull($instance->amount);
        self::assertEquals($value, is_array($value) ? $instance->getAmount()->toArray() : $instance->getAmount());
        self::assertEquals($value, is_array($value) ? $instance->amount->toArray() : $instance->amount);
    }

    /**
     * Test invalid property "amount"
     * @dataProvider invalidAmountDataProvider
     * @param mixed $value
     * @param string $exceptionClass
     *
     * @return void
     */
    public function testInvalidAmount(mixed $value, string $exceptionClass): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClass);
        $instance->setAmount($value);
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function validAmountDataProvider(): array
    {
        $instance = $this->getTestInstance();
        return $this->getValidDataProviderByType($instance->getValidator()->getRulesByPropName('_amount'));
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function invalidAmountDataProvider(): array
    {
        $instance = $this->getTestInstance();
        return $this->getInvalidDataProviderByType($instance->getValidator()->getRulesByPropName('_amount'));
    }
}
