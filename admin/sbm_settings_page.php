<div class="wrap sbm">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1> 
  <form method="post" action="options.php">
    <?php settings_fields( 'sbm_notif_settings' ); ?>
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="sbm_notif_email"><?php _e( 'Email Address', 'sbm-notif' ); ?></label></th>
          <td>
            <input type="email" name="sbm_notif_email" id="sbm_notif_email" value="<?php echo esc_attr( get_option( 'sbm_notif_email' ) ); ?>" />
            <p class="description"><?php _e( 'Enter the email address for notifications.', 'sbm-notif' ); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="sbm_recaptcha_site_key"><?php _e( 'reCAPTCHA V3 Site Key', 'sbm-notif' ); ?></label></th>
          <td>
            <input type="text" name="sbm_recaptcha_site_key" id="sbm_recaptcha_site_key" value="<?php echo esc_attr( get_option( 'sbm_recaptcha_site_key' ) ); ?>" />
            <p class="description"><?php _e( 'Enter your reCAPTCHA Site Key for spam protection.', 'sbm-notif' ); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="sbm_recaptcha_secret_key"><?php _e( 'reCAPTCHA V3 Secret Key', 'sbm-notif' ); ?></label></th>
          <td>
            <input type="text" name="sbm_recaptcha_secret_key" id="sbm_recaptcha_secret_key" value="<?php echo esc_attr( get_option( 'sbm_recaptcha_secret_key' ) ); ?>" />
            <p class="description"><?php _e( 'Enter your reCAPTCHA Secret Key for verification.', 'sbm-notif' ); ?></p>
          </td>
        </tr>
      </tbody>
    </table>
    <?php submit_button( 'Save Changes' ); ?>
  </form>
</div>
