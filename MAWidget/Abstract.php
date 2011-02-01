<?php

/*
 * copyright (c) 2010 Market America - Golam Osmani - marketamerica.com
 *
 * This file is part of Market America maWidgets Plugin.
 *
 * Market America maWidgets is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * Market America maWidgets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Market America maWidgets.
 * If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Abstract
 *
 * This file contains the class MAWidget_Abstract
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * MAWidget_Abstract is base class for all MA Widgets
 *
 * @package com.ma.wordpress.ma_widget
 */
abstract class MAWidget_Abstract {

    
    /**
     * @var array Object Values
     */
    protected $_values = array();
	protected $config_options_name = "wordpress-marketamerica-config";

	//public static $urlBase = "http://localhost/ma_ws/";
	//public static $urlBase = "http://208.43.210.246:8480/";
	public static $urlBase = "http://mawidget.marketamerica.com/";
	public static $width = '315';
	public static $height = '210';
	public static $searchText = 'Isotonix';
	public static $prodID = '2217';
	public static $pType = 'MA';
	public static $skuID = '13009';
	public static $catID = '400';
	public static $subCatID = '415';
	
	public $PCID = '';
	public $portalID = '';
	public $refEmail = '';
	public $prdCountry = '';
	public $merchCountry = '';
	
    /**
     * Constructor
     *
     * @param string $xml XML Representation of Object
     */
    public function __construct($args = null) {
        foreach ($args as $key => $value) {
            $this->set($key, $value);
        }
		
		$options = $this->loadOptions();
		$this->PCID = $options["PCID"];
		$this->portalID = $options["portalID"];
		$this->refEmail = $options["refEmail"];
		$this->prdCountry = $options["prdCountry"];
		$this->merchCountry = $options["merchCountry"];
		//print_r($options);
    }

	private function loadOptions() {
        
        // load Options
        $saved_options = get_option( $this->config_options_name );
        if( $saved_options !== false ) {
            foreach ($saved_options as $key => $value) {
                $this->options[$key] = $value;
            }
        }  
		return $saved_options;
    }
    /**
     * magic method to return non public properties
     *
     * @see     get
     * @param   mixed $property
     * @return  mixed
     */
    public function __get($property) {
        return $this->get($property);
    }

    /**
     * get specifed property
     *
     * @param mixed $property
     * @return mixed
     */
    public function get($property) {
        if (array_key_exists($property, $this->_values)) {
            return $this->_values[$property];
        } else {
            return null;
        }
    }

    /**
     * magic method to set non public properties
     *
     * @see    set
     * @param  mixed $property
     * @param  mixed $value
     * @return void
     */
    public function __set($property, $value) {
        $this->set($property, $value);
    }

    /**
     * set property to specified value
     *
     * @param mixed $property
     * @param mixed $value
     * @return void
     */
    public function set($property, $value) {
        $property = $this->convert($property);
        if ($this->isValid($property)) {
            $this->_values[$property] = $value;
        }
    }

    /**
     * Converts Properties from ShortCode to valid Format
     *
     * @param string $property
     * @return string 
     */
    protected function convert( $property ) {
		return $property;
    }

    /**
     * is property valid for this object
     * 
     * @param String $property 
     * @return boolean
     */
    public function isValid($property) {
        switch ($property) {
            case "height":
            case "width":
			case "size":
            case "PCID":
            case "portalID":
			case "refEmail":
            case "prdCountry":
			case "merchCountry":
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * return Associative Array of Default  Widget Options
     *
     * @return array
     */
    abstract public function getDefaultOptions();

    /**
     * return Associative Array of Default  Widget Short Code Options
     *
     * @return array
     */
    abstract public function getDefaultShortCodeOptions();
}