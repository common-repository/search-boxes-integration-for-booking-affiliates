<?php

class BasbUtils {

    static function extract_attr_from_ins($code) {
        preg_match_all('/(?:<ins|(?<!^)\G)\s*([a-zA-Z0-9\-_]+)="([^"]+)"(?=(.|\s)*?\>)/im', $code, $match);
        $ret = array();
        $max = count($match[1]);
        for ($i = 0; $i < $max; $i++) {
            if (strpos($match[1][$i], "data-") === 0) {
                $ret[substr($match[1][$i], strlen("data-"))] = $match[2][$i];
            }
        }
        return $ret;
    }

    static function get_basb_settings_options() {
        $bapSettings = get_option(BASB_SETTINGS_OPTIONS);
        return $bapSettings;
    }

    static function get_basb_product($k) {
        $res = NULL;
        if (!empty($k)) {
            $bapSettings = self::get_basb_settings_options();
            if (isset($bapSettings["product_data_$k"]) && count($bapSettings["product_data_$k"]) > 0 &&
                    absint($bapSettings["product_data_$k"]["aid"]) && absint($bapSettings["product_data_$k"]["target_aid"])) {
                $res = array(
                    "product_name" => $bapSettings["product_name_$k"],
                    "product_code" => $bapSettings["product_code_$k"],
                    "product_data" => $bapSettings["product_data_$k"],
                    "id" => $k,
                );
                $res["product_data"]["id"] = $k;
            }
        }
        return $res;
    }

    static function get_basb_product_data($k) {
        $res = self::get_basb_product($k);
        if ($res != null) {
            $res = $res["product_data"];
            $res["id"] = $k;
        }
        return $res;
    }

    static function get_basb_product_list() {
        $products = array();
        for ($i = 1; $i <= BASB_MAX_PRODUCTS; $i++) {
            $product = self::get_basb_product($i);
            if (!empty($product))
                $products[$i] = self::get_basb_product_name_complete($product);
        }
        return $products;
    }

    static function get_basb_vc_dropdown_product_list() {
        $list = self::get_basb_product_list();
        $new_list = array();
        $new_list[] = array("label" => __("Select product box", BASB_TEXT_DOMAIN), "value" => '');
        foreach ($list as $k => $v) {
            $new_list[] = array("label" => $v, "value" => $k);
        }
        return $new_list;
    }

    static function get_basb_vc_dropdown_dest_type_list()
    {
        $list=array(
            array("label"=>__("Select destination type", BASB_TEXT_DOMAIN),"value"=>""),
            array("label"=>__("Hotel", BASB_TEXT_DOMAIN),"value"=>"hotel"),
            array("label"=>__("City", BASB_TEXT_DOMAIN),"value"=>"city"),
            array("label"=>__("Landmark", BASB_TEXT_DOMAIN),"value"=>"landmark"),
            array("label"=>__("District", BASB_TEXT_DOMAIN),"value"=>"district"),
            array("label"=>__("Region", BASB_TEXT_DOMAIN),"value"=>"region"),
        );
               return $list;
    }
    
    static function get_basb_product_type_title($type) {
        if ($type == "nsb")
            return __("Search box", BASB_TEXT_DOMAIN);
        else if ($type == "banner")
            return __("Banner", BASB_TEXT_DOMAIN);
        else if ($type == "sbp")
            return __("Inpiration box", BASB_TEXT_DOMAIN);
        else
            return __("Unknow", BASB_TEXT_DOMAIN);
    }

    static function get_basb_product_name_prefix($data) {
        return self::get_basb_product_type_title($data["prod"]) . " - ID: " . $data["id"];
    }

    static function get_basb_product_name_complete($product) {
        return self::get_basb_product_name_prefix($product["product_data"]) . (!empty($product["product_name"]) ? " - " . $product["product_name"] : '');
    }

}
