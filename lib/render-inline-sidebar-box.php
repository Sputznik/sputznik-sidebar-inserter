<?php
  $default_sidebar = $this->get_default_sidebar_slug();
  $field_name = $this->get_post_meta_key()."[$default_sidebar]";
  $settings = get_post_meta( $post->ID, $this->get_post_meta_key(), true );

  $placement  = isset( $settings[$default_sidebar]['placement'] ) ? $settings[$default_sidebar]['placement'] : 4;
  $is_checked = isset( $settings[$default_sidebar]['disabled'] ) && $settings[$default_sidebar]['disabled'] ? "checked='checked'" : "";

  // echo "<pre>";
  // echo "placement: ".$placement."<br/>";
  // echo "Disabled: ".$is_checked."<br/>";
  // print_r( $settings );
  // echo "</pre>";
?>
<div id="sp-sbins-sidebar-inner">
  <table>
    <thead>
      <tr>
        <th class="left">Sidebar</th>
        <th>Sidebar placement</th>
        <th>Disabled</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left">
          <input type="hidden" name="<?php _e( $field_name );?>">
          <input type="text" size="20" value="Single Post Inline Widgets" disabled>
        </td>
        <td>
          <input type="number" name="<?php _e("$field_name");?>[placement]" min="-1" value="<?php _e( $placement );?>">
        </td>
        <td class="disabled">
          <input type="hidden" name="<?php _e("$field_name");?>[disabled]" value="0">
          <input type="checkbox" name="<?php _e("$field_name");?>[disabled]" value="1" <?php _e( $is_checked );?>>
        </td>
      </tr>
    </tbody>
  </table>
  <small class="help">If value < 0 after the content, value == 0 before the content, value > 0 between the content.</small>
</div>
<style>
  #sp_sbins_sidebar table {
    margin: 0;
    width: 100%;
    border: 1px solid #dcdcde;
    border-spacing: 0;
    background-color: #f6f7f7;
  }

  #sp_sbins_sidebar tr {
    vertical-align: top;
  }

  #sp_sbins_sidebar td.left,
  #sp_sbins_sidebar th.left {
    width: 38%;
  }

  #sp_sbins_sidebar td.disabled {
    width: 50px;
    text-align: center;
  }

  #sp_sbins_sidebar td.disabled > input {
    width: auto;
    margin: 12px 8px;
  }

  #sp_sbins_sidebar thead th {
    padding: 5px 8px 8px;
    background-color: #f0f0f1;
  }

  #sp-sbins-sidebar-inner table input,
  #sp-sbins-sidebar-inner table select,
  #sp-sbins-sidebar-inner table textarea {
    width: 96%;
    margin: 8px;
  }

  #sp_sbins_sidebar .help {
    display: block;
    padding: 10px;
    margin-top: 12px;
    color: #ffffff;
    font-size: 13px;
    background-color: #000000;
  }

</style>
