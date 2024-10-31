<?php

class BasbShortcode {

    protected static $_instance = null;

    static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
        add_shortcode(BASB_SHORTCODE, array($this, 'basb_product_box_shortcode'));
        add_action('vc_before_init', array($this, 'vc_basb_product_box_shortcode'));
    }

    function basb_product_box_shortcode($atts, $content = null) {
        return $this->basb_product_box_shortcode_v1($atts, $content);
    }

    private function basb_product_box_shortcode_v1($atts, $content = null) {
        $html = '';
        if (isset($atts["id"]) && absint($atts["id"]) && $atts["id"] <= BASB_MAX_PRODUCTS) {
            $id = $atts["id"];
            $product = BasbUtils::get_basb_product_data($atts["id"]);
            $product["dest"] = '';
            $product["dest_id"] = '';
            $product["dest_type"] = '';

            if (!empty($product)) {
                $sc = shortcode_atts($product, $atts);
                $s_width = (!empty($sc["width"]) ? $sc["width"] : $product["width"]);
                $s_height = (!empty($sc["height"]) ? $sc["height"] : $product["height"]);

                $url = BASB_BOOKING_IFRAME . "?product=" . esc_attr($product["prod"]) . "&w=" . esc_attr($s_width) . "&h=" . esc_attr($s_height) . "&aid=" . esc_attr($product["aid"]) . "&target_aid=" . esc_attr($product["target_aid"]);
                //Destionation                
                if (!empty($sc["dest"])) {
                    $url.="&ss=" . urlencode($sc["dest"]);
                }
                if (!empty($sc["dest_type"]) && !empty($sc["dest_id"])) {
                    $url.="&ss_id=" . urlencode($sc["dest_id"]);
                    $url.="&ss_type=" . urlencode($sc["dest_type"]);
                }
                //extra options
                $extra = array("cc1" => "cc1",
                    "lang" => "lang",
                    "label" => "label_load",
                    "label_click" => "j.label_out",
                    "selected_currency" => "currency",
                    "df_checkin" => "df_checkin",
                    "df_duration" => "df_duration",
                    "banner_id" => "banner_id",
                    "tmpl" => "tmpl");
                foreach ($extra as $k => $v) {
                    if (isset($sc[$k])) {
                        $s_e = (!empty($sc[$k]) ? $sc[$k] : $product[$k]);
                        if(!empty($s_e))
                            $url.="&$v=".urlencode($s_e);
                    }
                }
                ob_start();
                ?>
                <div class="basb_product_box basb_product_box_<?php echo esc_attr($atts["id"]); ?>">
                    <iframe src="<?php echo esc_url_raw($url); ?>" width="<?php echo $s_width; ?>" height="<?php echo $s_height; ?>" scrolling="no" style="border:none;padding:0;margin:0;overflow:hidden" marginheight="0" marginwidth="0" frameborder="0" allowtransparency="true"></iframe>                
                </div>
                <?php
                $html = ob_get_contents();
                ob_end_clean();
            }
        }
        return $html;
    }

    static function get_vc_basb_product_params() {
        $params = array(
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Search box template", BASB_TEXT_DOMAIN),
                "param_name" => "id",
                "value" => BasbUtils::get_basb_vc_dropdown_product_list(),
                "description" => esc_html__("Select you search box or inspiring box.", BASB_TEXT_DOMAIN)
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Width", BASB_TEXT_DOMAIN),
                "param_name" => "width",
                "value" => esc_html__("", BASB_TEXT_DOMAIN),
                "description" => esc_html__("This value will override the default settings", BASB_TEXT_DOMAIN)
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Height", BASB_TEXT_DOMAIN),
                "param_name" => "height",
                "value" => esc_html__("", BASB_TEXT_DOMAIN),
                "description" => esc_html__("This value will override the default settings", BASB_TEXT_DOMAIN)
            ),/*
            array(
                "type" => "textfield",
                "heading" => esc_html__("Destination", BASB_TEXT_DOMAIN),
                "param_name" => "dest",
                "value" => esc_html__("", BASB_TEXT_DOMAIN),
                "description" => esc_html__("Destination text", BASB_TEXT_DOMAIN)
            ),*/
            array(
                "type" => "textfield",
                "heading" => esc_html__("Destination ID", BASB_TEXT_DOMAIN),
                "param_name" => "dest_id",
                "value" => esc_html__("", BASB_TEXT_DOMAIN),
                "description" => esc_html__("Destination ID, better than destination text", BASB_TEXT_DOMAIN)
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Destination type", BASB_TEXT_DOMAIN),
                "param_name" => "dest_type",
                "value" => BasbUtils::get_basb_vc_dropdown_dest_type_list(),
                "description" => esc_html__("Select your destination type if you insert a destination ID", BASB_TEXT_DOMAIN)
            ),
        );
        return $params;
    }

    function vc_basb_product_box_shortcode() {
        $params = self::get_vc_basb_product_params();
        vc_map(
                array(
                    "name" => esc_html__("Search box for Booking.com affiliates", BASB_TEXT_DOMAIN),
                    "base" => BASB_SHORTCODE,
                    "category" => esc_html__('Booking', BASB_TEXT_DOMAIN),
                    "params" => $params,
                )
        );
    }

}
