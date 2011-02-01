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
 * Market America Search Widget
 *
 * This file contains the class Widget_MA_Search
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * Widget_MA_Search is the implemenation of the Market America Search
 * Widget as a WordPress Widget
 *
 * @package com.ma.wordpress.ma_widget
 */
class Widget_MA_Search extends Widget_MA_Base {

    /**
     * constructor
     */
    function Widget_MA_Search() {

        /* Widget Settings */
        $widget_settings = array( 'classname'=>'Widget_MA_Search', 'description'=>__('Market America Search Widget','wpaa') );

        /* Widget Control Options */
        $widget_options = array ( 'width' => $this->width, 'height' => $this->height, 'id_base' => 'market-america-search-widget' );;

        parent::WP_Widget('market-america-search-widget', __('Market America Search Widget','wpaa'), $widget_settings, $widget_options);
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
        MAWidget::MASearch( $instance );
        if( isset( $after_widget ) ) {
            echo $after_widget;
        }
    }

    /**
     * @see WP_Widget::update
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['combinedSize'] = $this->get_strip_tags($new_instance,'combinedSize');
        $instance['widgetTitle'] = $this->get_strip_tags($new_instance,'widgetTitle');
        $instance['defaultSearchTerm'] = $this->get_strip_tags($new_instance,'defaultSearchTerm');
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
		$width = self::DEFAULT_WIDTH;
		$height = self::DEFAULT_HEIGHT;
		$widgetTitle = isset( $instance['widgetTitle']) ? esc_attr($instance['widgetTitle']) : "";
        $defaultSearchTerm = isset( $instance['defaultSearchTerm']) ? esc_attr($instance['defaultSearchTerm']) : self::DEFAULT_SEARCH_TEXT;
		$combinedSize = isset( $instance['combinedSize']) ? esc_attr($instance['combinedSize']) : $width . '_' . $height;
        ?>
<div class="wpaa_widget">
        <?php
        echo $this->textinputWithLabel( __("Widget Title:",'wpaa'), 'widgetTitle', $widgetTitle );
		echo $this->selectWithLabel( __('Widget Size:','wpaa'), 'combinedSize', MAWidget_Design::getAvailableSizes(), $combinedSize );
        echo $this->textinputWithLabel( __('Default Search Term:','wpaa'), 'defaultSearchTerm', $defaultSearchTerm );
        $jsParams = "'" . $this->get_field_id('combinedSize') .
                "', '" . $this->get_field_id('defaultSearchTerm') . "'";
        echo '<input type="button" style="float:right" onclick="javascript:previewMASearch( \'' . $wpaa->getPluginPath( '/servlet/preview.php') . '\', '  . $jsParams . ');" value="' . __("Preview Widget") . '" />';
        ?>
        <div style="clear:both"></div>
</div>
<script type="text/javascript">
    var wsPreview = true;
    if( window.changeAmazonDesign ) {

    } else {        
        function previewMASearch( path, combinedSize, defaultSearchTerm)
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
            var queryStr = '?widget=Search' + 
                '&width=' + width +
                '&height=' + height +
                '&defaultSearchTerm=' + jQuery( "#" + defaultSearchTerm ).val()
				;
            jQuery.fancybox({
			'padding'		: 0,
			'autoScale'		: true,
			'transitionIn'          : 'none',
			'transitionOut'         : 'none',
			'title'			: "Search Preview",
			'href'			: encodeURI(path + queryStr),
			'type'			: 'iframe'
		});
            return false;
        }
    }
    jQuery( "#<?php echo $this->get_field_id('design');?>" ).change();
    jQuery('#<?php echo $this->get_field_id('colorTheme');?>').val("<?php echo $colorTheme; ?>");
</script>
        <?php
    }

} // class Widget_MA_Search