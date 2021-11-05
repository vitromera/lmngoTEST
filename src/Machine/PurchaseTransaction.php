<?php

namespace App\Machine;

/**
 * Class PurchaseTransaction
 * @package App\Machine
 */
class PurchaseTransaction implements PurchaseTransactionInterface
{
    protected int $items;
    protected float $amount;

    public function __construct(int $items, float $amount) {
        $this->items = $items;
        $this->amount = $amount;
    }

    public function getItemQuantity() {
        return $this->items;
    }

    public function getPaidAmount() {
        return $this->amount;
    }
}