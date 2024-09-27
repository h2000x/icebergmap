<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata',
        'label' => 'iceberg',
        'label_alt' => 'datadate',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'delete' => 'deleted',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'iceberg, firstappearance, latitude, longitude, width, length, squarekm, datadate',
        'iconfile' => 'EXT:icebergmap/Resources/Public/Icons/tx_icebergmap_domain_model_icebergdata.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'iceberg, datadate, latitude, longitude, width, length, squarekm, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime']
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_icebergmap_domain_model_icebergdata',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check'
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'iceberg' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.iceberg',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_icebergmap_domain_model_iceberg',
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],
        'latitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.latitude',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'eval' => 'trim,required,double2'
            ]
        ],
        'longitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.longitude',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'eval' => 'trim,required,double2'
            ]
        ],
        'width' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.width',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'eval' => 'trim,required,double2'
            ]
        ],
        'length' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.length',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'eval' => 'trim,required,double2'
            ]
        ],
        'squarekm' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.squarekm',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'eval' => 'trim,required,double2'
            ],
        ],
        'datadate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:tx_icebergmap_domain_model_icebergdata.datadate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
                'default' => null,
            ],
        ]

    ]
];
