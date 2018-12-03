<?php

use \B13\DenyFeGroup\GroupAccess;
use \B13\DenyFeGroup\PageLayoutViewDrawFooter;

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Add deny check to page repository (actual page request)
// disabled for now for performance reasons
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_page.php']['getPage']['denyfegroup'] = 'EXT:denyfegroup/Classes/PageRepositoryGetPage.php:B13\DenyFeGroup\PageRepositoryGetPage';

if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['denyfegroup'])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['denyfegroup'] = [];
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['denyfegroup'] = array_merge_recursive(
	[
		'tables' => [
			'pages',
			'tt_content',
		]
	],
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['denyfegroup']
);

// Add check to rootline (to respect "Extend to subpages" option)
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_checkEnableFields']['denyfegroup'] = GroupAccess::class . '->checkEnableFields';

// Add check to content elements / menus via enable columns SQL
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_page.php']['addEnableColumns']['denyfegroup'] = GroupAccess::class . '->addEnableColumn';


$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',fe_group_deny';

// Add backend footer info for content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawFooter'][] = PageLayoutViewDrawFooter::class;