<?php
/**
 * @name        TZSCurrency
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Murat Ünal
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Initializes Tanzanian Shillings.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers\Currencies;

use BiberLtd\Bundles\CurrencyBundle\Drivers;

class TZSCurrency extends Drivers\CurrencyDriver {
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
            'code'      => substr(__CLASS__,0, 3),
            'name'      => 'Tanzanian Shillings',
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