<?php
/*
  Plugin Name: Market America maWidgets
  Plugin URI: http://www.marketamerica.com/index.cfm?action=shopping.wpMAWidget
  Description: Market America Widget, Add Product and Search widgets.
  Author: Market America 
  Version: 1.0.0
  Requires at least: 3.0.0
  Author URI: http://www.marketamerica.com/index.cfm?action=shopping.wpMAWidget
  License: GPL v3
 */

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
 * Market America maWidgets Plugin
 *
 * This file configures and initializes the
 * Market America maWidgets Plugin
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

// load Admin Class
require_once plugin_dir_path(__FILE__) . 'WPMA.php';
spl_autoload_register(array('WPMA', 'autoload'));
$wpaa = new WPMA();