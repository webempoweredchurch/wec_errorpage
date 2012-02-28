<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TYPO3_CONF_VARS['FE']['pageUnavailable_handling'] = 'USER_FUNCTION:'.t3lib_extMgm::extPath('wec_errorpage').'class.tx_wecerrorpage_handler.php:tx_wecerrorpage_handler->pageNotAvailable';
$TYPO3_CONF_VARS['FE']['pageNotFound_handling'] = 'USER_FUNCTION:'.t3lib_extMgm::extPath('wec_errorpage').'class.tx_wecerrorpage_handler.php:tx_wecerrorpage_handler->pageNotFound';
?>