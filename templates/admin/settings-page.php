<div class="wrap">
    <h1><?php esc_html_e(get_admin_page_title());?></h1>
    <form action="options.php" method="post">
        <?php settings_fields('reward_dice_settings'); ?>
        <?php do_settings_sections('reward-dice'); ?>
        <?php submit_button(); ?>
    </form>
    <p>Integration status: <?php esc_html_e(reward_dice_check_integration_status()); ?></p>
</div>