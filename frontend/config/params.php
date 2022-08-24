<?php
use kartik\mpdf\Pdf;

return [
    'adminEmail' => 'admin@example.com',
    'buttonWidget' => [
        'icon' => [
            'tag' => 'i',
            'class' => 'fas fa-',
        ]
    ],
    'activeForm' => [
        'fieldConfig' => [
            'template'=> "{label}{input}{error}",
            'horizontalCssClasses' => [
                'label' => '',
                'error' => '',
                'hint' => '',
            ],
            'options' => ['class' => 'form-group']
        ],
        'horizontalFormConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
        'horizontalContactFormConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-4',
                'wrapper' => 'col-sm-6',
                'error' => '',
                'hint' => '',
            ],
        ],
        'horizontalLoadFormConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
        'dispatchFormConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-5',
                'wrapper' => 'col-sm-7',
                'error' => '',
                'hint' => '',
            ],
        ],
        'noIndentCheckbox' => [
            'template' => '<div class="col-sm-12 mb-3"><div class="custom-control custom-checkbox">{input}{label}</div></div>',
        ]
    ],
    'Pdf' => [
        'mode' => Pdf::MODE_UTF8,
        'format' => Pdf::FORMAT_A4,
        'orientation' => Pdf::ORIENT_PORTRAIT,
        'cssFile' => '@markup/frontend/dist/css/print.css',
        'methods' => ['SetFooter' => ['{PAGENO}']]
    ],
    'dt' => [
        'templates' => [
            'default' => '<div class="card shadow mb-4"><div class="card-body"><div class="table table-responsive">{toolbar}{table}</div></div></div>',
            'dropdown' => '<div class="dropdown-menu dropdown-menu--table"><div class="table table-responsive">{table}</div></div>',
            0 => '<div class="table table-responsive m-0">{toolbar}{table}</div>',
        ]
    ],
    'welcomeSubject' => 'Welcome to Mobile App'
];