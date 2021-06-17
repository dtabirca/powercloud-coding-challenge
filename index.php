<?php

/**
 * calling invoice builder from client code in real scenario
 */

require_once __DIR__ . '/vendor/autoload.php';

use Powercloud\Invoice\Invoice;
use Powercloud\Invoice\InvoiceBuilder;

function createInvoice(InvoiceBuilder $builder): Invoice
{
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
    return $builder->getInvoice();
}

$invoice = createInvoice(new InvoiceBuilder());
$output = $invoice->getOutput();
echo $output;
