<?php
/**
 * @name        ECBService
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The service provider: European Central Bank.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers\ServiceProviders;

use BiberLtd\Bundles\CurrencyBundle\Drivers;

class ECBService extends Drivers\ServiceProviderDriver {
    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     */
    public function __construct(){
        $service_config = array(
            'code'      => str_replace('Service', '', __CLASS__),
            'name'      => 'European Central Bank',
            'url'       => 'http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml',
            'from'      => 'EUR',
            'to'        => array(
                            'USD', 'JPY', 'BGN', 'CZK', 'DKK', 'GBP', 'HUF',
                            'LTL', 'LVL', 'PLN', 'RON', 'SEK', 'CHF', 'NOK',
                            'HRK', 'TRY', 'AUD', 'BRL', 'CAD', 'CNY', 'HKD',
                            'IDR', 'ILS', 'INR', 'KRW', 'MXN', 'MYR', 'NZD',
                            'PHP', 'SGD', 'THB', 'ZAR'
                        )
        );
        parent::__construct($service_config);
    }
    /**
     * @name 			getConversionRate()
     *  				Returns the conversion rate.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @throws          CurrencyServiceToException
     * @throws          CurrencyServiceFromException
     *
     * @param           CurrencyBundle\Drivers\Currencies           $from
     * @param           CurrencyBundle\Drivers\Currencies           $to
     * @param           string                                      $xml
     *
     * @return          numeric                                     $rate
     */
    public function getConversionRate($from, $to, $xml){
        $this->isConvertible($to, $from);
        /**
         * if from = EUR & to = EUR
         */
        if($from->getCode() == 'EUR' && $to->getCode() == 'EUR'){
            return 1;
        }
        /**
         * if to = EUR
         */
        if($to->getCode() == 'EUR'){
            foreach($xml->Cube->Cube->Cube as $currency){
                if ($currency->attributes()->currency == $from->getCode()){
                    $rate = 1 / (double) $currency->attributes()->rate;
                }
            }
        }
        /**
         * if from = EUR
         */
        if($from->getCode() == 'EUR'){
            foreach($xml->Cube->Cube->Cube as $currency){
                if ($currency->attributes()->currency == $to->getCode()){
                    $rate = (double) $currency->attributes()->rate;
                }
            }
        }
        /**
         * All other cases
         */
        if($from->getCode() != 'EUR' && $to->getCode() != 'EUR'){
            foreach($xml->Cube->Cube->Cube as $currency){
                if ($currency->attributes()->currency == $from->getCode()){
                    $rate_from = 1 / (double) $currency->attributes()->rate;
                }
                if ($currency->attributes()->currency == $to->getCode()){
                    $rate_to = 1 / (double) $currency->attributes()->rate;
                }
            }
            $rate = $rate_from / $rate_to;
        }
        return $rate;
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Can Berkol
 * 21.06.2013
 * **************************************
 * A __construct()
 * A getConversionRate()
 *
 */