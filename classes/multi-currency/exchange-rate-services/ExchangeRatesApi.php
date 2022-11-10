<?php

namespace WCML\MultiCurrency\ExchangeRateServices;

/**
 * Class ExchangeRatesApi
 */
class ExchangeRatesApi extends Service {

	/**
	 * @return string
	 */
	public function getId() {
		return 'exchangeratesapi';
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'Exchange rates API';
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return 'https://exchangeratesapi.io/';
	}

	/**
	 * @return string
	 */
	public function getApiUrl() {
		return 'http://api.exchangeratesapi.io/v1/latest?access_key=%1$s&base=%2$s&symbols=%3$s';
	}

	/**
	 * @return bool
	 */
	public function isKeyRequired() {
		return true;
	}

}
