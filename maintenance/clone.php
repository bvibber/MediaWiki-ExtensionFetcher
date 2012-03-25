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

class ExtFetchClone extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Clone an extension's git repo from gerrit";
		$this->addOption( 'developer', 'Check out over ssh+git, for developers. Default is to check out over https: anonymously.', false, false );
		$this->addArg( 'extension-names', 'One or more extension names to check out.' );
	}

	public function execute() {
		$exts = ExtensionFetcher::discoverExtensions();
		foreach( $this->mArgs as $arg ) {
			if ( array_key_exists( $arg, $exts ) ) {
				$ext = $exts[$arg];
				$ext->cloneRepo( $this->hasOption( 'developer' ) );
			} else {
				echo "No extension '$arg' is listed in Wikimedia gerrit. Note that names are case-sensitive!\n";
			}
		}
	}
}

$maintClass = "ExtFetchClone";
require_once( DO_MAINTENANCE );
