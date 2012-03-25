<?php

/**
 * ExtensionFetcher for MediaWiki
 * Copyright (c) 2012 Brion Vibber & Wikimedia Foundation, Inc.
 * licensed under GNU GPL v2 or later
 */

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = dirname( __FILE__ ) . '/../../..';
}
require_once( "$IP/maintenance/Maintenance.php" );

class ExtFetchListExtensions extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "List available extensions from gerrit-managed git repos";
	}

	public function execute() {
		$exts = ExtensionFetcher::discoverExtensions();
		foreach( $exts as $ext ) {
			$line = $ext->name;
			if ($ext->description ) {
				$line .= ': ' . $ext->description;
			}
			print $line . "\n";
		}
	}
}

$maintClass = "ExtFetchListExtensions";
require_once( DO_MAINTENANCE );
