<?php
/**
 * @name        BOIService
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The service provider: Bank of Israel.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers\ServiceProviders;

use BiberLtd\Bundles\CurrencyBundle\Drivers;

class BOIService extends Drivers\ServiceProviderDriver {
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
            'name'      => 'Bank of Israel',
            'url'       => 'http://www.bankisrael.gov.il/currency.xml',
            'from'      => 'ILS',
            'to'        => array(
                                'USD', 'JPY', 'GBP', 'EUR', 'AUD', 'CAD', 'DKK',
                                'NOK', 'ZAR', 'SEK', 'CHF', 'JOD', 'LBP', 'EGP'
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
     * @param           CurrencyBundle\Drivers\Currencies       $from
     * @param           CurrencyBundle\Drivers\Currencies       $to
     * @param           string                                  $xml
     *
     * @return          numeric                                 $rate
     */
    public function getConversionRate($from, $to, $xml){
        $this->isConvertible($to, $from);
        /**
         * if from = ILS & to = ILS
         */
        if($from->getCode() == 'ILS' && $to->getCode() == 'ILS'){
            return 1;
        }
        /**
         * if to = ILS
         */
        if($to->getCode() == 'ILS'){
            foreach($xml->CURRENCY as $currency){
                if ($currency->CURRENCYCODE == $from){
                    $rate = (double) $currency->RATE;
                }
            }
        }
        /**
         * if from = ILS
         */
        if($from->getCode() == 'ILS'){
            foreach($xml->CURRENCY as $currency){
                if ($currency->CURRENCYCODE == $to){
                    $rate = 1 / (double) $currency->RATE;
                }
            }
        }
        /**
         * All other cases
         */
        if($from != 'ILS' && $to != 'ILS'){
            foreach($xml->CURRENCY as $currency){
                if ($currency->CURRENCYCODE == $from){
                    $rate_from = (double) $currency->RATE;
                }
                if ($currency->CURRENCYCODE == $to){
                    $rate_to = (double) $currency->RATE;
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