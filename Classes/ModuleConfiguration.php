<?php
namespace TYPO3\CMS\Vidi;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * service class which return current configuration
 */
class ModuleConfiguration implements SingletonInterface {

	/**
	 * Return the module configuration.
	 *
	 * @return array
	 */
	public function getModuleConfiguration() {
		$moduleCode = GeneralUtility::_GP('M');
		$result = array();
		if (!empty($GLOBALS['TBE_MODULES_EXT']['vidi'][$moduleCode])) {
			$result = $GLOBALS['TBE_MODULES_EXT']['vidi'][$moduleCode];
		}
		return $result;
	}

	/**
	 * Return the current data type
	 * @return string
	 */
	public function getDataType() {
		$configuration = $this->getModuleConfiguration();
		return $configuration['dataType'];
	}
}
