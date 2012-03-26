<?php

/**
 * ExtensionFetcher for MediaWiki
 * Copyright (c) 2012 Brion Vibber & Wikimedia Foundation, Inc.
 * licensed under GNU GPL v2 or later
 */

class SpecialExtensionFetcher extends SpecialPage {
	public function __construct() {
		parent::__construct( 'ExtensionFetcher', 'extension-install' );
	}

	public function execute( $subpage ) {
		global $wgRequest, $wgUser;

		$this->setHeaders();

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		$extensions = ExtensionFetcher::discoverExtensions();
		$out = $this->getOutput();
		$out->addModules( 'ext.extfetch' );
		
		$out->addHTML( Html::openElement( 'table' ) );

		foreach( $extensions as $ext ) {
			$this->addExtensionRow( $ext );
		}

		$out->addHTML( Html::closeElement( 'table' ) );
	}
	
	protected function addExtensionRow( ExtFetchExtension $ext ) {
		$out = $this->getOutput();
		$classes = array();
		if ( $ext->isPresent() ) {
			$classes[] = 'present';
			$button = wfMessage( 'extfetch-present' )->escaped();
		} else {
			$classes[] = 'available';
			$button = Html::element( 'button', array( 'class' => 'mw-extfetch-fetch', 'data-extension' => $ext->name ), wfMsg( 'extfetch-fetch' ) );
		}
		$out->addHTML( Html::openElement( 'tr', array( 'class' => implode( ' ', $classes ) ) ) );

		$out->addHTML( Html::openElement( 'td' ) );
		$out->addHTML( $button );
		$out->addHTML( Html::closeElement( 'td' ) );

		$out->addHTML( Html::openElement( 'td' ) );
		$out->addHTML( Html::element( 'a', array( 'href' => $ext->getLink() ), $ext->name ) );
		$out->addHTML( Html::closeElement( 'td' ) );

		$out->addHTML( Html::element( 'td', array(), $ext->description ) );

		$out->addHTML( Html::closeElement( 'tr' ) );
	}
}

