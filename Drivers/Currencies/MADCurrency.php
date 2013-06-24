<?php
/**
 * @name        MADCurrency
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Murat Ünal
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Initializes Moraccon Denari.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers\Currencies;

use BiberLtd\Bundles\CurrencyBundle\Drivers;

class MADCurrency extends Drivers\CurrencyDriver {
    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Murat Ünal
     *
     */
    public function __construct(){
        $currency_config = array(
            'code'      => substr(str_replace(__NAMESPACE__, '', __CLASS__), 1, 3),
            'name'      => 'Moraccon Denari',
            'symbol'    => '',
        );
        parent::__construct($currency_config);
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Murat Ünal
 * **************************************
 * A __construct()
 * 
 */