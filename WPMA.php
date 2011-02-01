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

// load APaPi
require_once( plugin_dir_path(__FILE__) . 'API/MarketAmericaAPI.php' );
//spl_autoload_register(array('MarketAmericaAPI', 'autoload'));

// load Classes
require_once plugin_dir_path(__FILE__) . 'MA/Plugin.php';

/**
 * WPAA
 *
 * This file contains the class WPAA
 *
 * @author Golam Osmani <gmosmani@hotmail.com>
 * @package com.ma.wordpress.ma_widget
 */

/**
 * Market America maWidgets Plugin
 *
 * @package com.ma.wordpress.ma_widget
 */
class WPMA extends MA_Plugin {

    /**
     * @var string $path Market America maWidgets root directory
     */
    private static $_path;

    /**
     * multi user
     */
    const MODULE_MULTI_USER = 'multi user';

    /**
     * Plugin Modules
     * @var array
     */
    protected $modules = array();


    /**
     * Configuration Page Hook
     * @var String
     */
    protected $config_hook = "wordpress-marketamerica-config";

    /**
     * Configuration Option Name
     * @var String
     */
    protected $config_options_name = "wordpress-marketamerica-config";

    /**
     * Configuration Page User Level
     * @var String
     */
    protected $config_options_lvl = "edit_users";

    /**
     * Plugin Options
     * @var Array
     */
    protected $options = array(
				"UID" => '',
				"PWD" => '',
                "PCID" => null,
                "portalID" => null,
                "refEmail" => "",
                "prdCountry" => "USA",
				"merchCountry" => "USA",
                "MAProductWidget" => true,
                "MASearchWidget" => true,                
                "MultiUser" => false
        );

    /**
     * Admin Messages
     * @var string
     */
    protected $message = '';

    /**
     * Plugin Version
     * @var String
     */
    protected $version = '1.0.0';

    /**
     * Plugin Last Updated
     * @var String
     */
    protected $last_updated = '01-30-2011';

	/**
     * Plugin Dir name
     * @var String
     */
    public $plugin_folder_name = 'marketamerica';
	
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        add_action('admin_head', array(&$this, 'configPageHead'));
        add_action('admin_print_scripts', array(&$this, 'configPageScripts'));
        add_action('admin_print_styles', array(&$this, 'configPageStyles'));
        add_action('admin_notices', array(&$this, 'displayErrorMessage'));
        add_action('admin_init', array(&$this, 'saveSettings'));
        add_action('init', array(&$this, 'init'));
        add_action('widgets_init', array(&$this, 'init_widgets'));
        $this->loadOptions();
//        $this->loadModules();
		$this->registerShortcodes();
        // init I18n
        load_plugin_textdomain('wpaa', false, dirname( plugin_basename(__FILE__) ) . '/languages');
    }
/**
   * Register shortcode class & syntax
   */
  function registerShortcodes()
  {    
	$searchClass = 'Search';
	$productClass = 'Product';
	require_once( plugin_dir_path(__FILE__) . 'MAWidget/' . $productClass . '.php' );
	require_once( plugin_dir_path(__FILE__) . 'MAWidget/' . $searchClass . '.php' );
	add_shortcode('mawidgets-search', array('MAWidget_Search', 'shortCodeHandler'));
	add_shortcode('mawidgets-product', array('MAWidget_Product', 'shortCodeHandler'));
  }

    /**
     * Load Plugin Modules
     */
    public function loadModules() {
        // init MultiUser
        if( $this->options['MultiUser'] ) {
            // init multi user module
//            $this->modules[self::MODULE_MULTI_USER] = new WPAA_Module_MultiUser( $this->config_hook, $this->version, $this->last_updated );
        }
    }

    /**
     * get Plugin Module by name
     * @param Name $name
     * @return MA_Plugin
     */
/*
    public function getModule( $name ) {
        return $this->modules[$name];
    }
/*
    /**
     * WordPress init hook
     */
    public function init() {
        //init tinymce  Plugin
        if (current_user_can('edit_posts') &&
                current_user_can('edit_pages') &&
                ( get_user_option('rich_editing') == 'true' )) {
            add_filter("mce_external_plugins", array(&$this,"register_ma_tinymce_plugin"));
            add_filter('mce_buttons', array(&$this,'register_ma_button'));
        }
        // WordPress Bug #15600
        remove_filter('the_content', 'shortcode_unautop');
    }

    /**
     * enable Plugin Widgets
     */
    public function init_widgets() {
		if ($this->options['MAProductWidget']) {
            register_widget("Widget_MA_Product");
        }
		if ($this->options['MASearchWidget']) {
            register_widget("Widget_MA_Search");
        }
    }
	
    /**
     * Register MA Button
     * @param array $buttons
     * @return array
     */
    public function register_ma_button($buttons) {
        array_push($buttons, "seperator", "maproductlink");
        return $buttons;
    }

    /**
     * Load the TinyMCE plugin :: editor_plugin.js
     * @param array $plugin_array
     * @return array
     */
    public function register_ma_tinymce_plugin($plugin_array) {
        $plugin_array['maproductlink'] = get_bloginfo('wpurl') . '/' . PLUGINDIR . '/' . $this->plugin_folder_name  . '/tinymce/ma/editor_plugin_1.js';
        return $plugin_array;
    }

    /**
     * get configured instance of MarketAmericaAPI
     * @return MarketAmericaAPI
     */
    public function getAPI() {
        $api = new MarketAmericaAPI();
        $api->setUserId($this->options['UID']);
        $api->setPassword($this->options['PWD']);
        return $api;
    }


    /**
     * Save Plugin Settings
     */
    public function saveSettings() {
        if( isset( $_POST["wpaa_config_edit"]) && isset( $_POST["wpaa-config-meta-nonce"] ) ) {

            $wpaa_edit = $_POST["wpaa_config_edit"];
            $nonce = $_POST['wpaa-config-meta-nonce'];
            if ( !empty($wpaa_edit) && wp_verify_nonce($nonce, 'wpaa-config-meta-nonce')) {
				
				$old_UID = $this->options['UID'];
				$old_PWD = $this->options['PWD'];
                //update UID
                if (isset($_POST['UID'])) {
                    $this->options['UID'] = $_POST['UID'];
                }
				//update PWD
                if (isset($_POST['PWD'])) {
                    $this->options['PWD'] = $_POST['PWD'];
                }
				if($old_UID!=$_POST['UID'] || $old_PWD!=$_POST['PWD'])
				{
					$api = $this->getAPI();
					$api->setUserId( $_POST['UID'] );
			        $api->setPassword( $_POST['PWD'] );
					$result = $api->validate();	
					$retObj = $api->parseResult($result);
					if($retObj && $retObj->IsValid)
					{
						$this->options['PCID'] = $retObj->Credential->PCID;
						$this->options['portalID'] = $retObj->Credential->portalID;
						$this->options['refEmail'] = $retObj->Credential->refEmail;
						$this->options['prdCountry'] = $retObj->Credential->Country;
						$this->options['merchCountry'] = $retObj->Credential->MerchCountry;
					}
					else
					{
						$this->options['PCID'] = "";
						$this->options['portalID'] = "";
						$this->options['refEmail'] = "";
						$this->options['Country'] = "";
						$this->options['merchCountry'] = "";
					}
				}
                //update MAProductWidget
                if (isset($_POST['MAProductWidget'])) {
                    $this->options['MAProductWidget'] = true;
                } else {
                    $this->options['MAProductWidget'] = false;
                }
                //update MASearchWidget
                if (isset($_POST['MASearchWidget'])) {
                    $this->options['MASearchWidget'] = true;
                } else {
                    $this->options['MASearchWidget'] = false;
                }
                //update MultiUser
                if (isset($_POST['MultiUser'])) {
                    $this->options['MultiUser'] = true;
                } else {
                    $this->options['MultiUser'] = false;
                }
                update_option($this->config_options_name, $this->options);

                $this->message = __("<strong>Success:</strong> Settings successfully updated.", "wpaa");
            }
        }
    }

    /**
     * Output error messages
     */
    public function displayErrorMessage() {
        if (empty($this->options['UID']) || empty($this->options['PWD'])) {
            $admin_url = admin_url('admin.php?page=' . $this->config_hook);
            echo '<div class="updated fade">Market America maWidgets: ' . __('Plugin is not configured! Please correct in the', 'wpaa') . ' <a href="' . $admin_url . '" target="_self">' . __('settings page', 'wpaa') . '</a></div>';
        }
    }

    /**
     * @see MA_WP_Plugin::registerAdminMenu
     */
    public function registerAdminMenu() {
        add_menu_page("WP - Market America", "WP - Market America", $this->config_options_lvl, $this->config_hook, array(&$this, 'configPage'));
        add_submenu_page($this->config_hook, "Settings", "Settings", $this->config_options_lvl, $this->config_hook, array(&$this, 'configPage'));

    }

    /**
     * load Plugin Options
     */
    private function loadOptions() {
        
        // load Options
        $saved_options = get_option( $this->config_options_name );
        if( $saved_options !== false ) {
            foreach ($saved_options as $key => $value) {
                $this->options[$key] = $value;
            }
        }        
    }
	
	public function getOptions()
	{
		return $this->options;
	}

    /**
     * Get the path to a file within the plugin
     *
     * @param string
     * @return string
     */
    public function getPluginPath( $url) {
        return plugins_url($url, __FILE__);
    }

    /**
     * Output Admin Page header scripts
     */
    public function configPageHead() {
        if (isset($_GET['page']) && $_GET['page'] == $this->config_hook) {
            wp_enqueue_script('jquery');
        }
    }

    /**
     * Output Config Page Styles
     */
    function configPageStyles() {
        if (isset($_GET['page']) && $_GET['page'] == $this->config_hook) {
            wp_enqueue_style('dashboard');
            wp_enqueue_style('thickbox');
            wp_enqueue_style('global');
            wp_enqueue_style('wp-admin');
            wp_enqueue_style('wpaa-admin-css', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/css/admin.css');
        } else if( is_admin() ) {
            wp_enqueue_style('thickbox');
            wp_enqueue_style('wpaa-fancybox-css', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/js/fancybox/jquery.fancybox-1.3.4.css' );
            wp_enqueue_style('wpaa-widget-css', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/css/widget.css');
        }
    }

    /**
     * Output Page Scripts
     */
    function configPageScripts() {
        if (isset($_GET['page']) && $_GET['page'] == $this->config_hook) {
            wp_enqueue_script('postbox');
            wp_enqueue_script('dashboard');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('media-upload');
        } else if( is_admin() ){
            wp_enqueue_script('thickbox');
            wp_enqueue_script('wpaa-fancybox-js', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/js/fancybox/jquery.fancybox-1.3.4.js' );
			wp_enqueue_script('wpaa-product-js', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/js/ma/script.js' );
        }
    }

    /**
     * output Main Settings Page
     */
    public function configPage() {
        ?>
<div class="wrap">
    <h2>Market America maWidgts: <?php _e('Settings', 'wpaa'); ?></h2>
    <div class="postbox-container" style="width:500px;" >
        <div class="metabox-holder">
            <div class="meta-box-sortables">
                <form action="<?php echo admin_url('admin.php?page=' . $this->config_hook); ?>" method="post" id="wpaa-conf">
                    <input value="wpaa_config_edit" type="hidden" name="wpaa_config_edit" />
                    <input type="hidden" name="wpaa-config-meta-nonce" value="<?php echo wp_create_nonce('wpaa-config-meta-nonce') ?>" />
                            <?php                            
                            $content = '<div class="admin_config_box">';
                            $content .= '<table border="0" class="admin_table">';
							$content .= '<tr><td><strong>' . __('User Id:','wpaa') . '</strong></td><td>' . $this->textinput("UID", $this->options["UID"]) . '</td></tr>';
							$content .= '<tr><td><strong>' . __('Password:','wpaa') . '</strong></td><td>' . $this->passwordinput("PWD", $this->options["PWD"]) . '</td></tr>';
                            $content .= '<tr><td><input class="button-primary" type="button" name="validate" value="' . __('Validate Credentials','wpaa') . ' &raquo;" onclick="validateAccess();" /></td><td><div id="progressImg" style="display:none"><img src="' . WP_CONTENT_URL . '/plugins/' . $this->plugin_folder_name . '/imgs/spinner.gif" alt="" /></div><div id="accessMessage" style="display:none"></div></td></tr>';
                            $content .= '</table>';
                            $content .= '<br/><div class="alignright"><input class="button-primary" type="submit" name="submit" value="' . __('Update Settings','wpaa') . ' &raquo;" /></div>';
							$content .= '<div class="clear"></div>';
							$content .= '</div>';
                            $this->postbox("ma_web_service_settings", __("Market America Credential Settings",'wpaa'), $content);

                            $content = '<div class="admin_config_box">';
                            $content .= '<table border="0" class="admin_table">';
                            $content .= '<tr><td><strong>' . __('Market America Product:','wpaa') . '</strong></td><td>' . $this->checkbox("MAProductWidget", $this->options["MAProductWidget"]) . '</td></tr>';
                            $content .= '<tr><td><strong>' . __('Market America Search:','wpaa') . '</strong></td><td>' . $this->checkbox("MASearchWidget", $this->options["MASearchWidget"]) . '</td></tr>';
                            $content .= '</table>';
                            $content .= '<br/><div class="alignright"><input class="button-primary" type="submit" name="submit" value="' . __('Update Settings','wpaa') . ' &raquo;" /></div>';
                            $content .= '<div class="clear"></div>';
                            $content .= "<p>" . __("Don't need a provided widget? Then feel free to disable it here so your Widget menu is a little less cluttered.",'wpaa') . "</p>";
                            $content .= '</div>';
                            $this->postbox("ma_widget_settings", __("Market America Widgets Settings",'wpaa'), $content);
                            ?>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function validateAccess() {
            var uid = encodeURIComponent(jQuery("#UID").val());
            var pwd = encodeURIComponent(jQuery("#PWD").val());
			jQuery( "#accessMessage" ).hide();
			jQuery( "#progressImg" ).show();
			jQuery.ajax({
                url: '<?php echo plugins_url('/servlet/index.php', __FILE__); ?>',
                type: 'POST',
                dataType: 'json',
                data: 'Action=ValidateAccess&UID=' + uid + '&PWD=' + pwd,
                success: function(data) {
                    if( data.IsValid) {
                        jQuery( "#accessMessage" ).html( '<span style="color:green;" >Valid</span>' );
                    } else {
                        jQuery( "#accessMessage" ).html( '<span style="color:red;" >InValid</span>' );
                    }
					jQuery( "#progressImg" ).hide();
                    jQuery( "#accessMessage" ).show();
                }
            });
        }
        validateAccess();
    </script>
    <div class="postbox-container" style="width:300px;"  >
        <div class="metabox-holder">
            <div class="meta-box-sortables">
                        <?php
                        $content = '<div class="admin_config_box">';
                        $content .= '<strong>' . __('Author:','wpaa') . '</strong> <a href="http://www.marketamerica.com/" target="_blank">Market America</a><br/><br/>';
                        $content .= '<strong>' . __('Project Website:','wpaa') . '</strong> <a href="http://www.marketamerica.com/index.cfm?action=shopping.wpMAWidget" target="_blank">www.marketamerica.com</a><br/><br/>';
                        $content .= '<strong>' . __('Version:','wpaa') . '</strong> ' . $this->version . '<br/><br/>';
                        $content .= '<strong>' . __('Last Updated:','wpaa') . '</strong> ' . $this->last_updated;
                        $content .= '</div>';
                        $this->postbox("about", __("About this Plugin",'wpaa'), $content);
                        
                        $content = '<div class="admin_config_box">';
                        $content .= '<a href="http://wordpress.org/extend/plugins/' . $this->plugin_folder_name . '/" target="_blank">' . __('Rate this plugin','wpaa') . '</a> ' . __('on WordPress') . '<br/><br/>';
                        $content .= '<a href="http://wordpress.org/extend/plugins/' . $this->plugin_folder_name . '/" target="_blank">' . __('Notify','wpaa') . '</a> ' .  __('WordPress users this plugin works with your WordPress version','wpaa') . '<br/><br/>';
                        $content .= '<strong>Share this plugin with others</strong><br/><br/>';
                        //facebook
                        $content .= '<a href="http://www.facebook.com/sharer.php?u=http%3a%2f%2fwww.marketamerica.com%2findex.cfm%3faction%3dshopping.wpMAWidget&t=Awesome%20WordPress%20Plugin:%20%20Market%20America%20maWidgets" target="_blank"><img src="' . WP_CONTENT_URL . '/plugins/' . $this->plugin_folder_name . '/imgs/fb.png" alt="Facebook" /></a>';
                        //digg 
                        $content .= '&nbsp;&nbsp;<a href="http://digg.com/submit?url=http%3a%2f%2fwww.marketamerica.com%2findex.cfm%3faction%3dshopping.wpMAWidget" target="_blank"><img src="' . WP_CONTENT_URL . '/plugins/' . $this->plugin_folder_name . '/imgs/digg.gif" alt="Digg" /></a>';
                        //stubmleupon 
                        $content .= '&nbsp;&nbsp;<a href="http://www.stumbleupon.com/badge/?url=http%3a%2f%2fwww.marketamerica.com%2findex.cfm%3faction%3dshopping.wpMAWidget" target="_blank"><img src="' . WP_CONTENT_URL . '/plugins/' . $this->plugin_folder_name . '/imgs/stumbleupon.gif" alt="Stumble Upon" /></a>';
                        //delicious 
                        $content .= '&nbsp;&nbsp;<a href="http://delicious.com/save?v=5&noui&jump=close&url=http%3a%2f%2fwww.marketamerica.com%2findex.cfm%3faction%3dshopping.wpMAWidget" target="_blank"><img src="' . WP_CONTENT_URL . '/plugins/' . $this->plugin_folder_name . '/imgs/deli.gif" alt="Delicous" /></a>';
                        //twitter 
                        $content .= '&nbsp;&nbsp;<a href="http://twitter.com/home/?status=Market+America+maWidgets+%2C+the+all-in-one+Market+America+WordPress+Plugin+http%3a%2f%2fwww.marketamerica.com%2findex.cfm%3faction%3dshopping.wpMAWidget" target="_blank"><img src="' . WP_CONTENT_URL . '/plugins/' . $this->plugin_folder_name . '/imgs/twitter.gif" alt="Twitter" /></a>';
                        $content .= '</div>';
                        $this->postbox("feedback", __("User Feedback",'wpaa'), $content);

                        ?>
            </div>
        </div>
    </div>
</div>
        <?php
    }

    /**
     * simple autoload function
     * returns true if the class was loaded, otherwise false
     *
     * <code>
     * // register the class auto loader
     * spl_autoload_register( array('WPAA', 'autoload') );
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
     * Get the root path to Market America maWidgets Plugin
     *
     * @return string
     */
    public static function getPath() {
        if (!self::$_path) {
            self::$_path = dirname(__FILE__);
        }
        return self::$_path;
    }

}