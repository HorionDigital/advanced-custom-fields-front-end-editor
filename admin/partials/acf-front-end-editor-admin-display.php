<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.horiondigital.com
 * @since      2.0.1
 *
 * @package    Acf_Front_End_Editor
 * @subpackage Acf_Front_End_Editor/admin/partials
 */
?>

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <?php do_action( 'before_acf_front_end_editor_admin_settings' ); ?>
    <hr>
    <form action="options.php" method="post">
        <?php
            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
            submit_button();
        ?>
    </form>
</div>