<?php

/**
 * ExtensionFetcher for MediaWiki
 * Copyright (c) 2012 Brion Vibber & Wikimedia Foundation, Inc.
 * licensed under GNU GPL v2 or later
 */

class ApiExtFetch extends ApiBase {

	public function execute() {
		global $wgUser;
		// Before doing anything at all, let's check permissions
		if ( !$wgUser->isAllowed( 'extension-install' ) ) {
			$this->dieUsage( 'You don\'t have permission to install extensions', 'permissiondenied' );
		}

		$params = $this->extractRequestParams();
		$extension = $params['extension'];
		$exts = ExtensionFetcher::discoverExtensions();
		if ( !array_key_exists( $extension, $exts ) ) {
			$this->dieUsage( "Invalid extension ``{$extension}''", 'invalidextension' );
		}
		
		$ext = $exts[$extension];
		$ext->cloneRepo();

		$r[$ext] = 'installed';

		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getAllowedParams() {
		return array(
			'extension' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'token' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'extension' => 'Name of extension',
			'token' => 'Edit token. You can get one of these through prop=info.' ,
		);
	}

	public function getDescription() {
		return array(
			'Attempt to fetch and install an extension from the master gerrit repositories'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to update code' ),
			array( 'code' => 'invalidextension', 'info' => "Invalid extension ``extension''" ),
		) );
	}
	
	public function getVersion() {
		return '1';
	}
}
