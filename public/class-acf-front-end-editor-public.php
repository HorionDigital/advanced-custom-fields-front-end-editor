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
     * Renders title fields with additional html that allows to target these aread via javascript
     * @param  [String] $title
     * @param  [Int] $id
     * @return [String]
     */
    function wp_title_targeter($title, $id = null) {
        $value = '<d contenteditable data-postid="'.$id.'" data-name="wp_hd_title" data-key="wp_core">'.$title.'</d>';
        return $value;
    }

    /**
     * Renders content fields with additional html that allows to target these aread via javascript
     * @param  [String] $title
     * @param  [Int] $id
     * @return [String]
     */
    function wp_content_targeter($content) {
        $value = '<div contenteditable class="editableHD" data-postid="'.get_the_ID().'" data-name="wp_hd_content" data-key="wp_core">'.$content.'</div>';
        return $value;
    }

    /**
     * Renders excerpt fields with additional html that allows to target these aread via javascript
     * @param  [String] $title
     * @param  [Int] $id
     * @return [String]
     */
    function wp_excerpt_targeter($excerpt) {
        $value = '<d contenteditable data-postid="'.get_the_ID().'" data-name="wp_hd_excerpt" data-key="wp_core">'.$excerpt.'</d>';
        return $value;
    }

    /**
     * The options name to be used in this plugin
     *
     * @since   2.0.1
     * @access  private
     * @var     string      $option_name    Option name of this plugin
     */
    private $option_name = 'acf_front_end_editor';

    /**
     * Check if user has capabilities
     *
     * @since  2.0.1
     */
    
    private function user_has_capabilities($capabilities) {
        if(!isset($capabilities) || empty($capabilities) || is_null($capabilities)) {
            return true;
        }
        $caps = json_decode($capabilities, true);
        $allow = true;
        if(!current_user_can( $caps[0] )) {
            $allow = false;
        }

        return $allow;
    }
    

    /**
     * Registers filters required for ACF field rendering
     * @since 2.0.0
     */
    public function register_filters() {
        if(is_user_logged_in() && !is_admin() && $this->user_has_capabilities(get_option( $this->option_name . '_capabilities'))):
            add_filter('acf/load_value/type=text',  array( $this, 'acf_targeter'), 10, 3);
            add_filter('acf/load_value/type=textarea', array( $this, 'acf_targeter'), 10, 3);
            add_filter('acf/load_value/type=wysiwyg', array( $this, 'acf_wysiwyg_targeter'), 10, 3);
            add_filter('acf/format_value/type=text', array( $this, 'my_acf_format_value'), 10, 3);
            add_filter('acf/format_value/type=textarea', array( $this, 'my_acf_format_value'), 10, 3);
            add_filter('acf/format_value/type=wysiwyg', array( $this, 'my_acf_format_value'), 10, 3);
            add_filter('the_title', array( $this, 'wp_title_targeter'), 10, 3);
            add_filter('the_content', array( $this, 'wp_content_targeter'), 10, 3);
            add_filter('get_the_excerpt', array( $this, 'wp_excerpt_targeter'), 10, 3);
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

            $textArr  = $_REQUEST['textArr'];

            foreach ($textArr as $arr):
                $key  = $arr[0];
                $text = $arr[1];
                $name = $arr[2];
                $postid = $arr[3];
                if($key == 'wp_core') {
                    $hd_acf_post = array(
                         'ID' => $postid,
                    );

                    switch ($name) {
                        case 'wp_hd_title':
                            $hd_acf_post['post_title'] = $text;
                            break;
                        case 'wp_hd_content':
                            $hd_acf_post['post_content'] = $text;
                            break;
                        case 'wp_hd_excerpt':
                            $hd_acf_post['post_excerpt'] = $text;
                            break;
                    }

                    wp_update_post( $hd_acf_post );
                } else {
                    update_field($name, $text, $postid);
                }
            endforeach;
            endif;
        }
        die();
    }
}
