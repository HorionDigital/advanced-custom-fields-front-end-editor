<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.horiondigital.com
 * @since      1.0.0
 *
 * @package    Acf_Front_End_Editor
 * @subpackage Acf_Front_End_Editor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_Front_End_Editor
 * @subpackage Acf_Front_End_Editor/public
 * @author     Audrius Rackauskas <audrius@horiondigital.com>
 */
class Acf_Front_End_Editor_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        if(is_user_logged_in()):
          wp_enqueue_style( $this->plugin_name.'-medium', plugin_dir_url( __FILE__ ) . 'css/medium-editor.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name.'-theme', plugin_dir_url( __FILE__ ) . 'css/themes/default.css', array(), $this->version, 'all' );
		  wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/acf-front-end-editor-public.css', array(), $this->version, 'all' );
        endif;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        if(is_user_logged_in()):
            wp_register_script( $this->plugin_name.'-medium', plugin_dir_url( __FILE__ ) . 'js/medium-editor.min.js', array( 'jquery' ), $this->version, false );
     		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/acf-front-end-editor-public.js', array( 'jquery' ), $this->version, false );
            wp_localize_script( $this->plugin_name, 'meta', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'page' => get_queried_object(),
            ));
            wp_enqueue_script( $this->plugin_name.'-medium');
            wp_enqueue_script( $this->plugin_name);
        endif;
	}

    /**
     * Renders text fields and text areas with additional html that allows to target these areas via javascript
     * @param  [String] $value
     * @param  [Int] $post_id
     * @param  [Object] $field 
     * @return [String] returns edited value with additional html
     * 
     * @since    1.0.0
     */
    public function acf_targeter( $value, $post_id, $field ) {
            if(strpos($value, 'http') === 0 || $value == '#' || $value == '' || filter_var($value, FILTER_VALIDATE_EMAIL) || is_admin()) {
                $value = $value;
            } else {
                $key=$field['key'];
                $label=$field['name'];
                $type = 'labas';
                $value = '<d contenteditable data-postid="'.$post_id.'" data-name="'.$label.'" data-key="'.$field['key'].'">'.$value.'</d>';
            }
        return $value;
    }

    /**
     * Renders wysiwyg fields with additional html that allows to target these areas via javascript
     * @param  [String] $value
     * @param  [Int] $post_id
     * @param  [Object] $field 
     * @return [String] returns edited value with additional html
     *
     * @since    1.0.0
     */
    public function acf_wysiwyg_targeter( $value, $post_id, $field ) {
        $key=$field['key'];
        $label=$field['name'];
        $value = '<div contenteditable class="editableHD" data-postid="'.$post_id.'" data-name="'.$label.'" data-key="'.$field['key'].'"><p></p>'.$value.'</div>';
        return $value;
    }

    /**
     * Formats field value to html if there is any
     * @param  [String] $value
     * @param  [Int] $post_id
     * @param  [Object] $field 
     * @return [String] returns edited value
     *
     * @since    1.0.0
     */
    public function my_acf_format_value( $value, $post_id, $field ) {
        $value = html_entity_decode($value);
        return $value;
    }


    /**
     * Registers filters required for ACF field rendering
     * @since 2.0.0
     */
    public function register_filters() {
        if(is_user_logged_in()):
            add_filter('acf/load_value/type=text',  array( $this, 'acf_targeter'), 10, 3);
            add_filter('acf/load_value/type=textarea', array( $this, 'acf_targeter'), 10, 3);
            add_filter('acf/load_value/type=wysiwyg', array( $this, 'acf_wysiwyg_targeter'), 10, 3);
            add_filter('acf/format_value/type=text', array( $this, 'my_acf_format_value'), 10, 3);
            add_filter('acf/format_value/type=textarea', array( $this, 'my_acf_format_value'), 10, 3);
            add_filter('acf/format_value/type=wysiwyg', array( $this, 'my_acf_format_value'), 10, 3);
        endif;
    }

    /**
     * Updates edited ACF fields in the database
     * 
     * @since 1.0.0
     */
    public function update_texts() {
        if ( isset($_REQUEST) ) {
            if(is_user_logged_in()):

            $siteID   = $_REQUEST['siteID'];
            $textArr  = $_REQUEST['textArr'];

            foreach ($textArr as $arr):
                $key  = $arr[0];
                $text = $arr[1];
                $name = $arr[2];
                $postid = $arr[3];
                $obj = get_field_object($name,$postid);
                $type = ($obj['key'] ? 'single' : 'repeater');
                $acf_post = get_post( $obj['parent'] );
                update_field($name, $text, $postid);
            endforeach;
            endif;
        }
        die();
    }
}
