<?php
/**
 * @name        CurrencyDriver
 * @package		BiberLtd\CurrencyBundle
 *
 * @author		Can Berkol
 * @version     1.0.1
 * @date        21.06.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description The base driver class for each currency.
 *
 */
namespace BiberLtd\Bundles\CurrencyBundle\Drivers;

use BiberLtd\Bundles\CurrencyBundle\Exceptions;

class CurrencyDriver {
    protected $code;
    protected $name;
    protected $symbol;
    /** @var integer The allowed length of currency code */
    private   $codeLength = 3;

    /**
     * @name 			__construct()
     *  				Constructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param 			array 	$config 	Accepted keys: code, name, symbol, codeLength
     *
     */
    public function __construct(array $config = array()){

        foreach($config as $key => $value){
            $setFn = 'set'.ucfirst($key);
            if(method_exists($this, $setFn)){
                $this->$setFn($value);
            }
        }
    }
    /**
     * @name 			__destruct()
     *  				Destructor function.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     */
    function __destruct(){
        foreach($this as $key => $value){
            $this->$key = null;
        }
    }
    /**
     * @name 			getCode()
     *  				Gets the currency code.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->code
     *
     */
    public function getCode(){
        return $this->code;
    }
    /**
     * @name 			getName()
     *  				Gets the currency name.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->name
     *
     */
    public function getName(){
        return $this->name;
    }
    /**
     * @name 			getSymbol()
     *  				Gets the currency symbol.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          string          $this->symbol
     *
     */
    public function getSymbol(){
        return $this->symbol;
    }
    /**
     * @name 			setCode()
     *  				Sets the currency code.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @throws          CurrencyCodeException
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setCode($value){
        if(is_string($value) && strlen($value) == $this->codeLength){
            $this->code = $value;
        }
        else{
            throw new CurrencyCodeException('Currency code must be exactly '.$this->codeLength.' characters long.');
        }
        return $this;
    }
    /**
     * @name 			setName()
     *  				Sets the currency name.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setName($value){
        if(is_string($value)){
            $this->name = $value;
        }
        return $this;
    }
    /**
     * @name 			setSymbol()
     *  				Sets the currency symbol.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @param           string          $value
     *
     * @return          object          $this
     *
     */
    public function setSymbol($value){
        if(is_string($value)){
            $this->symbol = $value;
        }
        return $this;
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.1                      Can Berkol
 * 21.06.2013
 * **************************************
 * U _construct() Comment fixed.
 *
 * **************************************
 * v1.0.0                      Can Berkol
 * 21.06.2013
 * **************************************
 * A $code
 * A $codeLength
 * A $name
 * A $symbol
 * A __construct()
 * A __ destruct()
 * A getCode()
 * A getName()
 * A getSymbol()
 * A setCode()
 * A setName()
 * A setSymbol()
 */