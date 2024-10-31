<?php

class BasbAdminMetabox {

    protected static $_instance = null;

    static function get_metabox_params_map_bos() {
        return array("_basb_metabox_override_dest" => "_bos_mb_destination",
            "_basb_metabox_override_dest_id" => "_bos_mb_dest_id",
            "_basb_metabox_override_dest_type" => "_bos_mb_dest_type");
    }

    static function get_metabox_params() {
        return array("_basb_metabox_override_dest" => "",
            "_basb_metabox_override_dest_id" => "",
            "_basb_metabox_override_dest_type" => "");
    }

    static function get_metabox_help_params() {
        return array("_basb_metabox_override_dest" => __("E.g: London", BASB_TEXT_DOMAIN),
            "_basb_metabox_override_dest_id" => __("E.g: -372490 for Barcelona", BASB_TEXT_DOMAIN),
            "_basb_metabox_override_dest_type" => "E.g. select city if you insert the ID -372490 for Barcelona", BASB_TEXT_DOMAIN);
    }

    static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
        add_action('add_meta_boxes', array($this, 'basb_admin_metabox_add'));
        add_action('save_post', array($this, 'basb_admin_metabox_save'));
    }

    function basb_admin_metabox_add() {
        $options = BasbUtils::get_basb_settings_options();
        $post_types = BASB_DEFAULT_POST_TYPE_METABOXES;
        if (isset($options["post_type_enabled_metabox"]) && !empty($options["post_type_enabled_metabox"])) {
            $post_types.="," . $options["post_type_enabled_metabox"];
        }
        $post_types_array = explode(',', $post_types);

        foreach ($post_types_array as $v) {
            $post_type = trim($v);
            if (!empty($post_type)) {
                add_meta_box('basb_admin_metabox_post', __('Booking.com affiliate widget control', BASB_TEXT_DOMAIN), array($this, 'basb_admin_metabox_create'), $post_type, 'normal', 'high');
            }
        }
    }

    function basb_admin_metabox_create($post) {
        $metabox_params = self::get_metabox_params();
        $metabox_help_params = self::get_metabox_help_params();
        foreach ($metabox_params as $k => $v) {
            $metabox_params[$k] = get_post_meta($post->ID, $k, true);
        }
        ob_start();
        _e("If you want use these fields below to select a custom destination and change the widget configuration preferences only for this post", BASB_TEXT_DOMAIN);
        ?>
        <style>
            #basb_admin_metabox_post label
            {
                font-weight:bold;
                display:inline-block;
                width:120px;
                margin-right:10px;
            }
            #basb_admin_metabox_post input[type=text],#basb_admin_metabox_post select
            {
                width:200px;
                margin-right:10px;
            }

            #basb_admin_metabox_post P > span
            {
                font-size: 80%;
            }
        </style>
<?php /*
        <p><label for="_basb_metabox_override_dest"><?php _e('Destination', BASB_TEXT_DOMAIN); ?></label>
            <input type="text" name="_basb_metabox_override_dest" value="<?php echo $metabox_params["_basb_metabox_override_dest"]; ?>" placeholder="<?php echo $metabox_help_params["_basb_metabox_override_dest"]; ?>" /><span><?php echo $metabox_help_params["_basb_metabox_override_dest"]; ?></span>
        </p>
        <p><?php _e("Or", BASB_TEXT_DOMAIN); ?></p>
*/ ?>
        <p><label for="_basb_metabox_override_dest_id"><?php _e('Destination ID', BASB_TEXT_DOMAIN); ?></label>
            <input type="text" name="_basb_metabox_override_dest_id" value="<?php echo $metabox_params["_basb_metabox_override_dest_id"]; ?>"  placeholder="<?php echo $metabox_help_params["_basb_metabox_override_dest_id"]; ?>" /><span><?php echo $metabox_help_params["_basb_metabox_override_dest_id"]; ?></span>
        </p>
        <p><label for="_basb_metabox_override_dest_type"><?php _e('Destination TYPE', BASB_TEXT_DOMAIN); ?></label>
            <select name="_basb_metabox_override_dest_type">
                <?php
                $options = BasbUtils::get_basb_vc_dropdown_dest_type_list();
                foreach ($options as $k => $v) {
                    ?><option value="<?php echo $v["value"] ?>" <?php if (!empty($v["value"]) && $v["value"] == $metabox_params["_basb_metabox_override_dest_type"]) echo "selected"; ?>><?php echo $v["label"] ?></option>
                <?php } ?>                    
            </select>
            <span><?php echo $metabox_help_params["_basb_metabox_override_dest_type"]; ?></span>
        </p>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }

    function basb_admin_metabox_save($post_id) {
        $metabox_params = self::get_metabox_params();
        foreach ($metabox_params as $k => $v) {
            if (isset($_POST[$k]))
                update_post_meta($post_id, $k, strip_tags($_POST[$k]));
        }
    }

}
