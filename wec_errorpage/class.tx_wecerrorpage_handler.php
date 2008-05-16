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

		// get request domain
		$requestDomain = t3lib_div::getIndpEnv('HTTP_HOST');
		
		// get domain record that corresponds to this domain
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'domainName="'.$requestDomain.' AND hidden=0"','','',1);
		
		if(empty($res) || empty($res[0]['tx_wecerrorpage_404page'])) {
			$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_errorpage']);
			$page404 = $conf['defaultUrl'];
		} else {
			$page404 = $res[0]['tx_wecerrorpage_404page'];
		}

		$this->initializeFrontend();//$res[0]['pid']);
		
		$local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
		$local_cObj->start(null, 'sys_domain');
		
		$code = $local_cObj->getTypoLink_URL($page404);
		
		       // Check if URL is relative
		$url_parts = parse_url($code);
		if ($url_parts['host'] == '')    {
			if(substr($code,0,1) == '/') {
				$code = substr($code,1);
			}
			$code = t3lib_div::getIndpEnv('TYPO3_SITE_URL') . $code;
		} 

		$content = t3lib_div::getUrl($code);
		
		return $content;
	}
	
	function initializeFrontend($pid = '', $feUserObj=''){
	        define('PATH_tslib', PATH_site.'typo3/sysext/cms/tslib/');
	        require_once (PATH_tslib.'/class.tslib_content.php');
	        require_once(t3lib_extMgm::extPath('wec_assessment').'backend/class.tx_wecassessment_tsfe.php');
	        require_once(PATH_t3lib.'class.t3lib_userauth.php');
	        require_once(PATH_tslib.'class.tslib_feuserauth.php');
	        require_once(PATH_t3lib.'class.t3lib_befunc.php');
	        require_once(PATH_t3lib.'class.t3lib_timetrack.php');
	        require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');
	        require_once(PATH_t3lib.'class.t3lib_page.php');

	        $GLOBALS['TT'] = new t3lib_timeTrack;

	        // ***********************************
	        // Creating a fake $TSFE object
	        // ***********************************
	        $TSFEclassName = t3lib_div::makeInstanceClassName('tx_wecassessment_tsfe');
	        $GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], $pid, '0', 1, '', '','','');
	        $GLOBALS['TSFE']->connectToMySQL();
	        if($feUserObj==''){
	                $GLOBALS['TSFE']->initFEuser();
	        }else{
	                $GLOBALS['TSFE']->fe_user = &$feUserObj;
	        }

	        $GLOBALS['TSFE']->fetch_the_id();
	        $GLOBALS['TSFE']->getPageAndRootline();
	        $GLOBALS['TSFE']->initTemplate();
	        $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
	        $GLOBALS['TSFE']->forceTemplateParsing = 1;
	        $GLOBALS['TSFE']->getConfigArray();
	}
}

?>