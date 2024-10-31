<?php

class BasbProductWidget extends WP_Widget {

    function __construct() {
        parent::__construct(
                BASB_WIDGET, __('Booking.com affiliate box', BASB_TEXT_DOMAIN), array('description' => __('Easy way to add a booking.com affiliate search box', BASB_TEXT_DOMAIN),)
        );
    }

    public function widget($args, $instance) {
        if (is_page() || is_single()) {
            global $wp_query;
            $exists_values = false;
            $metabox_params = BasbAdminMetabox::get_metabox_params();
            $post_id = $wp_query->post->ID;
            foreach ($metabox_params as $k => $v) {
                $metabox_params[$k] = get_post_meta($post_id, $k, true);
                $exists_values = $exists_values || !empty($metabox_params[$k]);
            }

            if (!$exists_values && is_plugin_active("booking-official-searchbox/booking-official-searchbox.php")) {
                $metabox_params = BasbAdminMetabox::get_metabox_params_map_bos();
                foreach ($metabox_params as $k => $v) {
                    $metabox_params[$k] = get_post_meta($post_id, $v, true);
                    $exists_values = $exists_values || !empty($metabox_params[$k]);
                }
            }
            wp_reset_query();
            if ($exists_values) {
                if (!empty($metabox_params["_basb_metabox_override_dest_id"])) {
                    $instance["dest_id"] = $metabox_params["_basb_metabox_override_dest_id"];
                }
                if (!empty($metabox_params["_basb_metabox_override_dest_type"])) {
                    $instance["dest_type"] = $metabox_params["_basb_metabox_override_dest_type"];
                }
                if (!empty($metabox_params["_basb_metabox_override_dest"])) {
                    $instance["dest"] = $metabox_params["_basb_metabox_override_dest"];
                }
            }
        }
        echo $args['before_widget'];
        echo BasbShortcode::get_instance()->basb_product_box_shortcode($instance);
        echo $args['after_widget'];
    }

    public function form($instance) {
        $params = BasbShortcode::get_vc_basb_product_params();
        $title = !empty($instance['title']) ? $instance['title'] : __('New title', BASB_TEXT_DOMAIN);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> (<?php _e('optional'); ?>)</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
        foreach ($params as $param) {
            if ($param["type"] == "textfield") {
                ?>
                <p>
                    <label for="<?php echo $this->get_field_id($param["param_name"]); ?>"><?php echo $param["heading"]; ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id($param["param_name"]); ?>" name="<?php echo $this->get_field_name($param["param_name"]); ?>" type="text" value="<?php echo esc_attr($instance[$param["param_name"]]); ?>">
                </p>	                    
                <?php
            } else if ($param["type"] == "dropdown") {
                ?><p>
                    <label for="<?php echo $this->get_field_id($param["param_name"]); ?>"><?php echo $param["heading"]; ?></label> 
                    <select class="widefat" id="<?php echo $this->get_field_id($param["param_name"]); ?>" name="<?php echo $this->get_field_name($param["param_name"]); ?>">
                        <?php 
                        foreach ($param["value"] as $k => $v) { ?>
                            <option value="<?php echo $v["value"]; ?>" <?php if ($v["value"] == $instance[$param["param_name"]]) echo "selected"; ?>><?php echo $v["label"]; ?></option>
                        <?php } ?>
                    </select>
                </p><?php
            }
        }
        ?>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $params = BasbShortcode::get_vc_basb_product_params();
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        foreach ($params as $param) {
            if ($param["type"] == "textfield" || $param["type"] == "dropdown") {
                $instance[$param["param_name"]] = (!empty($new_instance[$param["param_name"]]) ) ? strip_tags($new_instance[$param["param_name"]]) : '';
            }
        }
        return $instance;
    }

    static function register_widget() {
        add_action('widgets_init', function() {
            register_widget('BasbProductWidget');
        }
        );
    }

}
