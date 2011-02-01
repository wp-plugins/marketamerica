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
?>
<html>
    <head></head>
    <body>
        <?php
        $width = '600';
        if (!empty($_GET['width'])) {
            $width = $_GET['width'];
        }
        $height = '600';
        if (!empty($_GET['height'])) {
            $height = $_GET['height'];
        }
        if( !empty( $_GET['size'])) {
             $dimensions = split( "x", $_GET['size'] );
             $width = $dimensions[0];
             $height = $dimensions[1];
        }
        ?>
        <div id="preview_section_demo" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
            <?php

            $widget = $_GET['widget'];
            unset($_GET['widget']);
            foreach ($_GET as $key => $value) {
                $_GET[$key] = urldecode($value);
                if (empty($value) || $value == 'undefined') {
                    unset($_GET[$key]);
                }
            }

            switch ($widget) {
                case "Product":
                    MAWidget::MAProduct($_GET);
                    break;
                case "Search":
                    MAWidget::MASearch($_GET);
                    break;
            }
            ?>
        </div>
    </body>
</html>