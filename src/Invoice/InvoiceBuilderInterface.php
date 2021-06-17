<?php

declare(strict_types=1);

namespace Powercloud\Invoice;

interface InvoiceBuilderInterface
{
    public function createInvoice(): void;
    public function addCustomerIdentification(string $id): void;
    public function addFirstName(string $firstname): void;
    public function addLastName(string $lastname): void;
    public function addMeterReadingInput(int $reading): void;
    public function addTariffPricePerKwh(float $price): void;
    public function addBonus(array $bonus): void;
    public function addMonthlyTaxesPct(int $tax): void;
    public function addCurrency(string $currency): void;
    public function getInvoice(): InvoiceAbstract;
}
