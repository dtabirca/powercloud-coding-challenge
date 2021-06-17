<?php

declare(strict_types=1);

namespace Powercloud\Invoice;

class Invoice extends InvoiceAbstract
{
    public string $currency = 'EUR';
    private \DateTime $generated_date;
    private float $total_payment;
    private float $total_taxes;
    private string $tier;
    private string $output;

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function calculateTierAndTotals(): void
    {
        $currentDate = $this->getCurrentDate();
        if ($this->meter_reading_input > 0 && $this->meter_reading_input <= 1500) {
            $brutto = (int) $this->meter_reading_input * (float) $this->tariff_price_per_kwh;
            if (
                    (new \DateTime($this->bonus['validFrom'])) <= $currentDate &&
                    $currentDate <= (new \DateTime($this->bonus['validUntil']))
            ) {
                $net = $brutto + ($brutto * $this->monthlyTaxesPct / 100) - (strstr('%', (string)$this->bonus['value']) ? ($brutto * (float)$this->bonus['value'] / 100) : (float)$this->bonus['value']);
            } else {
                $net = $brutto + ($brutto * $this->monthlyTaxesPct / 100);
            }
            $this->tier = 'A';
        } elseif ($this->meter_reading_input > 1500 && $this->meter_reading_input <= 2500) {
            $brutto = (int) $this->meter_reading_input * (float) $this->tariff_price_per_kwh;
            if (
                    (new \DateTime($this->bonus['validFrom'])) <= $currentDate &&
                    $currentDate <= (new \DateTime($this->bonus['validUntil']))
            ) {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 2) / 100);
                $net -= (strstr('%', (string)$this->bonus['value']) ? ($brutto * (float)$this->bonus['value'] / 100) : (float)$this->bonus['value']);
            } else {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 2) / 100);
            }
            $this->tier = 'B';
        } elseif ($this->meter_reading_input > 2500 && $this->meter_reading_input <= 4000) {
            $brutto = (int) $this->meter_reading_input * (float) $this->tariff_price_per_kwh;
            if (
                (new \DateTime($this->bonus['validFrom'])) <= $currentDate &&
                $currentDate <= (new \DateTime($this->bonus['validUntil']))
            ) {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 4) / 100) - (strstr('%', (string)$this->bonus['value']) ? ($brutto * (float)$this->bonus['value'] / 100) : (float)$this->bonus['value']);
            } else {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 4) / 100);
            }
            $this->tier = 'C';
        } else {
            $brutto = (int) $this->meter_reading_input * (float) $this->tariff_price_per_kwh;
            if (
                    (new \DateTime($this->bonus['validFrom'])) <= $currentDate &&
                    $currentDate <= (new \DateTime($this->bonus['validUntil']))
            ) {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 8) / 100) - (strstr('%', (string)$this->bonus['value']) ? ($brutto * (float)$this->bonus['value'] / 100) : (float)$this->bonus['value']);
            } else {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 8) / 100);
            }
            $this->tier = 'D';
        }
        $this->generated_date = $currentDate;
        $this->total_payment = $net;
        $this->total_taxes = $this->getMonthlyTaxesPct();
    }

    public function prepareOutput()
    {
        $totalTaxes = sprintf('%.2f%%', (float) $this->total_taxes);
        $fmt = new \NumberFormatter('de_DE', \NumberFormatter::CURRENCY);
        $totalAmount = $fmt->formatCurrency($this->total_payment, $this->getCurrency());
        $bonus = $this->bonus['value'];
        $date = $this->generated_date->format('d.m.Y');
        $this->output = "<h1>Invoice Calculator</h1>
            <p>Here you can see your calculated invoice.</p>
            <div><label>Identification: </label> <span>{$this->customer_identification}</span></div>
            <div><label>Name: </label> <span>{$this->customer_first_name} {$this->customer_last_name}</span></div>
            <div><label>Tier: </label> <span>{$this->tier}</span></div>
            <div><label>Bonus: </label> <span>{$bonus}</span></div>
            <div><label>Taxes: </label> <span>{$totalTaxes}</span></div>
            <div><label>Total amount: </label> <span>{$totalAmount}</span></div>
            <div><label>Date of calculation: </label> <span>{$date}</span></div>";
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    private function getCurrentDate(): \DateTime
    {
        return new \DateTime('now');
    }
}
