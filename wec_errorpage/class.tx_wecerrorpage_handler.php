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
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'domainName="'.$requestDomain.'" AND hidden=0','','',1);

		$ref->pageErrorHandler($this->processUrl($res, 'tx_wecerrorpage_404page', 'pageNotFound_handling'));
	}
	
	function pageNotAvailable($params, $ref) {

		// get request domain
		$requestDomain = t3lib_div::getIndpEnv('HTTP_HOST');

		// get domain record that corresponds to this domain
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'sys_domain', 'domainName="'.$requestDomain.'" AND hidden=0','','',1);
		
		$ref->pageErrorHandler($this->processUrl($res, 'tx_wecerrorpage_503page', 'pageUnavailable_handling'));
	}
	
	function processUrl($res, $field, $extconfFallback) {
		// TODO: devlog start
		if(TYPO3_DLOG) {
			t3lib_div::devLog('Starting error handling using field '.$field, 'wec_errorpage');
		}
		// devlog end
		// if there is no domain record, or no special 404 handling set, fall back to default
		if(empty($res) || empty($res[0][$field])) {
			$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_errorpage']);
			$url = $conf[$extconfFallback];
			// TODO: devlog start
			if(TYPO3_DLOG) {
				t3lib_div::devLog('No domain specific setting, falling back to ext conf. Value: '.$url, 'wec_errorpage');
			}
			// devlog end
		} else {
			$url = $res[0][$field];
			// TODO: devlog start
			if(TYPO3_DLOG) {
				t3lib_div::devLog('Using domain setting for domain "'.$res[0]['domainName'].'". Value: '.$url, 'wec_errorpage');
			}
			// devlog end
		}

		if(is_bool($url)) {
			// TODO: devlog start
			if(TYPO3_DLOG) {
				t3lib_div::devLog('Value is a boolean, passing directly to error handler', 'wec_errorpage');
			}
			// devlog end
			return $url;			
		}

		
		// now we check for REDIRECT or READFILE prefix.
		if(strpos($url, 'READFILE:') === 0 || strpos($url, 'REDIRECT:') === 0 || strpos($url, 'USER_FUNC:') === 0) {
			// TODO: devlog start
			if(TYPO3_DLOG) {
				t3lib_div::devLog('Found prefix, passing directly to error handler. URL: '.$url, 'wec_errorpage');
			}
			// devlog end
			return $url;
		}
		
		// now we pass it through typolink and process from there
		
		// initialize a fake front end
		$this->initializeFrontend();

		// create a cObj for the typolink method
		$local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
		$local_cObj->start(null, 'sys_domain');

		// pass our url through typolink to get a proper url
		$code = $local_cObj->getTypoLink_URL($url);
		// TODO: devlog start
		if(TYPO3_DLOG) {
			t3lib_div::devLog('Passed through typolink. Result: '.$code, 'wec_errorpage');
		}
		// devlog end
		
		if(empty($code)) return null;
		
		$parsed = parse_url($code);
		
		if (substr($code,0,1)!='/' AND empty($parsed['scheme'])) {
			$code = '/'.$code;	
			// TODO: devlog start
			if(TYPO3_DLOG) {
				t3lib_div::devLog('Relative URL and missing leading slash; adding. URL: '.$code, 'wec_errorpage');
			}
			// devlog end
		} 

		if (substr($code,-1)!='/' AND !empty($parsed['scheme'])) {
			$code.='/';
			// TODO: devlog start
			if(TYPO3_DLOG) {
				t3lib_div::devLog('Absolute URL but no trailing slash; adding. URL: '.$code, 'wec_errorpage');
			}
			// devlog end
		}
		
		// TODO: devlog start
		if(TYPO3_DLOG) {
			t3lib_div::devLog('Final URL passed to error handler: '.$code, 'wec_errorpage');
			t3lib_div::devLog('Finished error handling', 'wec_errorpage');
		}
		// devlog end
		return $code;	
	}
	
	function initializeFrontend($pid = '', $feUserObj=''){
			define('PATH_tslib', PATH_site.'typo3/sysext/cms/tslib/');
			require_once(PATH_tslib.'/class.tslib_content.php');
			require_once(PATH_tslib.'class.tslib_fe.php');
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
			$TSFEclassName = t3lib_div::makeInstanceClassName('tslib_fe');
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