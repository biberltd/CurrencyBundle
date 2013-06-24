<?php
/**
 * @name        CurrencyServiceFromException
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.0
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Custom Exception.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Exceptions;

class CurrencyServiceFromException extends \Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct('The driver for the input currency "'.$message.'" does not exist in CurrencyBundle\\Drivers\\Currencies', $code, $previous);
    }
}