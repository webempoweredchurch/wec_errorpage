<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$tempColumns = Array (
	"tx_wecerrorpage_404page" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:wec_errorpage/locallang_db.xml:sys_domain.tx_wecerrorpage_404page",		
		"config" => Array (
			"type"     => "input",
			"size"     => "15",
			"max"      => "255",
			"eval"     => "trim",
			"wizards"  => array(
				"_PADDING" => 2,
				"link"     => array(
					"type"         => "popup",
					"title"        => "Link",
					"icon"         => "link_popup.gif",
					"script"       => "browse_links.php?mode=wizard",
					"params"       => array(
						"blindLinkOptions" => "folder,mail"
					),
					"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
				)
			)
		)
	),
	"tx_wecerrorpage_503page" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:wec_errorpage/locallang_db.xml:sys_domain.tx_wecerrorpage_503page",		
		"config" => Array (
			"type"     => "input",
			"size"     => "15",
			"max"      => "255",
			"eval"     => "trim",
			"wizards"  => array(
				"_PADDING" => 2,
				"link"     => array(
					"type"         => "popup",
					"title"        => "Link",
					"icon"         => "link_popup.gif",
					"script"       => "browse_links.php?mode=wizard",
					"params"       => array(
						"blindLinkOptions" => "folder,mail"
					),
					"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
				)
			)
		)
	),
);


t3lib_div::loadTCA("sys_domain");
t3lib_extMgm::addTCAcolumns("sys_domain",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("sys_domain","tx_wecerrorpage_404page;;;;1-1-1, tx_wecerrorpage_503page");
?>