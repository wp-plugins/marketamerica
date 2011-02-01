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
 * Product
 *
 * This file contains the class MAWidget_Product
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * MAWidget_Product is plugin implementation of the
 * Market America Product Widget
 *
 * @package com.ma.wordpress.ma_widget
 */
class MAWidget_Product extends MAWidget_Design {

    /**
     * Constructor
     *
     * @param string $xml XML Representation of Object
     */
    public function __construct($args = null) {
        if( is_null( $args ) ){
            $args = self::getDefaultOptions();
        }
        parent::__construct($args);
        $this->_values["widget"] = "Product";
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
            case "prodID":
			case "pType":
			case "skuID":
                return true;
                break;
            default:
                return parent::isValid( $property );
                break;
        }
    }

    /**
     * return Associative Array of Default Product Widget Options
     *
     * @return array
     */
    public function getDefaultOptions() {
        return array(
            "prodID" => parent::$prodID,
            "height" => parent::$height,
            "width" => parent::$height,
            "PCID" => $this->PCID,
            "portalID" => $this->portalID,
            "refEmail" => $this->refEmail,
            "prdCountry" => $this->prdCountry,
            "merchCountry" => $this->merchCountry ,
			"catID" => parent::$catID,
            "subCatID" => parent::$subCatID	);
    }

    /**
     * return Associative Array of Default Product Widget Short Code Options
     *
     * @return array
     */
    public function getDefaultShortCodeOptions() {
        return array(
            "prodid" => parent::$prodID,
            "height" => parent::$height,
            "width" => parent::$height,
            "pcid" => $this->PCID,
            "portalid" => $this->portalID,
            "refemail" => $this->refEmail,
            "prdcountry" => $this->prdCountry,
            "merchcountry" => $this->merchCountry );
    }

	public function shortCodeHandler( $atts, $content = null ) {		
		$mawidget_site_root = MAWidget_Design::$urlBase;
		$obj = new MAWidget_Product();
	   extract( shortcode_atts( $obj->getDefaultShortCodeOptions() , $atts ) );
		$src = sprintf('%sIPD.aspx?height=%s&width=%s&prodID=%s&pType=%s&portalID=%s&PCID=%s&refEmail=%s&prdCountry=%s&merchCountry=%s&format=json',
			$mawidget_site_root, $height, $width, $prodid, 'MA', $portalid, $pcid, $refemail, $prdcountry, $merchcountry
		);		
	    return '<script type="text/javascript" src="'.$src.'"></script>';   
	}
	public function toHTML_Preview() {        
        return $this->toHTML_Local(true);
    }
	public function toHTML_Widget() {        
        return $this->toHTML_Local(false);
    }
	public function toHTML_Local($preview) {
        global $wpaa;
		$fileName = 'IPD';
		$output = 'prodID=' . $this->prodID . '&width=' . $this->width . '&height=' . $this->height . '&portalID=' . $this->portalID . '&PCID=' . $this->PCID . '&refEmail=' . $this->refEmail . '&prdCountry=' . $this->prdCountry . '&noClick=1&merchCountry=' . $this->merchCountry;
        $output = parent::$urlBase . 'IPD.aspx?' . $output;
		
        return '<script type="text/javascript" src="' . $output . '"></script>';
    }
}