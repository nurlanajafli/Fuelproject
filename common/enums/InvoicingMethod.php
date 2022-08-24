<?php

namespace common\enums;

use TRS\Enum\Enum;

class InvoicingMethod extends Enum
{
    const PRINT_INVOICES = 'Print Invoices';
    const EMAIL_INVOICES = 'Email Invoices';
    const BOTH = 'Both';
    const NONE = 'None';
}