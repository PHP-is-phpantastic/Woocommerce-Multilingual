<?php

class WCML_WC_Name_Your_Price {

	public function __construct() {

		add_action( 'init', [ $this, 'init' ], 9 );

	}

	public function init() {
		if ( ! is_admin() ) {
			if ( is_callable( [ 'WC_Name_Your_Price_Compatibility', 'is_nyp_gte' ] ) && WC_Name_Your_Price_Compatibility::is_nyp_gte( '3.0' ) ) {
				add_filter( 'wc_nyp_raw_suggested_price', [ $this, 'product_price_filter' ], 10, 2 );
				add_filter( 'wc_nyp_raw_minimum_price', [ $this, 'product_price_filter' ], 10, 2 );
				add_filter( 'wc_nyp_raw_maximum_price', [ $this, 'product_price_filter' ], 10, 2 );
			} else {
				add_filter( 'woocommerce_raw_suggested_price', [ $this, 'product_price_filter' ], 10, 2 );
				add_filter( 'woocommerce_raw_minimum_price', [ $this, 'product_price_filter' ], 10, 2 );
				add_filter( 'woocommerce_raw_maximum_price', [ $this, 'product_price_filter' ], 10, 2 );
			}
		}
	}

	public function product_price_filter( $price, $product ) {
		return apply_filters( 'wcml_raw_price_amount', $price );
	}

}
