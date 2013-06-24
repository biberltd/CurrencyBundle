<?php
/**
 * @name        TCMBService
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The service provider: Central Bank of the Republic of Turkiye.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers\ServiceProviders;

use BiberLtd\Bundles\CurrencyBundle\Drivers;

class TCMBService extends Drivers\ServiceProviderDriver {
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
            'name'      => 'Central Bank of the Republic of Turkiye',
            'url'       => 'http://www.tcmb.gov.tr/kurlar/today.xml',
            'from'      => 'USD',
            'to'        => array(
                                'BGN', 'CAD', 'DKK', 'SEK', 'CHF', 'NOK', 'JPY',
                                'SAR', 'KWD', 'AUD', 'EUR', 'GBP', 'RUB', 'RON',
                                'PKR', 'IRR', 'CNY', 'TRY'
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
         * if from = TRY & to = TRY
         */
        if($from->getCode() == 'TRY' && $to->getCode() == 'TRY'){
            return 1;
        }
        /**
         * if to = TRY
         */
        if($to->getCode() == 'TRY'){
            foreach($xml->Currency as $currency){
                if ($currency->attributes()->Kod == $from->getCode()){
                    $rate = ((double) $currency->ForexBuying / $currency->Unit + (double) $currency->ForexSelling / $currency->Unit + (double) $currency->BanknoteBuying / $currency->Unit + (double) $currency->BanknoteSelling / $currency->Unit) / 4;
                }
            }
        }
        /**
         * if from = TRY
         */
        if($from->getCode() == 'TRY'){
            foreach($xml->Currency as $currency){
                if ($currency->attributes()->Kod == $to->getCode()){
                    $rate = 1 / (((double) $currency->ForexBuying / $currency->Unit + (double) $currency->ForexSelling / $currency->Unit + (double) $currency->BanknoteBuying / $currency->Unit + (double) $currency->BanknoteSelling / $currency->Unit) / 4);
                }
            }
        }
        /**
         * All other cases
         */
        if($from->getCode() != 'TRY' && $to->getCode() != 'TRY'){
            foreach($xml->Currency as $currency){
                if ($currency->attributes()->Kod == $from->getCode()){
                    $rate_from = 1 / (((double) $currency->ForexBuying / $currency->Unit + (double) $currency->ForexSelling / $currency->Unit + (double) $currency->BanknoteBuying / $currency->Unit + (double) $currency->BanknoteSelling / $currency->Unit) / 4);
                }
                if ($currency->attributes()->Kod == $to->getCode()){
                    $rate_to = 1 / (((double) $currency->ForexBuying / $currency->Unit + (double) $currency->ForexSelling / $currency->Unit + (double) $currency->BanknoteBuying / $currency->Unit + (double) $currency->BanknoteSelling / $currency->Unit) / 4);
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
 * 21.06.2103
 * **************************************
 * A __construct()
 * A getConversionRate()
 *
 */