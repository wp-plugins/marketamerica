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
 * MA Widget
 *
 * This file contains the class MAWidget
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * MAWidget is a utility class to easily ouput MA Widgets
 *
 * @package com.ma.wordpress.ma_widget
 */
class MAWidget {

    
    /**
     * Output MA ProductWidget
     * @param array $args associate array of widget parameters
     */
    public static function Product( $args ) {
        $widget = new MAWidget_Product( $args );
        echo $widget->toHTML();
    }

    /**
     * Output MA Search Widget
     * @param array $args associate array of widget parameters
     */
    public static function Search( $args ) {
        $widget = new MAWidget_Search( $args );
        echo $widget->toHTML();
    }


	public static function MAProduct( $args ) {
        $widget = new MAWidget_Product( $args );
        echo $widget->toHTML_Preview();
    }
	public static function MAProductWidget( $args ) {
        $widget = new MAWidget_Product( $args );
        echo $widget->toHTML_Widget();
    }
	public static function MASearch( $args ) {
        $widget = new MAWidget_Search( $args );
        echo $widget->toHTML_Preview();
    }
	public static function MASearchWidget( $args ) {
        $widget = new MAWidget_Search( $args );
        echo $widget->toHTML_Widget();
    }
}