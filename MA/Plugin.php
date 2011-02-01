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
 * MA_Plugin
 *
 * This file contains the class MA_Plugin
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */
if (!class_exists('MA_Plugin')) {

    /**
     * Base class for all WordPress Plugin Admin Pages
     *
     * @package com.ma.wordpress.ma_widget
     */
    abstract class MA_Plugin {

        /**
         * Constructor
         */
        function __construct() {
            add_action('admin_menu', array(&$this, 'registerAdminMenu'));
        }

        /**
         * Register Plugin Admin Menu
         */
        abstract public function registerAdminMenu();

        /**
         * returns HTML checkbox input
         *
         * @param String $id input id and name
         * @param String $value is checked
         * @return String
         */
        public function checkbox($id, $value = false) {
            return '<input class="checkbox" type="checkbox" value="true" id="' . $id . '" name="' . $id . '" ' . ( ($value == true) ? 'checked="checked"' : '' ) . ' />';
        }

        /**
         * returns HTML radio input
         *
         * @param String $id input id and name
         * @param String $value is checked
         * @return String
         */
        public function radio($id, $value, $selected = false) {
            return '<input class="radio" type="radio" value="' . $value . '" name="' . $id . '" ' . ( ($selected == true) ? 'checked="checked"' : '' ) . ' />';
        }

        /**
         * returns HTML text input
         *
         * @param String $id input id and name
         * @param String $value input value
         * @return String
         */
        public function textinput($id, $value) {
            return '<input class="text" type="text" id="' . $id . '" name="' . $id . '" size="30" value="' . $value . '"/>';
        }

	     /**
         * returns HTML password input
         *
         * @param String $id input id and name
         * @param String $value input value
         * @return String
         */
        public function passwordinput($id, $value) {
            return '<input class="text" type="password" id="' . $id . '" name="' . $id . '" size="30" value="' . $value . '"/>';
        }

        /**
         * returns HTML select input
         *
         * @param String $id input id and name
         * @param array $options Array of Options
         * @param String $selected value selected option value
         * @return String
         */
        public function select($id, $options, $selected_value) {
            $output_txt = '<select class="select" name="' . $id . '" id="' . $id . '">';
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

        /**
         * Create a potbox widget
         */
        function postbox($id, $title, $content) {
            ?>
<div id="<?php echo $id; ?>" class="postbox">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class="hndle"><span><?php echo $title; ?></span></h3>
    <div class="inside"><?php echo $content; ?></div>
</div>
            <?php
        }

    }

}