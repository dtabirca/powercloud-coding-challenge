<?php

declare(strict_types=1);

use Powercloud\Invoice\InvoiceBuilder;
use Powercloud\Invoice\Invoice;
use PHPUnit\Framework\TestCase;

/**
 * Testing Invoice Builder
 */
class InvoiceBuilderTest extends TestCase
{
    /**
     * Trying to create an Invoice using Builder
     */
    public function testTypeInvoice(): void
    {
        $builder = new InvoiceBuilder();
        $builder->createInvoice();
        $builder->addCustomerIdentification('ABC123321CBA');
        $builder->addFirstName('John');
        $builder->addLastName('Doe');
        $builder->addMeterReadingInput(2564);
        $builder->addTariffPricePerKwh(0.028);
        $builder->addBonus([
            'identifier' => 'AABBCC',
            'validFrom' => '2021-01-01',
            'validUntil' => '2021-12-31',
            'value' => '5%',
        ]);
        $builder->addMonthlyTaxesPct(13);
        $builder->addCurrency('EUR');
        $builder->calculateInvoice();
        $builder->prepareForDisplay();
        $invoice = $builder->getInvoice();

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    /**
     * compare output
     */
    public function testInvoiceOutput(): void
    {
        $builder = new InvoiceBuilder();
        $builder->createInvoice();
        $builder->addCustomerIdentification('ABC123321CBA');
        $builder->addFirstName('John');
        $builder->addLastName('Doe');
        $builder->addMeterReadingInput(2564);
        $builder->addTariffPricePerKwh(0.028);
        $builder->addBonus([
            'identifier' => 'AABBCC',
            'validFrom' => '2021-01-01',
            'validUntil' => '2021-12-31',
            'value' => '5%',
        ]);
        $builder->addMonthlyTaxesPct(13);
        $builder->addCurrency('EUR');
        $builder->calculateInvoice();
        $builder->prepareForDisplay();
        $invoice = $builder->getInvoice();

        echo $output = $invoice->getOutput();

        $this->expectOutputString('<h1>Invoice Calculator</h1>
            <p>Here you can see your calculated invoice.</p>
            <div><label>Identification: </label> <span>ABC123321CBA</span></div>
            <div><label>Name: </label> <span>John Doe</span></div>
            <div><label>Tier: </label> <span>C</span></div>
            <div><label>Bonus: </label> <span>5%</span></div>
            <div><label>Taxes: </label> <span>13.00%</span></div>
            <div><label>Total amount: </label> <span>73,25 €</span></div>
            <div><label>Date of calculation: </label> <span>17.06.2021</span></div>', $output);
    }
}
