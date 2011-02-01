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
require_once( '../../../../../wp-load.php');

$obj = new MAWidget_Search();
$args = $obj->getDefaultOptions();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Market America Search Widget</title>
        <script type="text/javascript" src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/ma-search.js"></script>
        <link rel="stylesheet" type="text/css" href="css/ma.css" />
    </head>
    <body>
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td valign="top" width="300px">
					<br />
					<br />
                    <h3>Select Widget Size:</h3>
                    <?php
						$width = $args['width'];
						$height = $args['height'];
						$combinedSize = $width . '_' . $height;
						echo MAWidget_Design::select('combinedSize', MAWidget_Design::getAvailableSizes(), $combinedSize );
						echo '<br /><br /><label>Default Search Term:</label><br/>';
						//echo MAWidget_Design::textinput('defaultSearchTerm', $args['searchText'] );
						echo MAWidget_Design::textinput('defaultSearchTerm', 'Isotonix' );
                    ?>                    
                    <script type="text/javascript">
                        function previewWidget( ) {
							var width = 315;
							var height = 210;
							var cSize = jQuery( "#combinedSize" ).val();
							if(cSize)
							{
								var dimensions = cSize.toString().split( "_" );
								if(dimensions && dimensions.length==2)
								{
						            width = dimensions[0];
						            height = dimensions[1]; 
								}
							}
							var queryStr = '?widget=Search' + 
			                '&width=' + width +
			                '&height=' + height +
							'&defaultSearchTerm=' + jQuery( "#defaultSearchTerm" ).val();
                            var url = '<?php echo $wpaa->getPluginPath('/servlet/preview.php'); ?>';
                            jQuery( '#widgetPreview' ).html( '<iframe id="previewFrame" scrolling="auto" frameborder="0" hspace="0" height="300" style="width:100%" src="' + url + queryStr + '" ></iframe>' );
                            return false;
                        }
                    </script>
                </td>
                <td valign="top">
                    <h3>Preview</h3>
                    <div id="widgetPreview">
                        --
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="mceActionPanel">
                        <input class="updateButton" onclick="previewWidget() " value="Preview" type="button" />
                        <input style="float: right;" type="button" id="insert" name="insert" value="{#insert}" onclick="MAWidgetDialog.insert();" />
                        <input style="float: right;" type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
                        <div style="clear:both"></div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>