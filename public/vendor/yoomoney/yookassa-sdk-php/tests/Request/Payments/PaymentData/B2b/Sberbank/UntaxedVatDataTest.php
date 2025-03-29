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

namespace Tests\YooKassa\Request\Payments\PaymentData\B2b\Sberbank;

use Exception;
use Tests\YooKassa\AbstractTestCase;
use Datetime;
use YooKassa\Model\Metadata;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\UntaxedVatData;

/**
 * UntaxedVatDataTest
 *
 * @category    ClassTest
 * @author      cms@yoomoney.ru
 * @link        https://yookassa.ru/developers/api
 */
class UntaxedVatDataTest extends AbstractTestCase
{
    protected UntaxedVatData $object;

    /**
     * @return UntaxedVatData
     */
    protected function getTestInstance(): UntaxedVatData
    {
        return new UntaxedVatData();
    }

    /**
     * @return void
     */
    public function testUntaxedVatDataClassExists(): void
    {
        $this->object = $this->getMockBuilder(UntaxedVatData::class)->getMockForAbstractClass();
        $this->assertTrue(class_exists(UntaxedVatData::class));
        $this->assertInstanceOf(UntaxedVatData::class, $this->object);
    }

    /**
     * Test property "type"
     *
     * @return void
     * @throws Exception
     */
    public function testType(): void
    {
        $instance = $this->getTestInstance();
        self::assertNotNull($instance->getType());
        self::assertNotNull($instance->type);
        self::assertContains($instance->getType(), ['untaxed']);
        self::assertContains($instance->type, ['untaxed']);
    }

    /**
     * Test invalid property "type"
     * @dataProvider invalidTypeDataProvider
     * @param mixed $value
     * @param string $exceptionClass
     *
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
    public function invalidTypeDataProvider(): array
    {
        $instance = $this->getTestInstance();
        return $this->getInvalidDataProviderByType($instance->getValidator()->getRulesByPropName('_type'));
    }
}
