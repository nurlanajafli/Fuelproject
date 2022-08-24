<?php

use common\enums\DriverType;
use common\enums\LoadStopType;
use common\enums\PayrollBatchStatus;
use common\enums\PayrollBatchType;
use common\enums\UnitItemStatus;

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'validImagesMimeTypes' => ['image/jpeg', 'image/png', 'image/tiff'],
    'validDocMimeTypes' => ['application/pdf'],
    'lane' => ['max' => 3],
    'formatter' => [
        'datetime' => [
            'main' => 'MM/dd/yy HH:mm',
            'short' => 'M/d/yy K:mm a'
        ],
        'date' => [
            'db' => 'yyyy-MM-dd',
            'short' => 'M/d/yy',
            'long' => 'M/d/y',
            'longFN' => 'M-d-y',
            'min' => 'M/d'
        ],
        'time' => [
            '12h' => 'hh:mm a',
            '24h' => 'HH:mm'
        ]
    ],
    'companyId' => 1,
    'abbreviations' => [
        UnitItemStatus::AVAILABLE => 'AV',
        UnitItemStatus::IN_USE => 'EN',

        LoadStopType::SHIPPER => 'PU',
        LoadStopType::CONSIGNEE => 'DEL',

        PayrollBatchType::D_DRIVER => 'D',
        PayrollBatchType::E_EMPLOYEE => 'E',
        PayrollBatchType::X_EXECUTIVES => 'X',
        PayrollBatchType::A_AGENT => 'A',
        PayrollBatchType::O_TRUCK_OWNER => 'O',

        PayrollBatchStatus::FINISHED => 'F',
        PayrollBatchStatus::UNFINISHED => 'UNF',

        DriverType::COMPANY_DRIVER => 'C',
        DriverType::OWNER_OPERATOR => 'O',
        DriverType::SUBCONTRACTOR => 'S'
    ],
    'formats' => [
        0 => 'M/d/y',
        1 => 'M/d/yy',
        2 => 'M/d',
        'db' => 'yyyy-MM-dd',
        4 => 'MM/dd/yy HH:mm',
        5 => 'y',
        6 => 'M-d-y',
        7 => 'M-d-y hh_mm a',
        '12h' => 'hh:mm a',
        '24h' => 'HH:mm',
        8 => 'MMMM d, y',
        9 => 'MM/dd/yy',
        10 => 'M/d/yy K:mm a',
        11 => 'h:mm a',
        12 => 'EEEE, LLLL d',
        13 => 'MM/dd/y',
        'ISO8601' => "yyyy-MM-dd'T'HH':'mm':'ssXX",
    ]
];
