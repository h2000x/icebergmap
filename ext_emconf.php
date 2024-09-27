<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'icebergmap',
    'description' => 'Get data from https://usicecenter.gov/Products/AntarcIcebergs store it in a Database and create am KML-File from this Data.',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Justabunchof\\Icebergmap\\' => 'Classes/',
        ],
    ],
];
