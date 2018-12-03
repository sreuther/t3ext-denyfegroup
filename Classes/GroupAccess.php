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
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Main function that hooks into TYPO3
 *
 * @author    b:dreizehn GmbH <typo3@b13.de>
 * @package
 */
class GroupAccess extends AbstractGroupAccess
{
    /**
     * @param array $params
     * @param TypoScriptFrontendController $parentObject
     * @return bool
     */
    public function checkEnableFields(&$params, TypoScriptFrontendController $parentObject) {
        $accessAllowed = true;
        if ($params['row']['fe_group_deny']) {
            $pageGroupDenyList = explode(',', $params['row']['fe_group_deny']);
            if (count(array_intersect($this->getUsergroups(), $pageGroupDenyList)) > 0) {
                $accessAllowed = false;
            }
        }
        return $accessAllowed;
    }

    /**
     * @param array $params
     * @param mixed $parentObject
     * @return string    an additional SQL query where
     */
    public function addEnableColumn(&$params, $parentObject)
    {
        $sql = '';
        if (in_array($params['table'], $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['denyfegroup']['tables'])) {
            $usergroups = $this->getUsergroups();
            if (count($usergroups)) {
                $sql = $this->getMultipleGroupsWhereClause($this->fieldName, $params['table']);
            }
        }
        return $sql;
    }
}
