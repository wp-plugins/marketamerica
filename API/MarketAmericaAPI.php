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
 * MarketAmericaAPI
 *
 * This file contains the class MarketAmericaAPI
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * main class of the MarketAmericaAPI
 *
 * @package com.ma.wordpress.ma_widget
 */
final class MarketAmericaAPI {

    /**
     * @var string $path MAProduct root directory
     */
    private static $_path;

    /**
     * user id
     */
    private $_user_id;

    /**
     * password
     */
    private $_password;

	/**
     * baseUrl
     */
    private $_urlBase = '';
	
    /**
     * Constructor
     */
    public function __construct( ) {
		$this->_urlBase = MAWidget_Design::$urlBase;
    }

    /**
     * set user id
     * @param string $key user id
     * @return void
     */
    public function setUserId( $key ) {
        $this->_user_id = $key;
    }

    /**
     * set password
     *
    * @param string $key password
     * @return void
     */
    public function setPassword( $key ) {
        $this->_password = $key;
    }

	private function getUrl( ) {
		$url = $this->_urlBase . 'ValidateCredential.aspx?uid=' . urlencode($this->_user_id) . '&pwd=' . urlencode($this->_password);
		return $url;
	}
	
	public function validate()
	{
		if( empty($this->_user_id) || empty($this->_password) ) {
            //throw new Exception( "user id/password is not configured" );
			return "";
        }
		$ch = $this->generateCURL( $this->getUrl( ) );
        $data = curl_exec( $ch );
        $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		return $data;
	}
	
	public function parseResult($result)
	{
		return json_decode($result);
	}
	 /**
     * generate cURL get request
     * @param $url
     * @return object cURL Handler
     */
    protected function generateCURL( $url ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: MAAPI - PHP Wrapper Library for Market America Product API', 'Accept: application/xml', 'Content-Type: application/xml' ) );
        return $ch;
    }
	

/*
 * Utility Methods
 */

    /**
     * simple autoload function
     * returns true if the class was loaded, otherwise false
     *
     * <code>
     * // register the class auto loader
     * spl_autoload_register( array('MarketAmericaAPI', 'autoload') );
     * </code>
     *
     * @param string $classname Name of Class to be loaded
     * @return boolean
     */
    public static function autoload($className) {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return false;
        }
        $class = self::getPath() . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($class)) {
            require $class;
            return true;
        }
        return false;
    }

    /**
     * Get the root path to Market America API
     *
     * @return string
     */
    public static function getPath() {
        if ( ! self::$_path) {
            self::$_path = dirname(__FILE__);
        }
        return self::$_path;
    }

}