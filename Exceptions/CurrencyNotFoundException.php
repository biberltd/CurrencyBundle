<?php
/**
 * @name        CurrencyNotFoundException
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

class CurrencyNotFoundException extends \Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct('Currency '.$message.' is not found. Make sure you have installed the required Driver in Divers\\Currencies folder.', $code, $previous);
    }
}