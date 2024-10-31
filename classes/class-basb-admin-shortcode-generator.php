<?php

class BasbAdminSortcodeGenerator {

    protected static $_instance = null;

    static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
        add_action('media_buttons_context', array($this, 'basb_admin_shortcode_generator_button'));
        add_action('admin_footer', array($this, 'basb_admin_shortcode_generator_js'));
    }

// Action target that adds the "Insert" button to the post/page edit screen.
    function basb_admin_shortcode_generator_button($context) {
        add_thickbox();
        $html = '<a style="margin-left:10px;margin-right;10px" href="#TB_inline?height=400&inlineId=basb_admin_shortcode_generator_popup" id="basb_admin_shortcode_generator_button" class="thickbox button" data-editor="content" title="' . esc_attr(__('Search box integration shortcode generator for Booking.com affiliates', BASB_TEXT_DOMAIN)) . '">&#x2913; ' . __('Add Search Box integration for Booking.com', BASB_TEXT_DOMAIN) . '</a>';
        return $context . $html;
    }

    function basb_admin_shortcode_generator_js() {
        ob_start();
        ?><div id="basb_admin_shortcode_generator_popup" class="folded" style="display:none;">
            <style>
                .basb_post_field label{
                    width:120px;
                    font-weight: bold;
                    display:inline-block;
                }
                .basb_post_field input[type=text],.basb_post_field select {
                    width:200px;
                }
                .basb_post_generated
                {
                    min-height:25px;
                    line-height: 25px;
                    
                }
            </style>
            <div class="wrap">
                <h3 class="media-title"><?php _e('Search box integration shortcode generator for Booking.com affiliates', BASB_TEXT_DOMAIN); ?></h3>
                <form>
                    <?php
                    $params = BasbShortcode::get_vc_basb_product_params();
                    foreach ($params as $param) {
                        $param["param_name"] = "basb_admin_form_" . $param["param_name"];
                        if ($param["type"] == "textfield") {
                            ?>
                            <div class="basb_post_field">
                                <label for="<?php echo $param["param_name"]; ?>"><?php echo $param["heading"]; ?></label> 
                                <input id="<?php echo $param["param_name"]; ?>" name="<?php echo $param["param_name"]; ?>" type="text" value="">
                            </div>	                    
                            <?php
                        } elseif ($param["type"] == "dropdown") {
                            ?><div class="basb_post_field">
                                <label for="<?php echo $param["param_name"]; ?>"><?php echo $param["heading"]; ?></label> 
                                <select id="<?php echo $param["param_name"]; ?>" name="<?php echo $param["param_name"]; ?>">
                                    <?php foreach ($param["value"] as $k => $v) { ?>
                                        <option value="<?php echo $v["value"]; ?>"><?php echo $v["label"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div><?php
                        }
                    }
                    ?>
                    <br/>
                    <strong><?php echo __("Generated shortcode", BASB_TEXT_DOMAIN); ?>:</strong>
                    <div class="basb_post_generated"></div>
                    <br/>
                    <input id="basb_add_post_sh_generated" type="button" class="button-primary" value="<?php _e("Insert shortcode in the content", BASB_TEXT_DOMAIN); ?>"/>        
                    <input type="button" class="button" value="<?php _e("Cancel", BASB_TEXT_DOMAIN); ?>" onclick="tb_remove();"/>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(function () {
                function basb_admin_shortcode_generate()
                {
                    var sh = "[<?php echo BASB_SHORTCODE; ?> ";
                    var aux = ["id", "width", "height", "dest","dest_id","dest_type"];
                    for (var i = 0; i < aux.length; i++)
                    {
                        var $auxC=jQuery("#basb_admin_form_" + aux[i]);
                        if ($auxC.length>0 && $auxC.val() != "")
                            sh += " " + aux[i] + "=\"" + jQuery("#basb_admin_form_" + aux[i]).val() + "\"";

                    }
                    sh += "]";
                    if (jQuery("#basb_admin_form_id").val() > 0)
                    {
                        jQuery("#basb_add_post_sh_generated").removeAttr("disabled");
                    }
                    else
                    {
                        sh = "<?php echo esc_attr(__("Please select a search box", BASB_TEXT_DOMAIN)); ?>";
                    }
                    jQuery(".basb_post_generated").html(sh);
                }

                basb_admin_shortcode_generate();

                function basb_admin_shortcode_generator_insert() {
                    basb_admin_shortcode_generate();
                    var sh = jQuery(".basb_post_generated").text();
                    var win = window.dialogArguments || opener || parent || top;
                    win.send_to_editor(sh);
                }

                jQuery(".basb_post_field select").change(basb_admin_shortcode_generate);
                jQuery(".basb_post_field input[type=text]").change(basb_admin_shortcode_generate);
                jQuery("#basb_add_post_sh_generated").click(basb_admin_shortcode_generator_insert);
                jQuery("#basb_add_post_sh_generated").attr("disabled", "disabled");

            });
        </script>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }

}
