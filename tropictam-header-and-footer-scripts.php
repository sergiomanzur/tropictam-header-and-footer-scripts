<?php
/**
 * Plugin Name: TropicTam Header and Footer Scripts
 * Plugin URI: https://github.com/sergiomanzur/tropictam-header-and-footer-scripts
 * Description: This simple plugin lets you add any kind of scripts to the header and footer of your theme.
 * Version: 1.0
 * Author: Sergio Manzur Tapia
 * Author URI: https://www.linkedin.com/in/sergiomanzur/
 **/

class TropicTamScripts {

	/**
	 * Constructor to hook our initialization functions.
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'settings_page'));
		add_action('admin_init', array($this, 'register_settings'));
		add_action('wp_head', array($this, 'add_header_script'));
		add_action('wp_footer', array($this, 'add_footer_script'));
	}

	/**
	 * Adds the settings page to the WordPress admin menu.
	 */
	public function settings_page() {
		$hook_suffix = add_submenu_page(
			'tools.php', // Parent slug
			'TropicTam Header and Footer', // Page title
			'TropicTam Header and Footer', // Menu title
			'manage_options', // Capability
			'tropictam-settings', // Menu slug
			array($this, 'settings_page_callback') // Callback function
		);

		// Enqueue the styles and scripts specifically for this page using the hook suffix
		add_action("admin_print_scripts-{$hook_suffix}", array($this, 'load_bootstrap_wp_admin_style'));
	}

	/**
	 * Registers the settings for our scripts.
	 */
	public function register_settings() {
		register_setting('tropictam-settings-group', 'tropictam_header_code');
		register_setting('tropictam-settings-group', 'tropictam_footer_code');
	}

	/**
	 * Displays the settings page in the WordPress admin.
	 */
	public function settings_page_callback() {
		?>
        <div class="wrap">
            <h1>TropicTam Header and Footer Scripts</h1>
            <form method="post" action="options.php">
				<?php settings_fields('tropictam-settings-group'); ?>
				<?php do_settings_sections('tropictam-settings-group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Header Code</th>
                        <td>
                            <textarea name="tropictam_header_code" style="width:50%;height:200px;"><?php echo esc_attr(get_option('tropictam_header_code')); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Footer Code</th>
                        <td>
                            <textarea name="tropictam_footer_code" style="width:50%;height:200px;"><?php echo esc_attr(get_option('tropictam_footer_code')); ?></textarea>
                        </td>
                    </tr>
                </table>
				<?php submit_button(); ?>
            </form>
        </div>
		<?php
	}

	/**
	 * Enqueues Bootstrap styles and scripts for the WordPress admin.
	 */
	public function load_bootstrap_wp_admin_style() {
		wp_register_style('tropictam_bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.6.1/css/bootstrap.min.css');
		wp_enqueue_style('tropictam_bootstrap_css');

		wp_register_script('tropictam_bootstrap_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.6.1/js/bootstrap.min.js', array('jquery'), '', true);
		wp_enqueue_script('tropictam_bootstrap_js');
	}

	/**
	 * Adds the header script to the frontend.
	 */
	public function add_header_script() {
		$script = get_option('tropictam_header_code');
		if (!empty($script)) {
			echo $script;
		}
	}

	/**
	 * Adds the footer script to the frontend.
	 */
	public function add_footer_script() {
		$script = get_option('tropictam_footer_code');
		if (!empty($script)) {
			echo $script;
		}
	}
}

// Initialize the plugin class.
new TropicTamScripts();
?>
