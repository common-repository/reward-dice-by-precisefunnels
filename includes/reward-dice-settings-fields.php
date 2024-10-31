<?php

function reward_dice_settings()
{
    // Create the setting if not already created.
    if (!get_option('reward_dice_settings')) {
        add_option('reward_dice_settings');
    }

    add_settings_section(
    'reward_dice_settings', // Unique id of the section (HAS TO MATCH THE SETTING OPTION NAME).
    'Reward Dice Settings', // Section title.
    'reward_dice_settings_section_callback', // Callback for an optional description.
    'reward-dice' // Admin page to add the section to.
    );

    add_settings_field(
        'reward_dice_settings_access_key', // Unique id of the field.
        'Your access key', // Field title.
        'reward_dice_settings_access_key_callback', // Callback for field markup.
        'reward-dice', // Admin page to add the field to.
        'reward_dice_settings' // Section to add the field to.
    );

    // Register a setting for saving.
    register_setting(
        'reward_dice_settings', // Option group.
        'reward_dice_settings' // Option name.
    );
}

add_action('admin_init', 'reward_dice_settings');

function reward_dice_settings_section_callback()
{
	esc_html_e("Please follow these simple steps to activate Reward Dice on your WooCommerce store.", 'reward-dice');
    echo('<br><br>');
	printf('%s<a href="%s" target="_blank">%s</a>%s',		
		__('1. Log in (or sign up for a new account) at ', 'reward-dice'),
		esc_url('https://www.precisefunnels.com/reward-dice'),
		__('PreciseFunnels Reward Dice', 'reward-dice'),
		__('.', 'reward-dice')
    );	
	echo('<br><br>');
	printf('%s<a href="%s" target="_blank">%s</a>%s',		
		__('2. Visit ', 'reward-dice'),
		esc_url('https://www.precisefunnels.com/reward-dice/settings'),
		__(' settings page', 'reward-dice'),
		__(' and set up Reward Dice:', 'reward-dice')
    );
	echo('<br>');
	esc_html_e("As a bare minimum, you need to enter the exact domain of your shop 'www.yourcustomshopdomain.com'. You should also set up the design (for best results, use custom colors that match your store's design), enter the discount percentages and corresponding discount codes that your store visitors can win, and change any other default settings that are relevant to you.", 'reward-dice');	
	echo('<br><br>');
	printf('%s<a href="%s" target="_blank">%s</a>%s',		
		__('3. Visit ', 'reward-dice'),
		esc_url('https://www.precisefunnels.com/reward-dice/integrations'),
		__(' integrations page', 'reward-dice'),
		__(' and enable one of the available email integrations. After enabling an integration of your choice, you also need to select the mailing list/audience/group and click save again.', 'reward-dice')
    );
	echo('<br><br>');
	printf('%s<a href="%s" target="_blank">%s</a>%s',		
		__('4. Visit ', 'reward-dice'),
		esc_url('https://www.precisefunnels.com/reward-dice/installation'),
		__(' installation page', 'reward-dice'),
		__(' , expand the WooCommerce tab and copy your account id.', 'reward-dice')
    );	
	echo('<br><br>');
	esc_html_e("5. Paste your account id into the Reward Dice App Settings on WooCommerce (this page) and click save. After saving, the status should turn to 'active' and Reward Dice should begin displaying on the frontend of your store (to display it multiple times in one day, you need to clear your browser cookies or use incognito mode).", 'reward-dice');	
}

function reward_dice_settings_access_key_callback()
{
    $options = get_option('reward_dice_settings');

    $access_key = '';
    if (isset($options['access_key'])) {
        $access_key = esc_html($options['access_key']);
    }
?>
    <input type="password" id="reward_dice_access_key" name="reward_dice_settings[access_key]" value="<?php esc_html_e($access_key); ?>">
<?php
}