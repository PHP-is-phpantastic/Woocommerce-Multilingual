<?php

namespace WCML\MultiCurrency;

class GeolocationBackendHooks implements \IWPML_Backend_Action {

	public function add_hooks() {
		add_filter( 'wcml_geolocation_is_used', [ Geolocation::class, 'isUsed' ] );
	}
}
