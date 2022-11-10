<?php

namespace WCML\Options;

use WPML\Settings\PostType\Automatic;
use WPML\Setup\Option;

class WPML {

	/** @return bool */
	public static function shouldTranslateEverything() {
		return method_exists( Option::class, 'shouldTranslateEverything' )
		       && Option::shouldTranslateEverything();
	}

	/**
	 * @param string $postType
	 * @param bool   $state
	 */
	public static function setAutomatic( $postType, $state ) {
		if ( method_exists( Automatic::class, 'set' ) ) {
			Automatic::set( $postType, $state );
		}
	}
}
