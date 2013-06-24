<?php
/**
 * @name        CurrencyValueInvalidException
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

class CurrencyValueInvalidException extends \Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct('Currency value must be numeric. You have provided a non-numeric value: '.$message, $code, $previous);
    }
}