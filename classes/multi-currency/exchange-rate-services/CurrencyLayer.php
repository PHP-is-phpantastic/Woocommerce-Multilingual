<?php

namespace WCML\MultiCurrency\ExchangeRateServices;

/**
 * Class CurrencyLayer
 */
class CurrencyLayer extends Service {

	/**
	 * @return string
	 */
	public function getId() {
		return 'currencylayer';
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'currencylayer';
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return 'https://currencylayer.com/';
	}

	/**
	 * @return string
	 */
	public function getApiUrl() {
		return 'http://apilayer.net/api/live?access_key=%s&source=%s&currencies=%s&amount=1';
	}

	/**
	 * @return bool
	 */
	public function isKeyRequired() {
		return true;
	}

	/**
	 * @param string $from Base currency.
	 * @param array  $tos  Target currencies.
	 *
	 * @return array
	 * @throws \Exception Thrown where there are connection problems.
	 */
	public function getRates( $from, $tos ) {
		$this->clearLastError();

		$rates = [];

		$url = sprintf( $this->getApiUrl(), $this->getSetting( 'api-key' ), $from, implode( ',', $tos ) );

		$data = wp_safe_remote_get( $url );

		if ( is_wp_error( $data ) ) {

			$http_error = implode( "\n", $data->get_error_messages() );
			$this->saveLastError( $http_error );
			throw new \Exception( $http_error );

		}

		$json = json_decode( $data['body'] );

		if ( empty( $json->success ) ) {
			if ( ! empty( $json->error->info ) ) {
				if ( strpos( $json->error->info, 'You have not supplied an API Access Key' ) !== false ) {
					$error = __( 'You have entered an incorrect API Access Key', 'woocommerce-multilingual' );
				} else {
					$error = $json->error->info;
				}
			} else {
				$error = __( 'Cannot get exchange rates. Connection failed.', 'woocommerce-multilingual' );
			}
			$this->saveLastError( $error );
			throw new \Exception( $error );
		}

		if ( isset( $json->quotes ) ) {
			foreach ( $tos as $to ) {
				if ( isset( $json->quotes->{$from . $to} ) ) {
					$rates[ $to ] = round( $json->quotes->{$from . $to}, \WCML_Exchange_Rates::DIGITS_AFTER_DECIMAL_POINT );
				}
			}
		}

		return $rates;

	}

}
