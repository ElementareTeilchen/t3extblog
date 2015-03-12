<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2015 Felix Nagel <info@felixnagel.com>
 *
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
 *
 *
 * @package t3extblog
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_T3extblog_Domain_Repository_AbstractRepository extends Tx_Extbase_Persistence_Repository {

	/**
	 * @var integer
	 **/
	protected $pageUid = NULL;

	/**
	 * @param null $pageUid
	 *
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function createQuery($pageUid = NULL) {
		$query = parent::createQuery();

		if ($pageUid !== NULL) {
			$this->setPid($pageUid);
		}

		if ($this->pageUid !== NULL) {
			$query->getQuerySettings()->setStoragePageIds(array($this->pageUid));
		}

		return $query;
	}

	/**
	 * @param $uid Page uid
	 */
	public function setPid($uid) {
		$this->pageUid = $uid;
	}
}

?>