<?php
namespace TYPO3\CMS\Vidi\Controller;
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

/**
 * Base Controller which is meant to include all common
 */
class BaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
	 */
	protected $frontendUser;

	/**
	 * Returns a matcher object.
	 * Note: this code is very much tight to the BE module. It should / could probably be improved at one point...
	 *
	 * @return \TYPO3\CMS\Vidi\QueryElement\Matcher
	 */
	protected function createMatcherObject() {

		/** @var $matcher \TYPO3\CMS\Vidi\QueryElement\Matcher */
		$matcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Vidi\QueryElement\Matcher');

		// Special case for Grid in the BE using jQuery DataTables plugin.
		// Retrieve a possible search term from GP.
		$searchTerm = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('sSearch');
		if (strlen($searchTerm) > 0) {
			$matcher->setSearchTerm($searchTerm);
			$matcher->addCategory($searchTerm);
		}

		return $matcher;
	}

	/**
	 * Returns an order object.
	 * Note: this code is very much tight to the BE module. It should / could probably be improved at one point...
	 *
	 * @return \TYPO3\CMS\Vidi\QueryElement\Order
	 */
	protected function createOrderObject() {
		// Default sort
		$order['uid'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;

		// Retrieve a possible id of the column from the request
		$columnPosition = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('iSortCol_0');
		if ($columnPosition > 0) {
			$field = \TYPO3\CMS\Vidi\Tca\ServiceFactory::getGridService('sys_file')->getFieldNameByPosition($columnPosition);

			$direction = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('sSortDir_0');
			$order = array(
				$field => $direction
			);
		}
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Vidi\QueryElement\Order', $order);
	}

	/**
	 * Returns a pager object.
	 * Note: this code is very much tight to the BE module. It should / could probably be improved at one point...
	 *
	 * @return \TYPO3\CMS\Vidi\QueryElement\Pager
	 */
	protected function createPagerObject() {

		/** @var $pager \TYPO3\CMS\Vidi\QueryElement\Pager */
		$pager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Vidi\QueryElement\Pager');

		// Set items per page
		// DataTables plugin is not flexible enough - or makes it complicated - to encapsulate parameters like tx_vidi_pi[page]
		// $this->request->hasArgument('page')
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('iDisplayLength')) {
			$limit = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('iDisplayLength');
			$pager->setLimit($limit);
		}

		// Set offset
		$offset = 0;
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('iDisplayStart')) {
			$offset = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('iDisplayStart');
		}
		$pager->setOffset($offset);

		// set page
		$page = 1;
		if ($pager->getLimit() > 0) {
			$page = round($pager->getOffset() / $pager->getLimit());
		}
		$pager->setPage($page);

		return $pager;
	}
}
?>