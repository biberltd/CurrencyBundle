<?php
/**
 * @name        TMCService
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The service provider: The Money Converter.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers\ServiceProviders;

use BiberLtd\Bundles\CurrencyBundle\Drivers;

class TMCService extends Drivers\ServiceProviderDriver {
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
            'name'      => 'The Money Converter',
            'url'       => 'http://themoneyconverter.com/rss-feed/USD/rss.xml',
            'from'      => 'USD',
            'to'        => array(
                                'AED', 'ARS', 'AUD', 'AWG',        'BBD',
                                'BGN', 'BHD', 'BMD', 'BOB', 'BRL', 'BSD', 'CAD',
                                'CHF', 'CLP', 'CNY', 'COP', 'CZK', 'DKK', 'EGP',
                                'EUR', 'FJD', 'GBP',                      'HKD',
                                'HRK', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD',
                                'JOD', 'JPY', 'KES', 'KHR', 'KRW', 'KWD', 'LAK',
                                'LBP', 'LKR', 'LTL', 'LVL', 'MAD',        'MKD',
                                              'MXN', 'MUR', 'MYR', 'NAD', 'NGN',
                                'NOK', 'NPR', 'NZD', 'OMR', 'PEN', 'PHP', 'PKR',
                                'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'SAR',
                                       'SEK', 'SGD',        'THB', 'TND', 'TRY',
                                'TWD', 'TZS', 'UAH', 'UGX', 'UYU',        'VND',
                                'YER', 'ZAR', 'ZMK'
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
         * if from = USD & to = USD
         */
        if($from->getCode() == 'USD' && $to->getCode() == 'USD'){
            return 1;
        }
        /**
         * if to = USD
         */
        if($to->getCode() == 'USD'){
            foreach($xml->channel->item as $currency){
                if (substr($currency->title, 0, 3) == $from->getCode()){
                    $rate = 1 / (double) substr($currency->description, 24, 7);
                }
            }
        }
        /**
         * if from = USD
         */
        if($from->getCode() == 'USD'){
            foreach($xml->channel->item as $currency){
                if (substr($currency->title, 0, 3) == $to->getCode()){
                    $rate = (double) substr($currency->description, 24, 7);
                }
            }
        }
        /**
         * All other cases
         */
        if($from->getCode() != 'USD' && $to->getCode() != 'USD'){
            foreach($xml->channel->item as $currency){
                if (substr($currency->title, 0, 3) == $from->getCode()){
                    $rate_from = (double) substr($currency->description, 24, 7);
                }
                if (substr($currency->title, 0, 3) == $to->getCode()){
                    $rate_to = (double) substr($currency->description, 24, 7);
                }
            }
            $rate = $rate_to / $rate_from;
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
 */