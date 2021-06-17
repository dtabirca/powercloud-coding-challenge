<?php

declare(strict_types=1);

class InvoiceCalculator
{
    public $tariff_price_per_kwh;
    public $customer_identification;
    public $customer_first_name;
    public $customer_last_name;
    public $invoice = [
        'generated_date' => '',
        'total_payment' => '',
        'total_taxes' => '',
    ];
    public $meter_reading_input;
    public $monthlyTaxesPct;
    public $bonus = [
        'identifier' => '',
        'validFrom' => '',
        'validUtil' => '',
        'value' => '',
    ];
    private $tier;
    public function calculate(&$invoice)
    {
        $currentDate = new \DateTime('now');
        if ($this->meter_reading_input > 0 && $this->meter_reading_input <= 1500) {
            $brutto = (int) $this->meter_reading_input * (float) $this->tariff_price_per_kwh;
            if ((new \DateTime($this->bonus['validFrom'])) <= $currentDate && $currentDate <= (new \DateTime($this->bonus['validUntil']))) {
                $net = $brutto + ($brutto * $this->monthlyTaxesPct / 100) - (strstr('%', (string)$this->bonus['value']) ? ($brutto * (float)$this->bonus['value'] / 100) : (float)$this->bonus['value']);
            } else {
                $net = $brutto + ($brutto * $this->monthlyTaxesPct / 100);
            }
            $this->tier = 'A';
        } elseif ($this->meter_reading_input > 1500 && $this->meter_reading_input <= 2500) {
            $brutto = (int) $this->meter_reading_input * (float) $this->tariff_price_per_kwh;
            if ((new \DateTime($this->bonus['validFrom'])) <= $currentDate && $currentDate <= (new \DateTime($this->bonus['validUntil']))) {
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
            if ((new \DateTime($this->bonus['validFrom'])) <= $currentDate && $currentDate <= (new \DateTime($this->bonus['validUntil']))) {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 8) / 100) - (strstr('%', (string)$this->bonus['value']) ? ($brutto * (float)$this->bonus['value'] / 100) : (float)$this->bonus['value']);
            } else {
                $net = $brutto + ($brutto * ($this->monthlyTaxesPct - 8) / 100);
            }
            $this->tier = 'D';
        }
        $invoice['generated_date'] = $currentDate;
        $invoice['total_payment'] = $net;
        $invoice['total_taxes'] = $this->monthlyTaxesPct;
    }

    public function displayInvoice()
    {
        $this->calculate($this->invoice);
        $totalTaxes = sprintf('%.2f%%', (float) $this->invoice['total_taxes']);
        $fmt = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);
        $totalAmount = $fmt->formatCurrency( $this->invoice['total_payment'] , 'EUR');
        $bonus = $this->bonus['value'];
        $date = $this->invoice['generated_date']->format('d.m.Y');
        echo "<h1>Invoice Calculator</h1>
            <p>Here you can see your calculated invoice.</p>
            <div><label>Identification: </label> <span>{$this->customer_identification}</span></div>
            <div><label>Name: </label> <span>{$this->customer_first_name} {$this->customer_last_name}</span></div>
            <div><label>Tier: </label> <span>{$this->tier}</span></div>
            <div><label>Bonus: </label> <span>{$bonus}</span></div>
            <div><label>Taxes: </label> <span>{$totalTaxes}</span></div>
            <div><label>Total amount: </label> <span>{$totalAmount}</span></div>
            <div><label>Date of calculation: </label> <span>{$date}</span></div>";
    }
}

$invoice = new InvoiceCalculator();
$invoice->customer_identification = 'ABC123321CBA';
$invoice->customer_first_name = 'John';
$invoice->customer_last_name = 'Doe';
$invoice->meter_reading_input = 2564;
$invoice->tariff_price_per_kwh = 0.028;
$invoice->bonus = [
    'identifier' => 'AABBCC',
    'validFrom' => '2021-01-01',
    'validUntil' => '2021-12-31',
    'value' => '5%',
];
$invoice->monthlyTaxesPct = 13;

$invoice->displayInvoice();