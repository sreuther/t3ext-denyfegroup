<?php
defined('TYPO3_MODE') or die();

$additionalColumns = [
    'fe_group_deny' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:denyfegroup/Resources/Private/Language/db.xml:fe_group_deny',
        'config' => [
            'enableMultiSelectFilterTextfield' => true,
            'renderType' => 'selectMultipleSideBySide',
            'type' => 'select',
            'foreign_table' => 'fe_groups',
            'foreign_table_where' => 'ORDER BY fe_groups.title',
            'size' => 5,
            'minitems' => 0,
            'maxitems' => 50,
            'items' => []
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $additionalColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'fe_group_deny', '', 'after:fe_group');
