<?php

namespace B13\DenyFeGroup;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2013 b:dreizehn GmbH <typo3@b13.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Main function that hooks into TYPO3
 *
 * @author    b:dreizehn GmbH <typo3@b13.de>
 * @package
 */
abstract class AbstractGroupAccess
{
    /**
     * @var string DB field name, can be configurable later on
     */
    protected $fieldName = 'fe_group_deny';

    /**
     * @var array usergroups (without -1, 0, etc)
     */
    protected $usergroups;

    /**
     * inverse function of t3lib_page::getMultipleGroupsWhereClause()
     *
     * Creating where-clause for checking group access to elements in enableFields function
     *
     * @param string $field Field with group list
     * @param string $table Table name
     * @return string AND sql-clause
     * @see enableFields()
     */
    protected function getMultipleGroupsWhereClause($field, $table)
    {
        $expressionBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table)
            ->expr();
        $orChecks = [];
        // If the field is empty, then OK
        $orChecks[] = $expressionBuilder->eq($field, $expressionBuilder->literal(''));
        // If the field is NULL, then OK
        $orChecks[] = $expressionBuilder->isNull($field);
        // If the field contains zero, then OK
        $orChecks[] = $expressionBuilder->eq($field, $expressionBuilder->literal('0'));

        $andChecks = [];
        foreach ($this->usergroups as $value) {
            $check = $expressionBuilder->inSet($field, $expressionBuilder->literal($value));
            $andChecks[] = '(NOT ' . $check . ')';
        }
        return ' AND ((' . $expressionBuilder->orX(...$orChecks) . ') OR (' . $expressionBuilder->andX(...$andChecks) . '))';
    }

    /**
     * Get all user groups without the pseudo user groups 0,-1,-2
     * Do some basic caching for this session
     *
     * @return array
     */
    protected function getUsergroups()
    {
        if (!is_array($this->usergroups)) {
            $this->usergroups = [];
            $allgroups = GeneralUtility::intExplode(',', $GLOBALS['TSFE']->gr_list);
            foreach ($allgroups as $groupId) {
                if ($groupId <= 0) {
                    continue;
                }
                $this->usergroups[] = $groupId;
            }
        }
        return $this->usergroups;
    }
}
