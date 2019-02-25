<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "denyfegroup".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Deny Usergroup Access',
	'description' => 'Explicitly denies a page or content to a certain usergroup.',
	'category' => 'fe',
	'version' => '3.0.1',
    'state' => 'stable',
	'clearCacheOnLoad' => false,
    'author' => 'Benjamin Mack',
    'author_email' => 'typo3@b13.de',
    'author_company' => 'b:dreizehn GmbH',
	'constraints' => array(
		'depends' => array(
			'typo3' => '8.7.0-9.9.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		)
	)
);
