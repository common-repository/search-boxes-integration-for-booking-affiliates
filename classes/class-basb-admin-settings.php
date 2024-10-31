<?php

class BasbAdmin {

    protected static $_instance = null;

    static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    static function basb_settings_product_fields_array() {
        $fields = array();

        $fields['product_name'] = array(
            'product_name',
            'text',
            __('Search Box title', BASB_TEXT_DOMAIN),
            __('Insert brief text (e.g: home, right sidebar)', BASB_TEXT_DOMAIN)
        );

        $fields['product_code'] = array(
            'product_code',
            'textarea',
            __('Search box code', BASB_TEXT_DOMAIN),
            __('Paste here the code to generate the searchbox copied from Booking.com Affiliate Partner Center. This code has to start with the tag "ins"', BASB_TEXT_DOMAIN)
        );

        return $fields;
    }

    static function basb_settings_fields_array() {
        $fields = array();
        /*
        $fields['aid'] = array(
            'aid',
            'text',
            __('Your affiliate ID', BASB_TEXT_DOMAIN),
            __('Booking affiliate ID', BASB_TEXT_DOMAIN)
        );
        */
        $fields['post_type_enabled_metabox'] = array(
            'post_type_enabled_metabox',
            'text',
            __('Widget control Metabox in post types', BASB_TEXT_DOMAIN),
            __('Add custom post types. E.g.: portfolio', BASB_TEXT_DOMAIN)
        );
        return $fields;
    }

    function __construct() {
        add_action('admin_menu', array($this, 'basb_admin_menu'));
        add_action('admin_init', array($this, 'basb_admin_init'));
    }

    function basb_admin_menu() {
        $this->titleOptionMenuPage = __('Search boxes integration for Booking', BASB_TEXT_DOMAIN);
        $this->titleOptionTitlePage = __('Search boxes integration for Booking.com affiliates', BASB_TEXT_DOMAIN) . " - " . __('Settings page', BASB_TEXT_DOMAIN);

        add_options_page($this->titleOptionTitlePage, $this->titleOptionMenuPage, 'manage_options', BASB_SETTINGS_OPTIONS_PAGE, array($this, "basb_settings_page"));
    }

    function basb_admin_init() {
        register_setting(BASB_SETTINGS_OPTIONS, BASB_SETTINGS_OPTIONS, array($this, 'basb_validate_admin_form_callback'));

        add_settings_section(
                BASB_SETTINGS_OPTIONS . '_section_main', ' ', array($this, 'basb_render_section_main_callback'), BASB_SETTINGS_OPTIONS_PAGE
        );

        $fields = self::basb_settings_fields_array();
        foreach ($fields as $field) {
            add_settings_field(
                    $field[0], $field[2], array($this, 'basb_render_settings_field_callback'), BASB_SETTINGS_OPTIONS_PAGE, BASB_SETTINGS_OPTIONS . '_section_main', $field
            );
        }

        $fields = self::basb_settings_product_fields_array();
        for ($i = 1; $i <= BASB_MAX_PRODUCTS; $i++) {
            add_settings_section(
                    BASB_SETTINGS_OPTIONS . '_section_product_' . $i, __('Search box template', BASB_TEXT_DOMAIN) . " $i:", array($this, 'basb_render_section_product_callback'), BASB_SETTINGS_OPTIONS_PAGE
            );
            foreach ($fields as $field) {
                $field[0] = $field[0] . "_" . $i;
                $field[4] = $i;
                add_settings_field(
                        $field[0], $field[2], array($this, 'basb_render_settings_field_callback'), BASB_SETTINGS_OPTIONS_PAGE, BASB_SETTINGS_OPTIONS . '_section_product_' . $i, $field
                );
            }
        }
    }

    function basb_render_settings_field_callback($args) {
        $field_name = BASB_SETTINGS_OPTIONS . "[" . $args[0] . "]";
        $options = BasbUtils::get_basb_settings_options();
        $field_value = '';
        $html = '';
        if (isset($options[$args[0]])) {
            $basb_value = $options[$args[0]];
            $field_value = $basb_value;
        } else
            $basb_value = '';

        if (isset($args[4]))
            $product = BasbUtils::get_basb_product($args[4]);
        else
            $product = null;

        if ($args[1] == 'text') {
            if (strpos($args[0], "product_") === 0) {
                if (!empty($product) && count($product) > 0) {
                    $html.="<span>" . BasbUtils::get_basb_product_name_prefix($product["product_data"]) . " - </span>";
                } else {
                    $html.="<span>" . __("Search box template ID", BASB_TEXT_DOMAIN) . ": " . $args[4] . " - " . "</span>";
                }
            } else if ($args[0] == "post_type_enabled_metabox") {
                $html.='<span>' . BASB_DEFAULT_POST_TYPE_METABOXES . ', </span>';
            }
            $html .= '<input name="' . $field_name . '" id="' . $args[0] . '" type="' . $args[1] . '" value="' . esc_attr($field_value) . '" placeholder="' . esc_attr($args[3]) . '">';
        } elseif ($args[1] == 'textarea') {
            $html .= '<textarea name="' . $field_name . '" id="' . $args[0] . '" placeholder="' . esc_attr($args[3]) . '" >' . $field_value . '</textarea>';
            if (strpos($args[0], "product_") === 0 && !empty($product) && count($product) > 0) {
                $html.="<h5>" . __("Shortcode", BASB_TEXT_DOMAIN) . ": </h5> [" . BASB_SHORTCODE . " id=\"" . $args[4] . "\"]</div>";
                $html.="<h5>" . __("Preview", BASB_TEXT_DOMAIN) . ": </h5>" . $product["product_code"] . "";
            }
        }
        echo $html;
    }

    function basb_validate_admin_form_callback($inputs) {
        $message = array();
        $result = array();
        /*
        if (empty($inputs["aid"]) || !absint($inputs["aid"])) {
            $message[] = sprintf(__("Your affiliate ID must be a positive integer", BASB_TEXT_DOMAIN), $i);
        } else {
            $result["aid"] = $inputs["aid"];
        }
       
          if (empty($inputs["post_type_enabled_metabox"])) {
          $message[] = sprintf(__("You must write at lest one post type. E.g: page", BASB_TEXT_DOMAIN), $i);
          } else {
          $result["post_type_enabled_metabox"] = $inputs["post_type_enabled_metabox"];
          }
         */
        $result["post_type_enabled_metabox"] = $inputs["post_type_enabled_metabox"];

        for ($i = 1; $i <= BASB_MAX_PRODUCTS; $i++) {
            $name = (isset($inputs["product_name_$i"]) ? $inputs["product_name_$i"] : '');
            $code = (isset($inputs["product_code_$i"]) ? $inputs["product_code_$i"] : '');
            if (!empty($code)) {
                $attr = BasbUtils::extract_attr_from_ins($code);
                if (isset($attr["aid"]) && isset($attr["target_aid"])) {
                    if (absint($attr["aid"]) && absint($attr["target_aid"])) {
                        $data = $attr;
                        $result["product_data_$i"] = $data;
                    } else {
                        $message[] = sprintf(__("For search box template %s: data-aid and data-target_aid attributes must be positive integers", BASB_TEXT_DOMAIN), $i);
                    }
                } else {
                    $message[] = sprintf(__("For search box template %s: Can not find data-aid and data-target_aid from tag ins. Did you copy&paste the proper generated code from http://www.booking.com/partner", BASB_TEXT_DOMAIN), $i);
                }

                $result["product_code_$i"] = $code;
            } else {
                $name = NULL;
                $result["product_code_$i"] = NULL;
                $result["product_data_$i"] = NULL;
            }
            $result["product_name_$i"] = sanitize_text_field($name);
        }
        if (count($message) > 0) {
            add_settings_error(BASB_SETTINGS_OPTIONS, 'basb_admin_options_texterror', implode('', $message), 'error');
        }
        return $result;
    }

    function basb_render_section_main_callback() {
        
    }

    function basb_render_section_product_callback() {
        echo '<div><p><em>';
        echo __('Copy & Paste the generated product box (search box and  inspitating search box) in "Search box code"', BASB_TEXT_DOMAIN);
        echo '<br/>' . __('After save these settings you will be able to see a preview of the generated code and override some parameters in widgets, shortcodes and posts', BASB_TEXT_DOMAIN);
        echo '</em></p></div>';
    }

    function basb_settings_page() {
        ?><h2><?php echo $this->titleOptionTitlePage; ?></h2>
        <div class="wrap">   
            <div class="updated"><p><?php
        _e('Add your search boxes and inspiring search boxes generared from the section products in the <a href="https://admin.booking.com/partner/" target="_blank">Booking.com Affiliate Partner Center interface</a>', BASB_TEXT_DOMAIN);
        ?></p></div>
            <div><p>
                    <?php _e('With this plugin you will be able to add and manage the code generated from Booking.com Affiliate Partner Center and get all the tracking funcionalities of the Booking.com Affiliate Partner Center interface', BASB_TEXT_DOMAIN); ?>
                </p>
            </div>
            <div>
                <form action="options.php" method="post">
                    <?php
                    settings_fields(BASB_SETTINGS_OPTIONS);
                    do_settings_sections(BASB_SETTINGS_OPTIONS_PAGE);
                    ?>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="<?php
            _e('Save search box templates', BASB_TEXT_DOMAIN);
                    ?>" />                
                    </p>
                </form>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }

}
