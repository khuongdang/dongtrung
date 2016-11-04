<?php

class RSFunctionToApplyCoupon {

    public function __construct() {

        add_action('wp_head', array($this, 'apply_matched_coupons'));



        add_action('woocommerce_checkout_update_order_meta', array($this, 'remove_coupon_after_place_order'), 10, 2);

        add_action('woocommerce_before_cart_table', array($this, 'update_coupon_value_after_product_removed'));

        add_filter('woocommerce_coupon_message', array($this, 'get_coupon_code_data_sucess'), 10, 2);

        add_filter('woocommerce_coupon_error', array($this, 'get_coupon_code_data'), 10, 3);

        add_filter('woocommerce_coupon_error', array($this, 'rs_error_msg_for_autoredeem'), 10, 3);
    }

    public static function apply_matched_coupons() {

        global $woocommerce;
        $point_to_redeem = '1';
        if (isset($_POST['apply_coupon'])) {
            $user_ID = get_current_user_id();
            $coupon_code = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'nickname', true); // Code
        }
        if (isset($_POST['rs_apply_coupon_code']) || isset($_POST['rs_apply_coupon_code1'])) {
            if (isset($_POST['rs_apply_coupon_code_field'])) {
                $point_to_redeem_non_zero = $_POST['rs_apply_coupon_code_field'];
                if ($point_to_redeem_non_zero != '0' || '') {
                    $user_ID = get_current_user_id();
                    $getinfousernickname = get_user_by('id', $user_ID);
                    $couponcodeuserlogin = $getinfousernickname->user_login;
                    if (get_option('rs_redeem_field_type_option') == '2') {
                        if (isset($_POST['rs_apply_coupon_code1'])) {
                            $discount_type = 'percent';
                        }
                        if (isset($_POST['rs_apply_coupon_code'])) {
                            $discount_type = 'fixed_cart';
                        }
                    } else {
                        $discount_type = 'fixed_cart';
                    }

                    if (!is_array(get_option('rs_select_products_to_enable_redeeming'))) {
                        $allowproducts = explode(',', get_option('rs_select_products_to_enable_redeeming'));
                    } else {
                        $allowproducts = get_option('rs_select_products_to_enable_redeeming');
                    }

                    if (!is_array(get_option('rs_exclude_products_to_enable_redeeming'))) {
                        $excludeproducts = explode(',', get_option('rs_exclude_products_to_enable_redeeming'));
                    } else {
                        $excludeproducts = get_option('rs_exclude_products_to_enable_redeeming');
                    }
                    $allowcategory = get_option('rs_select_category_to_enable_redeeming');
                    $excludecategory = get_option('rs_exclude_category_to_enable_redeeming');
                    $coupon = array(
                        'post_title' => 'sumo_' . strtolower($couponcodeuserlogin),
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id(),
                        'post_type' => 'shop_coupon',
                    );


                    $getuserdataby = get_user_by('id', $user_ID);
                    $getloginnickname = $getuserdataby->user_login;
                    $oldcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'redeemcouponids', true);
                    wp_delete_post($oldcouponid, true);
                    $getuserdataby = get_user_by('id', $user_ID);
                    $getloginnickname = $getuserdataby->user_login;
                    $new_coupon_id = wp_insert_post($coupon);
                    update_user_meta($user_ID, 'redeemcouponids', $new_coupon_id);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $woocommerce->cart->subtotal_ex_tax);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $woocommerce->cart->cart_contents_count);

                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'discount_type', $discount_type);
                    if (isset($_POST['rs_apply_coupon_code'])) {
                        $point_to_redeem = $_POST['rs_apply_coupon_code_field'];
                    }
                    $point_control = get_option('rs_redeem_point');
                    $point_control_price = get_option('rs_redeem_point_value'); //i.e., 100 Points is equal to $1
                    $revised_amount = $point_to_redeem / $point_control;
                    $newamount = $revised_amount * $point_control_price;
                    $getmaxruleoption = get_option('rs_max_redeem_discount');
                    $getfixedmaxoption = get_option('rs_fixed_max_redeem_discount');
                    $getpercentmaxoption = get_option('rs_percent_max_redeem_discount');
                    $errpercentagemsg = get_option('rs_errmsg_for_max_discount_type');
                    $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                    if ($getmaxruleoption == '1') {
                        if ($getfixedmaxoption != '') {
                            if ($newamount > $getfixedmaxoption) {
                                $newamount = $getfixedmaxoption;
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'rsmaximumdiscountcart', 1);
                                wc_add_notice(__($errpercentagemsg1), 'error');
                            }
                        }
                    } else {
                        if ($getpercentmaxoption != '') {
                            $getpercent = $getpercentmaxoption;
                            $gettotalprice = $woocommerce->cart->subtotal_ex_tax;
                            $percentageproduct = $getpercent / 100;
                            $getpricepercent = $percentageproduct * $gettotalprice;
                            $getpointconvert = $getpricepercent * $point_control;
                            $getexactpoint = $getpointconvert / $point_control_price;
                            $errpercentagemsg = get_option('rs_errmsg_for_max_discount_type');
                            $errpercentagemsg1 = str_replace('[percentage]', $getpercent, $errpercentagemsg);
                            if ($point_to_redeem > $getexactpoint) {
                                $revised_amount = $getexactpoint / $point_control;
                                $newamount = $revised_amount * $point_control_price;
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'rsmaximumdiscountcart', 1);
                                wc_add_notice(__($errpercentagemsg1), 'error');
                            }
                        }
                    }

                    if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '1') {

                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $woocommerce->cart->subtotal);

                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $woocommerce->cart->cart_contents_count);
                        if (get_option('rs_redeem_field_type_option') == '2') {
                            if (isset($_POST['rs_apply_coupon_code1'])) {
                                $redeempercent = get_option('rs_percentage_cart_total_redeem');

                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $redeempercent);
                            }
                            if (isset($_POST['rs_apply_coupon_code'])) {
                                if ($newamount > $woocommerce->cart->subtotal_ex_tax) {
                                    $newamount = $woocommerce->cart->subtotal_ex_tax;
                                    WC()->session->set('fp_rs_redeem_amount', $newamount);

                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $newamount);
                                } else {
                                    WC()->session->set('fp_rs_redeem_amount', $newamount);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $newamount);
                                }
                            }
                        } else {
                            if ($newamount > $woocommerce->cart->subtotal_ex_tax) {
                                $newamount = $woocommerce->cart->subtotal_ex_tax;
                                WC()->session->set('fp_rs_redeem_amount', $newamount);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $newamount);
                            } else {
                                WC()->session->set('fp_rs_redeem_amount', $newamount);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $newamount);
                            }
                        }
                    } else {
                        $getsumofselectedproduct = self::get_sum_of_selected_products('sumo', '', '');

                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $getsumofselectedproduct);

                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $getsumofselectedproduct);
                        if (get_option('rs_redeem_field_type_option') == '2') {
                            if (isset($_POST['rs_apply_coupon_code1'])) {
                                $redeempercent = get_option('rs_percentage_cart_total_redeem');
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $redeempercent);
                            }
                            if (isset($_POST['rs_apply_coupon_code'])) {
                                $getsumofselectedproduct = $getsumofselectedproduct;
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $getsumofselectedproduct);
                            }
                        } else {
                            $getsumofselectedproduct = $getsumofselectedproduct;
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $getsumofselectedproduct);
                        }

                        WC()->session->set('fp_rs_redeem_amount', $getsumofselectedproduct);
                    }
                    if (get_option('rs_coupon_applied_individual') == 'yes') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'individual_use', 'yes');
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'individual_use', 'no');
                    }

//Redeeming only for Selected Products option start
                    $enableproductredeeming = get_option('rs_enable_redeem_for_selected_products');
                    if ($enableproductredeeming == 'yes') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'product_ids', implode(',', array_filter(array_map('intval', $allowproducts))));
                    }
                    $excludeproductredeeming = get_option('rs_exclude_products_for_redeeming');
                    if ($excludeproductredeeming == 'yes') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'exclude_product_ids', implode(',', array_filter(array_map('intval', $excludeproducts))));
                    }
                    $enablecategoryredeeming = get_option('rs_enable_redeem_for_selected_category');
                    if ($enablecategoryredeeming == 'yes') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'product_categories', implode(',', array_filter(array_map('intval', $allowcategory))));
                    }
                    $excludecategoryredeeming = get_option('rs_exclude_category_for_redeeming');
                    if ($excludecategoryredeeming == 'yes') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'exclude_product_categories', implode(',', array_filter(array_map('intval', $excludecategory))));
                    }


//Redeeming only for Selected Products option End



                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'usage_limit', '1');
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'expiry_date', '');
                    if (get_option('rs_apply_redeem_before_tax') == '1') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'apply_before_tax', 'no');
                    }
                    if (get_option('rs_apply_shipping_tax') == '1') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'free_shipping', 'yes');
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'free_shipping', 'no');
                    }

                    if ($woocommerce->cart->has_discount('sumo_' . strtolower($couponcodeuserlogin)))
                        return;
                    if (!$woocommerce->cart->has_discount('sumo_' . strtolower($couponcodeuserlogin))) {
                        $woocommerce->cart->add_discount('sumo_' . strtolower($couponcodeuserlogin));
                    }
                }
            }
        }
    }

    public static function get_sum_of_selected_products($coupontype, $user_current_points1, $user_current_points) {

        global $woocommerce;

        $includeproductid = get_option('rs_select_products_to_enable_redeeming');

        if (is_array($includeproductid)) {
            $include_productid = (array) $includeproductid; // Compatible for Old WooCommerce Version
        } else {
            $include_productid = (array) explode(',', $includeproductid); // Compatible with Latest Version
        }

        $excludeproductid = get_option('rs_exclude_products_to_enable_redeeming');
        if (is_array($excludeproductid)) {
            $exclude_productid = (array) $excludeproductid; // Compatible for Old WooCommerce Version
        } else {
            $exclude_productid = (array) explode(',', $excludeproductid); // Compatible with Latest Version
        }

        $includecategory = get_option('rs_select_category_to_enable_redeeming');
        if (is_array($includecategory)) {
            $include_category = (array) $includecategory; // Compatible for Old WooCommerce Version
        } else {
            $include_category = (array) explode(',', $includecategory); // Compatible with Latest Version
        }

        $excludecategory = get_option('rs_exclude_category_to_enable_redeeming');
        if (is_array($excludecategory)) {
            $exclude_category = (array) $excludecategory; // Compatible for Old WooCommerce Version
        } else {
            $exclude_category = (array) explode(',', $excludecategory); // Compatible with Latest Version
        }

        $cart_contents = $woocommerce->cart->cart_contents;
        $totalselectedvalue = array();

        foreach ($cart_contents as $key => $value) {

            $productid = $value['product_id'];
            $variationid = $value['variation_id'];
            $productcategorys = get_the_terms($productid, 'product_cat');
            $line_total = $coupontype == 'sumo' || $coupontype == 'auto' ? $value['line_subtotal'] : $value['line_subtotal'];

            /* Checking whether the Product has Category */
            if ($productcategorys != false) {
                $getcount = count($productcategorys);
                if ($getcount > '1') {
                    foreach ($productcategorys as $productcategory) {
                        $termid = $productcategory->term_id;
                        if (get_option('rs_enable_redeem_for_selected_category') == 'yes') {
                            if (get_option('rs_select_category_to_enable_redeeming') != '') {
                                if (in_array($termid, $include_category)) {
                                    $totalselectedvalue[$productid] = $line_total;
                                }
                            } else {
                                $totalselectedvalue[] = $woocommerce->cart->subtotal;
                            }
                        } elseif (get_option('rs_exclude_category_for_redeeming') == 'yes') {
                            if (get_option('rs_exclude_category_to_enable_redeeming') != '') {
                                if (in_array($termid, $exclude_category)) {
                                    $totalselectedvalue[$productid] = $line_total;
                                }
                            } else {
                                $totalselectedvalue[] = $woocommerce->cart->subtotal;
                            }
                        } else {
                            if ($coupontype == 'sumo' || $coupontype == 'auto') {
                                $totalselectedvalue[$productid] = $line_total;
                            }
                        }
                    }
                } else {
                    @$termid = $productcategorys[0]->term_id;
                    if (get_option('rs_enable_redeem_for_selected_category') == 'yes') {
                        if (get_option('rs_select_category_to_enable_redeeming') != '') {
                            if (in_array($termid, $include_category)) {
                                $totalselectedvalue[$productid] = $line_total;
                            }
                        } else {
                            $totalselectedvalue[] = $woocommerce->cart->subtotal;
                        }
                    } elseif (get_option('rs_exclude_category_for_redeeming') == 'yes') {
                        if (get_option('rs_exclude_category_to_enable_redeeming') != '') {
                            if (!in_array($termid, $exclude_category)) {
                                $totalselectedvalue[$productid] = $line_total;
                            }
                        } else {
                            $totalselectedvalue[] = $woocommerce->cart->subtotal;
                        }
                    } else {
                        if ($coupontype == 'sumo' || $coupontype == 'auto') {
                            $totalselectedvalue[$productid] = $line_total;
                        }
                    }
                }
            }

            if (get_option('rs_enable_redeem_for_selected_products') == 'yes') {
                if (get_option('rs_select_products_to_enable_redeeming') != '') {
                    if (in_array($variationid != '' || 0 ? $variationid : $productid, $include_productid)) {
                        $totalselectedvalue[] = $line_total;
                    }
                } else {
                    $totalselectedvalue[] = $woocommerce->cart->subtotal;
                }
            } elseif (get_option('rs_exclude_products_for_redeeming') == 'yes') {
                if (get_option('rs_exclude_products_to_enable_redeeming') != '') {
                    $product_id = $variationid != '' || 0 ? $variationid : $productid;
                    if (!in_array($product_id, $exclude_productid)) {
                        $totalselectedvalue[] = $line_total;
                    }
                } else {
                    $totalselectedvalue[] = $woocommerce->cart->subtotal;
                }
            } else {
                if ($coupontype == 'sumo' || $coupontype == 'auto') {
                    $totalselectedvalue[] = $line_total;
                }
            }
        }
        if ($coupontype == 'sumo') {
            if (isset($_POST['rs_apply_coupon_code_field'])) {
                $redeemingpoints = $_POST['rs_apply_coupon_code_field'];
                $point_control = get_option('rs_redeem_point');
                $point_control_price = get_option('rs_redeem_point_value'); //i.e., 100 Points is equal to $1
                $revised_amount = $redeemingpoints / $point_control;
                $newamount = $revised_amount * $point_control_price;

                if ($newamount < array_sum($totalselectedvalue)) {
                    return $newamount;
                } else {
                    return array_sum($totalselectedvalue);
                }
            }
        }

        if ($coupontype == 'auto') {

            $current_carttotal_amount = array_sum($totalselectedvalue);
            $redeem_conversion = get_option('rs_redeem_point');
            $current_carttotal_in_points = $current_carttotal_amount * $redeem_conversion;
            $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_auto_redeem');
            $updated_points_step1 = $current_carttotal_in_points / 100;
            $updated_points_for_redeeming = $updated_points_step1 * $limitation_percentage_for_redeeming;
            if ($user_current_points1 >= $updated_points_for_redeeming) {
                $points_currency_value = $updated_points_for_redeeming / $redeem_conversion;
                return $points_currency_value;
            } else {
                $points_for_redeeming = $user_current_points;
                $points_conversion_value = get_option('rs_redeem_point_value');
                $points_currency_value = $points_for_redeeming / $redeem_conversion;
                $points_currency_amount_to_replace = $points_currency_value * $points_conversion_value;
                $points_for_redeeming = $points_currency_amount_to_replace;
                return $points_for_redeeming;
            }
        }
    }

    /*
     * Function to delete the coupon after order placed
     */

    public static function remove_coupon_after_place_order($order_id, $order_post) {
        $order = new WC_Order($order_id);
        $getuserdata = get_user_by('id', $order->user_id);
        $getusername = isset($getuserdata->user_login) ? ($getuserdata->user_login) : '';
        $couponname = 'sumo_' . strtolower($getusername);

        $auto_redeem_name = 'auto_redeem_' . strtolower($couponname);
        foreach ($order->get_used_coupons() as $newcoupon) {
            if ($couponname == $newcoupon) {
                $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($order->user_id, 'redeemcouponids');
                wp_trash_post($getcouponid);
            }
            if ($auto_redeem_name == $newcoupon) {
                $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($order->user_id, 'auto_redeemcoupon_ids');
                wp_trash_post($getcouponid);
            }
        }

        $getoption = get_option('rs_enable_redeem_for_order');
        update_post_meta($order_id, 'rs_check_enable_option_for_redeeming', $getoption);
    }

    public static function update_auto_redeem_points($order_id, $orderuserid) {
        $order = new WC_Order($order_id);
        $rewardpointscoupons = $order->get_items(array('coupon'));
        $getuserdatabyid = get_user_by('id', $orderuserid);
        $getusernickname = $getuserdatabyid->user_login;

        $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
        foreach ($rewardpointscoupons as $couponcode => $value) {
            if ($auto_redeem_name == $value['name']) {

                if (get_option('rewardsystem_looped_over_coupon' . $order_id) != '1') {
                    $getuserdatabyid = get_user_by('id', $orderuserid);
                    $getusernickname = $getuserdatabyid->user_login;
                    $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($orderuserid, 'auto_redeemcoupon_ids');
                    $currentamount = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($getcouponid, 'coupon_amount');
                    if ($currentamount >= $value['discount_amount']) {
                        $current_conversion = get_option('rs_redeem_point');
                        $point_amount = get_option('rs_redeem_point_value');
                        $redeemedamount = $value['discount_amount'] * $current_conversion;
                        $redeemedpoints = $currentamount / $point_amount;
                        $fp_earned_points_sms = true;
                        if ($fp_earned_points_sms == true) {
                            if (get_option('rs_enable_send_sms_to_user') == 'yes') {
                                if (get_option('rs_send_sms_redeeming_points') == 'yes') {
                                    if (get_option('rs_sms_sending_api_option') == '1') {
                                        RSFunctionForSms::send_sms_twilio_api($order_id);
                                    } else {
                                        RSFunctionForSms::send_sms_nexmo_api($order_id);
                                    }
                                }
                            }
                        }
                    }
                    return $redeemedpoints;
                    update_option('rewardsystem_looped_over_coupon' . $order_id, '1');
                }
            }
        }
    }

    public static function update_redeem_reward_points_to_user($order_id, $orderuserid) {
// Inside Loop
        $order = new WC_Order($order_id);
        $rewardpointscoupons = $order->get_items(array('coupon'));
        $getuserdatabyid = get_user_by('id', $orderuserid);
        $getusernickname = $getuserdatabyid->user_login;
        $maincouponchecker = 'sumo_' . strtolower($getusernickname);
        foreach ($rewardpointscoupons as $couponcode => $value) {
            if ($maincouponchecker == $value['name']) {
                if (get_option('rewardsystem_looped_over_coupon' . $order_id) != '1') {
                    $getuserdatabyid = get_user_by('id', $orderuserid);
                    $getusernickname = $getuserdatabyid->user_login;
                    $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($orderuserid, 'redeemcouponids');
                    $currentamount = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($getcouponid, 'coupon_amount');
//if ($currentamount >= $value['discount_amount']) {
                    $current_conversion = get_option('rs_redeem_point');
                    $point_amount = get_option('rs_redeem_point_value');
                    $redeem_amount_value = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'fp_rs_redeemed_points_value');
                    $redeemedamount = $value['discount_amount'] * $current_conversion;
                    $redeemedpoints = $redeemedamount / $point_amount;
                    $fp_earned_points_sms = true;
                    if ($fp_earned_points_sms == true) {
                        if (get_option('rs_enable_send_sms_to_user') == 'yes') {
                            if (get_option('rs_send_sms_redeeming_points') == 'yes') {
                                if (get_option('rs_sms_sending_api_option') == '1') {
                                    RSFunctionForSms::send_sms_twilio_api($order_id);
                                } else {
                                    RSFunctionForSms::send_sms_nexmo_api($order_id);
                                }
                            }
                        }
                    }
//}
                    return $redeemedpoints;
                    update_option('rewardsystem_looped_over_coupon' . $order_id, '1');
                }
            }
        }
    }

    public static function update_coupon_value_after_product_removed() {
        global $woocommerce;
        $couponamt = $woocommerce->cart->get_cart_discount_total();
//  WC()->session->set('fp_rs_redeem_amount', $couponamt);
    }

    public static function get_coupon_code_data($msg, $msg_code, $object) {

        global $woocommerce;
        foreach ($woocommerce->cart->applied_coupons as $code) {
            $coupon = new WC_Coupon($code);
            $autocoupon = $coupon->code;
            $couponcode = $coupon->code;
            $user_ID = get_current_user_id();
            $getuserdatabyid = get_user_by('id', $user_ID);
            $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
            $maincouponchecker = 'sumo_' . strtolower($getusernickname);
            $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
            if ($couponcode == $maincouponchecker) {
                if ($msg_code == 104) {
                    $msg_code = 204;
                } else {
                    $msg_code = $msg_code;
                }
            }
            switch ($msg_code) {
                case 204 :
                    $msg = __(get_option('rs_coupon_applied_individual_error_msg'), 'rewardsystem');
                    break;
                default:
                    $msg = $msg;
                    break;
            }
        }
        return $msg;
    }

    public static function rs_error_msg_for_autoredeem($msg, $msg_code, $object) {
        $autoredeemenable = get_option('rs_enable_disable_auto_redeem_points');
        $user_ID = get_current_user_id();
        $getuserdatabyid = get_user_by('id', $user_ID);
        $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
        $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
        $coupon_obj = $object;
        $coupon_code = $coupon_obj->code;
        if ($coupon_code == $auto_redeem_name) {
            switch ($msg_code) {
                case 109 :
                    $msg = __('Auto Redeem is not applicable to your cart contents.', 'rewardsystem');
                    break;
                case 113 :
                    $msg = __('Auto Redeem is not applicable to your cart contents.', 'rewardsystem');
                    break;
                case 101 :
                    $msg = __('Auto Redeem is not applicable to your cart contents.', 'rewardsystem');
                    break;
                default:
                    $msg = $msg;
                    break;
            }
        }
        return $msg;
    }

    public static function get_coupon_code_data_sucess($msg, $msg_code) {
        global $woocommerce;
        foreach ($woocommerce->cart->applied_coupons as $code) {
            $coupon = new WC_Coupon($code);
            $autocoupon = $coupon->code;
            $couponcode = $coupon->coupon_code;
            $user_ID = get_current_user_id();
            $getuserdatabyid = get_user_by('id', $user_ID);
            $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
            $maincouponchecker = 'sumo_' . strtolower($getusernickname);
            $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
            if ($auto_redeem_name == $autocoupon) {
                if (count($woocommerce->cart->get_coupons()) == 1) {
                    if ($msg_code == 200) {
                        $msg_code = 501;
                    }
                }
            }
            switch ($msg_code) {
                case 501:
                    $msg = __('Auto' . get_option('rs_success_coupon_message'), 'rewardsystem');
                    break;
                case 200 :
                    if (isset($_POST['rs_apply_coupon_code'])) {
                        $msg = __(get_option('rs_success_coupon_message'), 'rewardsystem');
                        ?>
                        <?php

                        if (get_option('rs_redeem_field_type_option') == '2') {
                            ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    jQuery("#mainsubmi").parent().hide();
                                });</script>
                            <?php

                        }
                    }
                    break;
                default:
                    $msg = '';
                    break;
            }
        }
        return $msg;
    }

}

new RSFunctionToApplyCoupon();
