<?php

/**
 * ExtensionFetcher for MediaWiki
 * Copyright (c) 2012 Brion Vibber & Wikimedia Foundation, Inc.
 * licensed under GNU GPL v2 or later
 */

/* Global configuration for the extension */
$wgExtFetchGerrit = 'gerrit.wikimedia.org';
$wgExtFetchGitDeveloper = "ssh://$1:29418";
$wgExtFetchGitAnon = "https://$1/r/p";

$dir = dirname( __FILE__ );
$wgAutoloadClasses['ExtensionFetcher'] = $dir . '/ExtensionFetcher.body.php';
$wgAutoloadClasses['ExtFetchExtension'] = $dir . '/ExtensionFetcher.body.php';
$wgAutoloadClasses['SpecialExtensionFetcher'] = $dir . '/specials/SpecialExtensionFetcher.php';
$wgAutoloadClasses['ApiExtFetch'] = $dir . '/api/ApiExtFetch.php';

$wgExtensionMessagesFiles['ExtensionFetcher'] = $dir . '/ExtensionFetcher.i18n.php';

$wgSpecialPages['ExtensionFetcher'] = 'SpecialExtensionFetcher';

$wgAPIModules['extfetch'] = 'ApiExtFetch';

$wgResourceModules['ext.extfetch'] = array(
	'localBasePath' => dirname( __FILE__ ) . '/modules',
	'remoteExtPath' => 'ExtensionFetcher/modules',
	'scripts' => 'ext.extfetch.js',
	'dependencies' => array(
		'mediawiki.util',
		'mediawiki.user',
		'user.tokens'
	)
);

$wgAvailablePermissions[] = 'extension-install';
$wgGroupPermissions['sysop']['extension-install'] = true; // ???? safe default? :)


