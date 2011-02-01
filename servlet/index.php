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

// load WordPress
require_once( '../../../../wp-load.php');

// initialize APaPi library
$api = $wpaa->getAPI();

$result = null;

switch( $_REQUEST['Action'] ) {
    case "ValidateAccess":
		$api->setUserId( $_REQUEST['UID'] );
        $api->setPassword( $_REQUEST['PWD'] );
		$result = $api->validate();		
}

// json format result
$json = "";
if( is_null( $result ) ) {
    $json = '{ "IsValid" : "False", "Message" : "Invalid Request" }';
} else {
    $json = $result;
}

//get callback
if( isset($_REQUEST['callback']) ) {
    //return jsonp
    echo $_REQUEST['callback'] . '(' . $json . ')';
} else {
    // return json
    echo $json;
}