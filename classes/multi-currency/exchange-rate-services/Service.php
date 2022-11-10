<?php

namespace WCML\MultiCurrency\ExchangeRateServices;

use WPML\FP\Obj;

/**
 * Class Service
 */
abstract class Service {

	/** @var array  */
	private $settings;

	/**
	 * @return string
	 */
	abstract public function getId();

	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @return string
	 */
	abstract public function getUrl();

	/**
	 * @return string
	 */
	abstract public function getApiUrl();

	/**
	 * @return bool
	 */
	abstract public function isKeyRequired();

	/**
	 * @param string $from Base currency.
	 * @param array  $tos  Target currencies.
	 *
	 * @return mixed
	 * @throws \Exception Thrown where there are connection problems.
	 */
	public function getRates( $from, $tos ) {
		$this->clearLastError();

		$rates = [];

		if ( $this->isKeyRequired() ) {
			$url = sprintf( $this->getApiUrl(), $this->getSetting( 'api-key' ), $from, implode( ',', $tos ) );
		} else {
			$url = sprintf( $this->getApiUrl(), $from, implode( ',', $tos ) );
		}

		$data = wp_safe_remote_get( $url );

		if ( is_wp_error( $data ) ) {

			$http_error = implode( "\n", $data->get_error_messages() );
			$this->saveLastError( $http_error );
			throw new \Exception( $http_error );

		}

		$json = json_decode( $data['body'] );

		if ( empty( $json->rates ) ) {
			if ( isset( $json->error->info ) ) {
				$error = $json->error->info;
			} else {
				$error = __( 'Cannot get exchange rates. Connection failed.', 'woocommerce-multilingual' );
			}
			$this->saveLastError( $error );
			throw new \Exception( $error );
		}

		foreach ( $json->rates as $to => $rate ) {
			$rates[ $to ] = round( $rate, \WCML_Exchange_Rates::DIGITS_AFTER_DECIMAL_POINT );
		}

		return $rates;
	}

	/**
	 * @return array
	 */
	public function getSettings() {
		if ( null === $this->settings ) {
			$this->settings = get_option( 'wcml_exchange_rate_service_' . $this->getId(), [] );
		}

		return $this->settings;
	}

	private function saveSettings() {
		update_option( 'wcml_exchange_rate_service_' . $this->getId(), $this->getSettings() );
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function getSetting( $key ) {
		return Obj::prop( $key, $this->getSettings() );
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function saveSetting( $key, $value ) {
		$this->getSettings();
		$this->settings[ $key ] = $value;
		$this->saveSettings();
	}

	/**
	 * @param string $error_message
	 */
	public function saveLastError( $error_message ) {
		$this->saveSetting(
			'last_error',
			[
				'text' => $error_message,
				'time' => date_i18n( 'F j, Y g:i a', false, true ),
			]
		);
	}

	public function clearLastError() {
		$this->saveSetting( 'last_error', false );
	}

	/**
	 * @return mixed
	 */
	public function getLastError() {
		return $this->getSetting( 'last_error' );
	}

}
