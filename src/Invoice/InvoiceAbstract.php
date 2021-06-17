<?php

declare(strict_types=1);

namespace Powercloud\Invoice;

abstract class InvoiceAbstract
{
    public string $customer_identification;
    public string $customer_first_name;
    public string $customer_last_name;
    public int $meter_reading_input;
    public float $tariff_price_per_kwh;
    public array $bonus;
    public int $monthlyTaxesPct;

    public function __constructor(): void
    {
    }

    public function getCustomerIdentification(): string
    {
        return $this->customer_identification;
    }

    public function getFirstName(): string
    {
        return $this->customer_first_name;
    }

    public function getLastName(): string
    {
        return $this->customer_last_name;
    }

    public function addMeterReadingInput(): int
    {
        return $this->meter_reading_input;
    }

    public function getTariffPricePerKwh(): float
    {
        return $this->tariff_price_per_kwh;
    }

    public function getBonus(): array
    {
        return $this->bonus;
    }

    public function getMonthlyTaxesPct(): int
    {
        return $this->monthlyTaxesPct;
    }
}
