/*
 * copyright (c) 2010 Market America - Golam Osmani - marketamerica.com
 *
 * This file is part of WordPress Market America Widget Plugin.
 *
 * WordPress Market America Widget is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * WordPress Market America Widget is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WordPress Market America Widget.
 * If not, see <http://www.gnu.org/licenses/>.
*/
(function() {
    tinymce.create('tinymce.plugins.maproductlink', {
        init : function(ed, url) {
            var t = this;
            // Register commands
            ed.addCommand('maproductlink', function(ui, val) {                
				ed.windowManager.open({
					file : url + '/' + val + '.php',
					width : t._pluginWidth[val],
					height : t._pluginHeight[val],
					inline : 1,
					auto_focus: 0
				}, {
					plugin_url : url
				});
			
            });

            // Register buttons
            ed.addButton('maproductlink', {
                title : 'insert Market America Widgets',
                cmd : 'maproductlink',
                image : url + '/img/ma.gif'
            });

            /*
               * Load additional CSS
               */
            ed.onInit.add(function() {
                if (ed.settings.content_css !== false)
                {
                    dom = ed.windowManager.createInstance('tinymce.dom.DOMUtils', document);
                    dom.loadCSS(url + '/css/button.css');
                    ed.dom.loadCSS(url + '/css/button.css');
                }
            });

        },

        _pluginFunctions : {
            'ma-product': 'Product Widget',
            'ma-search': 'Search Widget'
        },

        _pluginHeight : {
            'ma-product': '400',
            'ma-search': '440'
        },

        _pluginWidth : {
            'ma-product': '750',
            'ma-search': '750'
        },

        getInfo : function() {
            return {
                longname : 'Market America',
                author : 'Golam Osmani',
                authorurl : 'http://www.marketamerica.com',
                infourl : 'http://www.marketamerica.com',
                version : '1.0'
            };
        },
                
        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            var t = this, menu = t._cache.menu, c, ed = tinyMCE.activeEditor, each = tinymce.each;

            if (n != 'maproductlink')
            {
                return null;
            }

            c = cm.createSplitButton(n, {
                cmd:    '',
                scope : t,
                title : 'insert Market America Widgets'
            });

            c.onRenderMenu.add(function(c, m) {
                m.add({
                    'class': 'mceMenuItemTitle',
                    title:   'Market America Widgets'
                }).setDisabled(1);

                each(t._pluginFunctions, function(value, key) {
                    var o = {
                        icon : 0
                    }, mi;

                    o.onclick = function() {
                        ed.execCommand('maproductlink', true, key);
                    };

                    o.title = value;
                    mi = m.add(o);
                    menu[key] = mi;
                });

                t._selectMenu(ed);
            });

            return c;
        },

        /*
         * Cache references
         */
        _cache: {
            menu: {}
        },

        /**
         * Select an item menu based on its classname
         *
         * @since 1.0
         * @version 1.0
         * @param {Object} ed TinyMCE Editor reference
         */
        _selectMenu: function(ed){
            var fe  =  ed.selection.getNode(), each = tinymce.each, menu = this._cache.menu;

            each(this.shortcodes, function(value, key){
                if (typeof menu[key] == 'undefined' || !menu[key])
                {
                    return;
                }

                menu[key].setSelected(ed.dom.hasClass(fe, key));
            });
        }
    });

    // Register plugin
    tinymce.PluginManager.add('maproductlink', tinymce.plugins.maproductlink);
})();