<?php

/**
 * Plugin Name:       Reward Dice by PreciseFunnels
 * Plugin URI:        https://wordpress.org/plugins/reward-dice-by-precisefunnels/
 * Description:       Reward Dice by PreciseFunnels is an exit intent popup with an email optin form that uses gamification for efficient lead generation in WooCommerce.
 * Version:           1.0.3
 * Author:            PreciseFunnels
 * Contributors:      roksprogar
 * Author URI:        https://www.precisefunnels.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       reward-dice-precisefunnels
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define the plugin path.
define('REWARD_DICE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('REWARD_DICE_SCRIPT_LOADER_URL', 'https://www.precisefunnels.com/cors/sl/rd/');

include(plugin_dir_path(__FILE__) . 'includes/reward-dice-menus.php');
include(plugin_dir_path(__FILE__) . 'includes/reward-dice-settings-fields.php');

// Add the plugin settings link to the list of plugins.
function reward_dice_add_settings_link( $links )
{
    $settings_link = '<a href="admin.php?page=reward-dice">' . __('Settings', 'reward-dice') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'reward_dice_add_settings_link' );

function reward_dice_check_integration_status()
{
    // Grab the options.
    $reward_dice_settings = get_option('reward_dice_settings');

    // Are they even there?
    if (!$reward_dice_settings) {
        return 'inactive';
    }

    // Does the access key even exist?
    if (!array_key_exists('access_key', $reward_dice_settings)) {
        return 'inactive';
    }

    // It could also be saved as blank (maybe prevent this in validation)?
    if (!$reward_dice_settings['access_key']) {
        $reward_dice_settings['enabled'] = false;
        update_option('reward_dice_settings', $reward_dice_settings);
        return 'inactive';
    }

    // Try to http get the script loader.
    $response = wp_remote_get( REWARD_DICE_SCRIPT_LOADER_URL . $reward_dice_settings['access_key'], [
        'headers' => [
            'Referer' => site_url()
        ]
    ]);

    if (wp_remote_retrieve_response_code($response) === 200) {
        $reward_dice_settings['enabled'] = true;
        update_option('reward_dice_settings', $reward_dice_settings);
        return 'active';
    } else {
        $reward_dice_settings['enabled'] = false;
        update_option('reward_dice_settings', $reward_dice_settings);
        return 'inactive';
    }
}

add_action( 'wp_footer', 'reward_dice_inject_script' );

function reward_dice_js_enqueue() {
    // Load and check the settings.
    $reward_dice_settings = get_option('reward_dice_settings');
    if (!is_user_logged_in() && $reward_dice_settings && isset($reward_dice_settings['enabled']) && isset($reward_dice_settings['access_key']) && $reward_dice_settings['enabled'] === true) {
        // Prepare the script loader url.
        $data_to_pass = [];
        $data_to_pass['sl_url'] = esc_url_raw(REWARD_DICE_SCRIPT_LOADER_URL . $reward_dice_settings["access_key"]);

        // Enqueue the script and pass the script loader url to the js.
        wp_enqueue_script( 'reward-dice-precisefunnels', plugins_url( 'js/script-loader.js', __FILE__ ), [], "1.0.0", true );
        wp_localize_script( 'reward-dice-precisefunnels', 'pfrd_php_vars', $data_to_pass );
    }
}

add_action( 'wp_enqueue_scripts', 'reward_dice_js_enqueue' );