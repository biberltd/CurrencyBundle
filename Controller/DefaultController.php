<?php
/**
 * DefaultController
 *
 * Default controller of CurrencyBundle
 *
 * @vendor      BiberLtd
 * @package		CurrencyBundle
 * @subpackage	Controller
 * @name	    DefaultController
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        20.06.2013
 *
 */

namespace BiberLtd\Bundles\CurrencyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $currencies = $this->container->getParameter('biberltd_currency_bundle');
    }
}
