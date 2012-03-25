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

$wgAutoloadClasses['ExtensionFetcher'] = dirname( __FILE__ ) . '/ExtensionFetcher.body.php';
$wgAutoloadClasses['ExtFetchExtension'] = dirname( __FILE__ ) . '/ExtensionFetcher.body.php';
