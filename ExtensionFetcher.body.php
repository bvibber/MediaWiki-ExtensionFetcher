<?php

/**
 * ExtensionFetcher for MediaWiki
 * Copyright (c) 2012 Brion Vibber & Wikimedia Foundation, Inc.
 * licensed under GNU GPL v2 or later
 */

class ExtensionFetcher {
	static function discoverRepos() {
		global $wgExtFetchGerrit;
	
		$url = 'https://' . $wgExtFetchGerrit . '/r/gerrit/rpc/ProjectAdminService';
		$data = array(
			'jsonrpc' => '2.0',
			'method' => 'visibleProjects',
			'params' => array(),
			'id' => 1,
		);
		$req = MWHttpRequest::factory( $url, array( 'method' => 'POST' ) );
		$req->setHeader( 'Accept', 'application/json' );
		$req->setHeader( 'Content-Type', 'application/json; charset=utf-8' );
		$req->setData( json_encode( $data ) );
		$result = $req->execute();
	
		if ( $result->isOk() ) {
			$decoded = json_decode( $req->getContent(), true );
			return $decoded['result'];
		} else {
			return 'failed';
		}
	}
	
	static function discoverExtensions() {
		$out = array();
		$repos = self::discoverRepos();
		foreach( $repos as $repo ) {
			if ( isset($repo['parent']) && $repo['parent']['name'] == 'mediawiki/extensions' ) {
				$ext = new ExtFetchExtension( $repo );
				$out[$ext->name] = $ext;
			}
		}
		return $out;
	}
}

class ExtFetchExtension {
	public $name;
	public $repo;
	public $description;

	/**
	 * @param array $repo JSON-RPC fragment from Gerrit describing the repo
	 */
	function __construct( $repo ) {
		$this->name = basename( $repo['name']['name'] );
		$this->repo = $repo['name']['name'];
		$this->description = $repo['description'];
	}
	
	/**
	 * Clone the repository
	 * @param bool auth
	 */
	function cloneRepo( $asDeveloper=false ) {
		global $wgExtFetchGerrit, $wgExtFetchGitDeveloper, $wgExtFetchGitAnon;
		if ( $asDeveloper ) {
			$baseUrl = $wgExtFetchGitDeveloper;
		} else {
			$baseUrl = $wgExtFetchGitAnon;
		}
		$url = str_replace( '$1', $wgExtFetchGerrit, $baseUrl ) . '/' . $this->repo;
		$dest = $this->getDirectory();
		$cmd = wfEscapeShellArg(
			'git',
			'clone',
			$url,
			$dest
		);
		echo "$cmd\n";
		wfShellExec( $cmd );
	}
	
	function getLink() {
		return 'https://www.mediawiki.org/wiki/Extension:' . ucfirst( $this->name );
	}
	
	function getDirectory() {
		global $IP;
		return "$IP/extensions/$this->name";
	}
	
	function isPresent() {
		return is_dir( $this->getDirectory() );
	}
}

