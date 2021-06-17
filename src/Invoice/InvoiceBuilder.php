<?php

declare(strict_types=1);

namespace Powercloud\Invoice;

class InvoiceBuilder implements InvoiceBuilderInterface
{
    private Invoice $invoice;

    public function createInvoice(): void
    {
        $this->invoice = new Invoice();
    }

    public function addCustomerIdentification(string $id): void
    {
        $this->invoice->customer_identification = $id;
    }

    public function addFirstName(string $firstname): void
    {
        $this->invoice->customer_first_name = $firstname;
    }

    public function addLastName(string $lastname): void
    {
        $this->invoice->customer_last_name = $lastname;
    }

    public function addMeterReadingInput(int $reading): void
    {
        $this->invoice->meter_reading_input = $reading;
    }

    public function addTariffPricePerKwh(float $price): void
    {
        $this->invoice->tariff_price_per_kwh = $price;
    }

    public function addBonus(array $bonus): void
    {
        $this->invoice->bonus = $bonus;
    }

    public function addMonthlyTaxesPct(int $tax): void
    {
        $this->invoice->monthlyTaxesPct = $tax;
    }

    public function addCurrency(string $currency): void
    {
        $this->invoice->currency = $currency;
    }

    public function calculateInvoice(): void
    {
        $this->invoice->calculateTierAndTotals();
    }

    public function prepareForDisplay(): void
    {
        $this->invoice->prepareOutput();
    }

    public function getInvoice(): InvoiceAbstract
    {
        return $this->invoice;
    }
}
