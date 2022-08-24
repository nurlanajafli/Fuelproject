<?php


namespace common\enums;


use TRS\Enum\Enum;

class PurchaseOrderType extends Enum
{
    const CURRENT_ASSET = 'Current Asset';
    const FIXED_ASSET = 'Fixed Asset';
    const COST_OF_SALES = 'Cost of Sales';
    const EXPENSE = 'Expense';
}