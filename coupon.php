<?php
if($_GET['action'] =="get_coupon_value"){
        get_coupon_value();
    } 
function get_coupon_value(){
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }
        $couponCount = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->where('id', $_POST['restaurant'])
            ->find_one();
        $couponCount = ORM::for_table($config['db']['pre'] . 'discount')
        ->where('code',$_POST['promo_code'])
        ->count();
        $total_amount = 0;
        if($couponCount == 0){
            echo json_encode(['status'=>false, 'message' => 'This coupon does not exists!']);
            // return redirect()->back()->with('flash_message_error','This coupon does not exists!');
        }else{
        // Get Coupon Details
        $couponDetails = ORM::for_table($config['db']['pre'] . 'discount')
        ->where('code',$_POST['promo_code'])
        ->find_one(); 
        
        
        // If coupon is Expired
        $expiry_date = $couponDetails->expirary;
        $current_date = date('Y-m-d');
        if($expiry_date < $current_date){
            echo json_encode(['status'=>false, 'message' => 'This coupon is expired!']);
        }
        if(!empty($couponDetails->dishes)){
            $catArr = explode(',', $couponDetails->dishes);
        }
        $items = json_decode($_POST['items'], true);
        foreach ($items as $item) {
            $item_id = $item['id'];
            $quantity = $item['quantity'];

            $menu = ORM::for_table($config['db']['pre'] . 'menu')
                ->where('id', $item_id)
                ->find_one();

            if(!empty($couponDetails->dishes)){
                if(!in_array($menu['id'], $catArr)){
                    echo json_encode(['status'=>false, 'message' => 'This coupon is not one of the selected products!']);
                }
            }
            $total_amount += $menu['price'] * $quantity;
                
        }
        if($couponDetails->type == 'fixed'){
            $couponAmount = $couponDetails->value;
        }else{
            
            $couponAmount = $total_amount * ( $couponDetails->value/100);
        }
        $grand_total = $total_amount - $couponAmount;
        echo json_encode(['status' => 'true', 'grand total'=>$grand_total, 'total_amount' => $total_amount, 'couponAmount'=>$couponAmount]);
    }
}

 ?>