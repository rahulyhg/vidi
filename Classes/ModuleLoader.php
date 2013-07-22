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
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * service class, used in other extensions to register a vidi based backend module
 */
class ModuleLoader {

	/**
	 * The type of data being listed (which corresponds to a table name in TCA)
	 *
	 * @var string
	 */
	protected $dataType;

	/**
	 * @var string
	 */
	protected $mainModule = 'user';

	/**
	 * @var string
	 */
	protected $position = '';

	/**
	 * @var string
	 */
	protected $icon = 'EXT:vidi/ext_icon.gif';

	/**
	 * @var string
	 */
	protected $moduleLanguageFile = 'LLL:EXT:vidi/Resources/Private/Language/locallang_module.xlf';

	/**
	 * The module key such as m1, m2.
	 *
	 * @var string
	 */
	protected $moduleKey = 'm1';

	/**
	 * @var string[]
	 */
	protected $additionalJavaScriptFiles = array();

	/**
	 * @var string[]
	 */
	protected $additionalStyleSheetFiles = array();

	/**
	 * @param string $dataType
	 */
	public function __construct($dataType) {
		$this->dataType = $dataType;
	}

	/**
	 * Register the module
	 *
	 * @return void
	 */
	public function register() {
		$subModuleName = $this->dataType . '_' . $this->moduleKey;
		$moduleCode = sprintf('%s_Vidi%s',
			$this->mainModule,
			\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($subModuleName)
		);

		$GLOBALS['TBE_MODULES_EXT']['vidi'][$moduleCode] = array();
		$GLOBALS['TBE_MODULES_EXT']['vidi'][$moduleCode]['dataType'] = $this->dataType;
		$GLOBALS['TBE_MODULES_EXT']['vidi'][$moduleCode]['additionalJavaScriptFiles'] = $this->additionalJavaScriptFiles;
		$GLOBALS['TBE_MODULES_EXT']['vidi'][$moduleCode]['additionalStyleSheetFiles'] = $this->additionalStyleSheetFiles;

		ExtensionUtility::registerModule(
			'vidi',
			$this->mainModule, // Make media module a submodule of 'user'
			$subModuleName,
			$this->position, // Position
			array(
				'Content' => 'list, listRow',
			),
			array(
				'access' => 'user,group',
				'icon' => $this->icon,
				'labels' => $this->moduleLanguageFile,
			)
		);
	}

	/**
	 * @param string $icon
	 * @return $this
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}

	/**
	 * @param string $mainModule
	 * @return $this
	 */
	public function setMainModule($mainModule) {
		$this->mainModule = $mainModule;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMainModule() {
		return $this->mainModule;
	}

	/**
	 * @param string $moduleLanguageFile
	 * @return $this
	 */
	public function setModuleLanguageFile($moduleLanguageFile) {
		$this->moduleLanguageFile = $moduleLanguageFile;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getModuleLanguageFile() {
		return $this->moduleLanguageFile;
	}

	/**
	 * @param string $position
	 * @return $this
	 */
	public function setPosition($position) {
		$this->position = $position;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param array $files
	 * @return $this
	 */
	public function addJavaScriptFiles(array $files) {
		foreach ($files as $file) {
			$this->additionalJavaScriptFiles[] = $file;
		}
		return $this;
	}

	/**
	 * @param array $files
	 * @return $this
	 */
	public function addStyleSheetFiles(array $files) {
		foreach ($files as $file) {
			$this->additionalStyleSheetFiles[] = $file;
		}
		return $this;
	}
}
