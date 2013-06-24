<?php
/**
 * CurrencyConverter Class
 *
 * This class provides forex (foreign exchange) currency conversion mechanisms.
 *
 * @vendor      BiberLtd
 * @package		Core
 * @subpackage	Services
 * @name	    Encryption
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.2.0
 * @date        21.06.2013
 *
 */

namespace BiberLtd\Bundles\CurrencyBundle\Services;

use BiberLtd\Bundles\CurrencyBundle\Exceptions,
    BiberLtd\Bundles\CurrencyBundle\Drivers\Currencies,
    BiberLtd\Bundles\CurrencyBundle\Drivers\ServiceProviders;

class CurrencyConverter {
    /** @var $value value to be converted */
    public      $value;
    /** @var $value_formatted formatted value */
    protected   $value_formatted;
    /** @var $value_converted converted value */
    protected   $value_converted;
    /** @var $services collection of conversion services */
    protected   $services;
    /** @var $currencies collection of currencies */
    protected   $currencies;

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
        $this->register_currencies();
        $this->register_services();
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
    public function __destruct(){
        foreach($this as $key => $value){
            $this->$key = null;
        }
    }
    /**
     * @name            convert()
     *  				Converts the value from one currency to another using the provided service provider.
     * @author          Can Berkol
     * @since			1.0.0
     * @version         1.0.0
     *
     * @throws          \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyServiceProviderException
     * @throws          \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyCodeException
     *
     * @param           numeric         $value
     * @param           string          $from
     * @param           string          $to
     * @param           string          $service
     *
     * @return          object          $this
     */
    public function convert($value, $from = 'USD', $to = 'TRY', $service = 'TCMB'){
        $this->value = $value;
        /**
         * Check if selected conversion service is registered.
         */
        if(!isset($this->services[$service])){
            throw new \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyServiceProviderException($service);
        }
        $service_provider = $this->services[$service];
        if(!isset($this->currencies[$from])){
            throw new \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyCodeException($from);
        }
        $from = $this->currencies[$from];
        if(!isset($this->currencies[$to])){
            throw new \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyCodeException($to);
        }
        $to = $this->currencies[$to];
        /**
         * Connect to service & load XML
         */
        $conversion_xml = $this->load_xml($service_provider->getUrl());
        /** get conversion rate */
        $conversion_rate = $service_provider->getConversionRate($from, $to, $conversion_xml);

        /** Sets the value converted and the value formatted to the converted value. */
        $this->value_converted = $this->value_formatted = $conversion_rate * $value;
        return $this;
    }
    /**
     * @name 			format()
     *  				Formats number of the value_converted and saves into value_formatted.
     * @author          Can Berkol
     * @since		    1.0.0
     * @version         1.0.0
     *
     * @throws          \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyCodeException
     *
     * @param           array          $currency            Keys: from, to
     * @param           array          $formatting_options
     *
     *                                 code             => on, off (show currency code - default: on)
     *                                 code_position    => start, end (default: end)
     *                                 symbol           => on, off (show currency symbol - default: off)
     *                                 symbol_position  => start, end (default: start)
     *                                 round            => any integer (default: 2)
     *                                 direction        => up, down (round direction, default: up)
     *                                 decimal_symbol   => any string (default: .)
     *                                 thousands_symbol => any string (default: ,)
     *                                 show_original    => on, off (default: off, shows the original value in paranthesis)
     *
     * @return         object         $this
     */
    public final function format($currency, array $formatting_options = array()){
        if(!isset($this->currencies[$currency['to']])){
            throw new \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyCodeException($currency['to']);
        }
        if(!isset($this->currencies[$currency['from']])){
            throw new \BiberLtd\Bundles\CurrencyBundle\Exceptions\CurrencyCodeException($currency['from']);
        }
        $currency_to = $this->currencies[$currency['to']];
        $currency_from = $this->currencies[$currency['from']];
        /**
         * initialize options
         */
        $default_options = array(
            'code'              => 'on',
            'code_position'     => 'end',
            'symbol'            => 'off',
            'symbol_position'   => 'start',
            'precision'         => '2',
            'round'             => 'up',
            'decimal_symbol'    => '.',
            'thousands_symbol'  => ',',
            'show_original'     => 'off'
        );
        /**
         * Override defaults.
         */
        $options = array_merge($default_options, $formatting_options);
        /**
         * Read values
         */
        $value = $this->value_converted;
        /**
         * Rounding
         */
        $exploded_value = explode('.', $value);
        $precision = $options['precision'] + 1;
        if(!isset($exploded_value[1])){
            $exploded_value[1] = '00';
        }
        $exploded_value[1] = substr($exploded_value[1], 0, $precision);
        $value = (double) $exploded_value[0].'.'.$exploded_value[1];
        switch($options['round']){
            case 'down':
                $value = round($value, $options['precision'], PHP_ROUND_HALF_DOWN);
                break;
            case 'up':
            default:
                $value = round($value, $options['precision'], PHP_ROUND_HALF_UP);
                break;
        }
        /**
         *  Code & Symbol display
         */
        switch($options['code']){
            case 'off':
                break;
            case 'on':
            default:
                switch($options['code_position']){
                    case 'start':
                        $value = $currency_to->getCode().' '.$value;
                        break;
                    case 'end':
                    default:
                        $value .= ' '.$currency_to->getCode();
                        break;
                }
                break;
        }
        switch($options['symbol']){
            case 'off':
                break;
            case 'on':
            default:
                $symbol = $currency_to->getSymbol();
                switch($options['symbol_position']){
                    case 'start':
                        $value = $symbol.' '.$value;
                        break;
                    case 'end':
                    default:
                        $value .= ' '.$symbol;
                        break;
                }
                break;
        }
        /**
         * Decimal and thousands separator
         */
        $value = str_replace(array('.', ','), array($options['decimal_symbol'], $options['thousands_symbol']), $value);
        /**
         * Show original
         */
        if($options['show_original'] == 'on'){
            $value .= ' '.'('.$currency_from->getCode().' '.round($this->value, 2).')';
        }
        $this->value_formatted = $value;
        return $this;
    }
    /**
     * @name 			load_xml()
     *                  Loads the XML file into an object using SimpleXML and cURL libraries.
     * @author          Can Berkol
     * @since			1.0.0
     * @param           string          $url            URL of service
     * @param           integer         $timeout        Seconds to timeout the connection.
     * @param           string          $agent          Agent / Browser to behave like.
     * @return          string          $conversions    Content of XML
     *
     */
    private function load_xml($url, $timeout = 0, $agent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)'){
        /**
         * Open remote connection.
         */
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($connection,  CURLOPT_USERAGENT , $agent);
        curl_setopt($connection, CURLOPT_CONNECTTIMEOUT, $timeout);
        $xml_string = curl_exec($connection);
        curl_close($connection);
        /** Remote connection ends */
        $conversions = new \SimpleXMLElement($xml_string);

        return $conversions;
    }
    /**
     *  @name 			output()
     *  				Outputs the converted value.
     *  @author         Can Berkol
     * 	@since			1.0.0
     *
     *  @param          bool            $print          true|false, false is default. If set to true it does print the value otherwise returns it.
     *
     *  @return         string          $this->value_formatted
     *
     */
    public final function output($print = false){
        if(!$print){
            return $this->value_formatted;
        }
        echo $this->value_formatted;
    }
    /**
     * @name 			register_currencies()
     *  				Registers all available currencies.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          object      $this
     *
     */
    private function register_currencies(){
        $files = glob(__DIR__.'\\..\\Drivers\\Currencies\\*Currency.php');
        foreach($files as $file){
            $currency_class = str_replace('.php', '', $file);
            $currency_class = str_replace(__DIR__.'\\..\\Drivers\\Currencies\\', '', $currency_class);
            $currency_code = str_replace('Currency', '', $currency_class);
            $currency_class = '\\BiberLtd\\Bundles\\CurrencyBundle\\Drivers\\Currencies\\'.$currency_class;
            $currency = new $currency_class();

            $this->currencies[$currency_code] = $currency;
        }

        return $this;
    }
    /**
     * @name 			register_services()
     *  				Registers all available service provides.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     * @return          object      $this
     *
     */
    private function register_services(){
        $files = glob(__DIR__.'\\..\\Drivers\\ServiceProviders\\*Service.php');
        foreach($files as $file){
            $service_class = str_replace('.php', '', $file);
            $service_class = str_replace(__DIR__.'\\..\\Drivers\\ServiceProviders\\', '', $service_class);
            $service_code = str_replace('Service', '', $service_class);
            $service_class = '\\BiberLtd\\Bundles\\CurrencyBundle\\Drivers\\ServiceProviders\\'.$service_class;
            $service = new $service_class();

            $this->services[$service_code] = $service;
        }

        return $this;
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Can Berkol
 * 21.06.2013
 * **************************************
 * A $code
 * A $currencies
 * A $services
 * A $value
 * A $value_converted
 * A $value_formatted
 * A __construct()
 * A __destruct()
 * A convert()
 * A format()
 * A load_xml()
 * A output()
 * A register_currencies()
 * A register_services()
 *
 */