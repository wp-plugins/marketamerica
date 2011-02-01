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
 * Market America Product Widget
 *
 * This file contains the class Widget_MA_Product
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * Widget_MA_Product is the implemenation of the Market America Product
 * Widget as a WordPress Widget
 *
 * @package com.ma.wordpress.ma_widget
 */
class Widget_MA_Product extends Widget_MA_Base {

    /**
     * constructor
     */
    function Widget_MA_Product() {
		
        /* Widget Settings */
        $widget_settings = array( 'classname'=>'Widget_MA_Product', 'description'=>__('Market America Product Widget','wpaa') );

        /* Widget Control Options */
        $widget_options = array ( 'width' => $this->width, 'height' => $this->height, 'id_base' => 'market-america-product-widget' );

        parent::WP_Widget('market-america-product-widget', __('Market America Product Widget','wpaa'), $widget_settings, $widget_options);        
    }

    /**
     * @see WP_Widget::widget
     */
    function widget($args, $instance) {
		global $wpaa;
        if( isset( $before_widget ) ) {
            echo $before_widget;
        }
        $title = apply_filters('widget_title', $instance['widgetTitle']);
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        MAWidget::MAProductWidget( $instance );
        if( isset( $after_widget ) ) {
            echo $after_widget;
        }
    }

    /**
     * @see WP_Widget::update
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['widgetTitle'] = $this->get_strip_tags($new_instance,'widgetTitle');
		$instance['combinedSize'] = $this->get_strip_tags($new_instance,'combinedSize');
		$instance['catID'] = $this->get_strip_tags($new_instance,'catID');
		$instance['subCatID'] = $this->get_strip_tags($new_instance,'subCatID');
        $instance['prodID'] = $this->get_strip_tags($new_instance,'prodID');
        //now update height and width
		$width = self::DEFAULT_WIDTH;
		$height = self::DEFAULT_HEIGHT;
		if( !empty( $instance['combinedSize'])) {
             $dimensions = split( "_", $instance['combinedSize'] );
             $width = $dimensions[0];
             $height = $dimensions[1];
        }
		$instance['width']=$width;
		$instance['height']=$height;
        return $instance;
    }

    /**
     * @see WP_Widget::form
     */
    function form($instance) {
        global $wpaa;
		$obj = new MAWidget_Product();
        $instance = wp_parse_args( (array) $instance, $obj->getDefaultOptions() );
        $width = 215;
		$height = 170;
        $widgetTitle = isset( $instance['widgetTitle']) ? esc_attr($instance['widgetTitle']) : "";
		$combinedSize = isset( $instance['combinedSize']) ? esc_attr($instance['combinedSize']) : $width . '_' . $height;
		$catID = isset( $instance['catID']) ? esc_attr($instance['catID']) : "";
		$subCatID = isset( $instance['subCatID']) ? esc_attr($instance['subCatID']) : "";
        $prodID = isset( $instance['prodID']) ? esc_attr($instance['prodID']) : self::DEFAULT_PROD_ID;
        ?>
<div class="wpaa_widget">
            <?php
			$js = "onchange=\"MA_ChangeCategory( '" . $this->get_field_id('catID') . "', '" . $this->get_field_id('subCatID') . "' )\"";
			$subCatjs = "onchange=\"MA_ChangeSubCategory( '" . $this->get_field_id('subCatID') . "', '" . $this->get_field_id('prodID') . "' )\"";
            echo $this->textinputWithLabel( __("Widget Title:",'wpaa'), 'widgetTitle', $widgetTitle );
			echo $this->selectWithLabel( __('Widget Size:','wpaa'), 'combinedSize', MAWidget_Design::getAvailableSizes(), $combinedSize );
			echo $this->selectWithLabel( __('Product Category:','wpaa'), 'catID', (array)null, $catID, $js);
			echo $this->selectWithLabel( __('Product Sub-Category:','wpaa'), 'subCatID', (array)null, $subCatID, $subCatjs);
			echo $this->selectWithLabel( __('Product:','wpaa'), 'prodID', (array)null, $prodID );
        $jsParams = "'" . $this->get_field_id('combinedSize') .
                "', '" . $this->get_field_id('prodID') . "'";
        echo '<input type="button" style="float:right" onclick="previewMAProduct( \'' . $wpaa->getPluginPath( '/servlet/preview.php') . '\', '  . $jsParams . ');" value="' . __("Preview Widget") . '" />';
		echo '<script type="text/javascript">';
		$pCountry = $instance['prdCountry'];
		$mCountry = $instance['merchCountry'];
		echo 'MA_prdCountry = "' . $pCountry . '";';
		echo 'MA_merchCountry = "' . $mCountry . '";';
		echo 'MA_maLoadCatCallback = function() {';
		echo 'MA_LoadCategory("' . $this->get_field_id('catID') . '", "' . $catID . '");';
		echo 'MA_LoadSubCategory("' . $this->get_field_id('subCatID') . '", "' . $catID . '", "' . $subCatID . '");';
		echo 'MA_LoadProduct("' . $this->get_field_id('prodID') . '", "' . $subCatID . '", "' . $prodID . '");';
		echo '};';
		echo 'MA_LoadCategoryList();';
		echo '</script>';
						?>
        <div style="clear:both"></div>
</div>
<script type="text/javascript">
    var wsPreviw = true;
    if( window.changeAmazonWidgetDesign ) {

    } else {
        function previewMAProduct( path, combinedSize, prodID )
        {
			var width = 315;
			var height = 210;
			var cSize = jQuery( "#" + combinedSize ).val();
			if(cSize)
			{
				var dimensions = cSize.toString().split( "_" );
				if(dimensions && dimensions.length==2)
				{
		            width = dimensions[0];
		            height = dimensions[1]; 
				}
			}
			
            var queryStr = '?widget=Product' + 
                '&width=' + width +
                '&height=' + height +
                '&prodID=' + jQuery( "#" + prodID).val()
				;
			
            jQuery.fancybox({
			'padding'		: 0,
			'autoScale'		: true,
			'transitionIn'          : 'none',
			'transitionOut'         : 'none',
			'title'			: "Product Preview",
			'href'			: encodeURI(path + queryStr),
			'type'			: 'iframe'
		});
            return false;
        }
    }
</script>
        <?php
    }

} // class Widget_MA_Product