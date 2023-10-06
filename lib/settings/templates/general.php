<?php

	// UPDATE SETTINGS
	if( isset( $_POST['sp_sbins_settings'] ) ){
		$settings = $this->get_settings();
		$updated_settings = array_merge( $settings, $_POST['sp_sbins_settings']);
		$this->write_settings( $updated_settings );
	}

	$settings = $this->get_settings();

?>
<form method="post">
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><label>Post Types</label></th>
        <td>
          <?php
						foreach( $this->get_post_types() as $slug => $label ):
						$field_name = "$this->settings_slug[post_types][$slug]";
						$is_checked = isset( $settings['post_types'][$slug] ) && $settings['post_types'][$slug] ? "checked='checked'" : "";
					?>
            <label class="widefat">
							<input type="hidden" name="<?php _e( $field_name );?>" value="0">
							<input type="checkbox" name="<?php _e( $field_name );?>" value="1" <?php _e( $is_checked );?>>
              <?php _e( $label );?>
            </label>
          <?php endforeach;?>
          <small class="description">Post types in which the sidebar inserter will be enabled</small>
        </td>
      </tr>
    </tbody>
  </table>
  <p class='submit'><input type="submit" name="submit" class="button button-primary" value="Save Settings"><p>
</form>
<style>
  label.widefat {
    display:block;
  }

  small.description {
    display: block;
    color: #999999;
    margin-top: 5px;
    font-style: italic;
  }
</style>
