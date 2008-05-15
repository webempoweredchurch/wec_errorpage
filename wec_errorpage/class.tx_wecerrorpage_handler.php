<?php
/***************************************************************
* Copyright notice
*
* (c) 2005-2008 Christian Technology Ministries International Inc.
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries 
* International (http://CTMIinc.org). The WEC is developing TYPO3-based
* (http://typo3.org) free software for churches around the world. Our desire
* is to use the Internet to help offer new life through Jesus Christ. Please
* see http://WebEmpoweredChurch.org/Jesus.
*
* You can redistribute this file and/or modify it under the terms of the
* GNU General Public License as published by the Free Software Foundation;
* either version 2 of the License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This file is distributed in the hope that it will be useful for ministry,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the file!
***************************************************************/
require_once(PATH_tslib.'class.tslib_content.php');

class tx_wecerrorpage_handler {

	function pageNotFound($params, $ref) {
		// TODO: debug
		t3lib_div::debug($params);

		
		$local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
		$local_cObj->start(null, 'sys_domain');
		
		
		// get request domain
		$requestDomain = t3lib_div::getIndpEnv('HTTP_HOST');
		
		// get domain record that corresponds to this domain
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'domainName="'.$requestDomain.'"','','',1);
		
		$page404 = $res[0]['tx_wecerrorpage_404page'];

		$bla = $local_cObj->getTypoLink_URL($page404);
		
		// TODO: debug
		t3lib_div::debug($bla);
		// TODO: debug
		t3lib_div::debug($res);
		
	}
}

?>