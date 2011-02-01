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
 * Design
 *
 * This file contains the class MAWidget_Design
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * MAWidget_Design is abstract representation of MA Widgets with
 * Designs and Themes.
 *
 * @package com.ma.wordpress.ma_widget
 */
abstract class MAWidget_Design extends MAWidget_Abstract {


    /**
     * Constructor
     *
     * @param string $xml XML Representation of Object
     */
    public function __construct($args) {
        parent::__construct($args);
    }

    /**
     * Converts Properties from ShortCode to valid Format
     *
     * @param string $property
     * @return string
     */
    protected function convert( $property ) {
        return parent::convert( $property );
    }

    /**
     * is property valid for this object
     *
     * @param String $property
     * @return boolean
     */
    public function isValid($property) {        
		return parent::isValid( $property );
    }


    /**
     * get avaialbe size list
     *
     * @param string $design Design Id
     * @return array
     */
    public static function getAvailableSizes() {
		return array(
			"" => "Select...",
			"215_170" => "Small #1 (215 x 170)",
			"225_170" => "Small #2 (225 x 170)",
			"235_170" => "Small #3 (235 x 170)",
			"245_170" => "Small #4 (245 x 170)",
			"255_190" => "Medium #1 (255 x 190)",
			"265_190" => "Medium #2 (265 x 190)",
			"275_190" => "Medium #3 (275 x 190)",
			"285_190" => "Medium #4 (285 x 190)",
			"295_210" => "Large #1 (295 x 210)",
			"305_210" => "Large #2 (305 x 210)",
			"315_210" => "Large #3 (315 x 210)"
			);

    }
	
	public static function select($id, $options, $selected_value, $etc = "") {
        $output_txt = '<select class="widefat" ' . $etc . ' name="' . $id . '" id="' . $id . '">';
        foreach ($options as $option_value => $option_text) {
            $selected_txt = '';
            if ($selected_value == $option_value) {
                $selected_txt = ' selected="selected"';
            }
            if ($option_text == '') {
                $option_text = $option_value;
            }
            $output_txt .= '<option value="' . $option_value . '"' . $selected_txt . '>' . $option_text . '</option>';
        }
        $output_txt .= '</select>';
        return $output_txt;
    }
	public static function textinput($id, $value, $etc = "") {
        return '<input class="widefat" ' . $etc . ' type="text" id="' . $id . '" name="' . $id . '" size="30" value="' . $value . '"/>';
    }
}