<?php

namespace App\Machine;

/**
 * Class CigaretteMachine
 * @package App\Machine
 */
class CigaretteMachine implements MachineInterface
{
    const ITEM_PRICE = 4.99;
    protected array $changeArrangement;
    protected float $changeAmount;

    public function __construct() {
        $this->changeArrangement = [];
        $this->changeAmount = 0.0;
    }

    public function getItemPrice() {
        return CigaretteMachine::ITEM_PRICE;
    }

    public function getChange() {
        return $this->changeArrangement;
    }

    public function getChangeAmount() {
        return $this->changeAmount;
    }

    public function execute(PurchaseTransactionInterface $purchaseTransaction) {
        $amount = $purchaseTransaction->getPaidAmount();
        $items = $purchaseTransaction->getItemQuantity();

        $possiblePurchase = (int) ($amount / ($items * CigaretteMachine::ITEM_PRICE));
        if($possiblePurchase == 0) {
            return "nofunds";
        }

        $totalPrice = (float) $items * CigaretteMachine::ITEM_PRICE;

        $change = (float) bcsub($amount, $totalPrice, 2);
        $this->changeAmount = $change;
        CigaretteMachine::arrangeChange($change);

        return "success";
    }

    function arrangeChange($change) {
        $values = array(1.00, 0.5, 0.25, 0.1, 0.05, 0.01);
        $arrangements = array();

        foreach($values as $coinValue) {
            $coins = 0;
            while(bccomp($change, $coinValue, 2) >= 0) {
                $coins++;
                $change = (float) bcsub($change, $coinValue, 2);
            }
            if($coins > 0) {
                $arrangement = [ (string) $coinValue, $coins ];
                array_push($arrangements, $arrangement);
            }
        }
        $this->changeArrangement = $arrangements;
    }
}