<?php

namespace WCML\Reports\Orders;

class Factory implements \IWPML_Backend_Action_Loader, \IWPML_REST_Action_Loader, \IWPML_Deferred_Action_Loader {

	/**
	 * @return string
	 */
	public function get_load_action() {
		return 'init';
	}

	/**
	 * @return \IWPML_Action|null
	 */
	public function create() {
		/**
		 * @global \wpdb $GLOBALS['wpdb']
		 * @name $wpdb
		 */
		global $wpdb;

		if ( wcml_is_multi_currency_on() ) {
			return new Hooks( $wpdb );
		}
	}

}
