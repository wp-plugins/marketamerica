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
tinyMCEPopup.requireLangPack();

var MAWidgetDialog = {

    init : function() {
        jQuery( "#defaultSearchTerm" ).val( tinyMCEPopup.editor.selection.getContent({format : 'text'}) );
        previewWidget();
    },

    insert : function() {
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
        var content = '[mawidgets-search width="' + width + '" height="' + height +'"';
        content += ' default_search_term="' + jQuery('#defaultSearchTerm').val() + '"';
        content += '/]';
        tinyMCEPopup.execCommand('mceInsertContent', false, content);
        tinyMCEPopup.close();
    }
};

tinyMCEPopup.onInit.add(MAWidgetDialog.init, MAWidgetDialog);