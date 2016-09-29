<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.horiondigital.com
 * @since      1.0.0
 *
 * @package    Acf_Front_End_Editor
 * @subpackage Acf_Front_End_Editor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_Front_End_Editor
 * @subpackage Acf_Front_End_Editor/admin
 * @author     Audrius Rackauskas <audrius@horiondigital.com>
 */
class Acf_Front_End_Editor_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acf_Front_End_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acf_Front_End_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/acf-front-end-editor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acf_Front_End_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acf_Front_End_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/acf-front-end-editor-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Add an options page under the Settings submenu
     *
     * @since  2.0.1
     */
    public function add_options_page() {
    
        $this->plugin_screen_hook_suffix = add_submenu_page(
            'options-general.php',
            __( 'ACF Front end editor', 'acf-front-end-editor' ),
            __( 'ACF Front end editor', 'acf-front-end-editor' ),
            'switch_themes',
            $this->plugin_name,
            array( $this, 'display_options_page' )
        );

    }

    /**
     * Render the options page for plugin
     *
     * @since  2.0.1
     */
    public function display_options_page() {
        include_once 'partials/acf-front-end-editor-admin-display.php';
    }

    /**
     * The options name to be used in this plugin
     *
     * @since   2.0.1
     * @access  private
     * @var     string      $option_name    Option name of this plugin
     */
    private $option_name = 'acf_front_end_editor';

    public function register_setting() {

        // Add a General section
        add_settings_section(
            $this->option_name . '_general',
            __( 'General', 'acf-front-end-editor' ),
            array( $this, $this->option_name . '_general_cb' ),
            $this->plugin_name
        );

        add_settings_field(
            $this->option_name . '_capabilities',
            __( 'Enable front end editing for', 'acf-front-end-editor' ),
            array( $this, $this->option_name . '_capabilities_cb' ),
            $this->plugin_name,
            $this->option_name . '_general',
            array( 'label_for' => $this->option_name . '_capabilities' )
        );
        
        register_setting( $this->plugin_name, $this->option_name . '_capabilities');
    }


    /**
     * Render the text for the vat section
     *
     * @since  2.0.1
     */
    public function acf_front_end_editor_general_cb() {
        echo '<p>' . __( 'Manage Some Basic Settings Here', 'acf-front-end-editor' ) . '</p>';
    }

    /**
     * Render capability list
     *
     * @since  2.0.1
     */
    
    private function clean_up_capabilities($capabilities) {
        $caps = array();
        foreach ($capabilities as $key => $cap):
            array_push($caps, $key);
        endforeach;

        return json_encode($caps);
    }


    /**
     * Render capability select for this plugin
     *
     * @since  2.0.1
     */
    public function acf_front_end_editor_capabilities_cb() {
        global $wp_roles; 
        $roles = $wp_roles->roles;
        $out = '';
        $out.= '<select name="' . $this->option_name . '_capabilities' . '" id="' . $this->option_name . '_capabilities' . '">';
        foreach ($roles as $key => $role):
            $out.='<option value=\''.$this->clean_up_capabilities($role['capabilities']).'\' '.(get_option( $this->option_name . '_capabilities' ) == $this->clean_up_capabilities($role['capabilities']) ? 'selected' : '').'>'.$role['name'].'</option>';
        endforeach;
        $out.='</select>';        

        echo $out;
    }



}
