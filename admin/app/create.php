<?php
require_once "../config.php";
$result = "";
if (
        empty($_POST['file_name']) || 

        // API Settings
        empty($_POST['PAYMENT_URL']) ||
        empty($_POST['CORP_ID']) ||
        empty($_POST['API_USERNAME']) ||
        empty($_POST['API_PASSWORD']) ||
        empty($_POST['AGENT_ID']) ||
        empty($_POST['PAYMENT_TYPE']) ||
        empty($_POST['PRODUCT_CODE']) ||
        empty($_POST['PAYMENT_PROCESS']) ||
        empty($_POST['SOURCE']) ||

        // Plan Settings
        empty($_POST['plan_name']) ||
        empty($_POST['plan_type']) ||
        empty($_POST['plan_type']) ||
        empty($_POST['thank_you_page_url'])
        
    ) {
    $result = "Fillup All Data";
}
else{

    $CouponData  = array();

    $file_name = $_POST['file_name'];

    // API Settings
    $PAYMENT_URL = $_POST['PAYMENT_URL'];
    $CORP_ID = $_POST['CORP_ID'];
    $API_USERNAME = $_POST['API_USERNAME'];
    $API_PASSWORD = $_POST['API_PASSWORD'];
    $AGENT_ID = $_POST['AGENT_ID'];
    $PAYMENT_TYPE = $_POST['PAYMENT_TYPE'];
    $PRODUCT_CODE = $_POST['PRODUCT_CODE'];
    $PAYMENT_PROCESS = $_POST['PAYMENT_PROCESS'];
    $SOURCE = $_POST['SOURCE'];

    // Plan Settings
    $plan_name = $_POST['plan_name'];
    $plan_type = $_POST['plan_type'];
    $Yearly_payment_special = $_POST['Yearly_payment_special'];
    $thank_you_page_url = $_POST['thank_you_page_url'];
    $Enrollment_Fee = $_POST['Enrollment_Fee'];
    $Yearly_Payment_Fee = $_POST['Yearly_Payment_Fee'];
    $Monthly_Payment_Fee = $_POST['Monthly_Payment_Fee'];

    if(empty($_POST['CouponData'])){
        $CouponData = NULL;
    }
    else{
        $CouponData = $_POST['CouponData'];
    }
    
    //$CouponData = explode(',', $CouponData);

    //echo $_POST['CouponData'][0]['Coupon_code'];
    $sql = "SELECT file_name from checkout_file where file_name='$file_name'";
    if ($query =  mysqli_query($conn,$sql)){
        $rowcount=mysqli_num_rows($query);
        if($rowcount>0){
            $result = "File Name Not Abailable";
            mysqli_free_result($query);
        }
        else{
             $sql = "INSERT INTO 
                    `checkout_file`(
                        file_name,

                        PAYMENT_URL,
                        CORP_ID,
                        API_USERNAME,
                        API_PASSWORD,
                        AGENT_ID,
                        PAYMENT_TYPE,
                        PRODUCT_CODE,
                        PAYMENT_PROCESS,
                        SOURCE,

                        plan_name,
                        plan_type,
                        Yearly_payment_special,
                        thank_you_page_url,
                        Enrollment_Fee,
                        Yearly_Payment_Fee,
                        Monthly_Payment_Fee
                        ) 
                    VALUES (
                        '$file_name',

                        '$PAYMENT_URL',
                        '$CORP_ID',
                        '$API_USERNAME',
                        '$API_PASSWORD',
                        '$AGENT_ID',
                        '$PAYMENT_TYPE',
                        '$PRODUCT_CODE',
                        '$PAYMENT_PROCESS',
                        '$SOURCE',

                        '$plan_name',
                        '$plan_type', 
                        '$Yearly_payment_special', 
                        '$thank_you_page_url', 
                        '$Enrollment_Fee', 
                        '$Yearly_Payment_Fee', 
                        '$Monthly_Payment_Fee'
                        )";
             
             if(mysqli_query($conn,$sql) )
             {
                 $flag = 1;
                $last_id = mysqli_insert_id($conn);

                if($CouponData!=NULL){
                    foreach($CouponData as $coupon){
                        $Coupon_code = $coupon["Coupon_code"];
                        $coupon_value = $coupon["Coupon_value"];
                        $coupon_type = $coupon["Coupon_type"];
                        $coupon_message = $coupon["coupon_message"];
                        $sql1 = "INSERT INTO 
                        coupons(
                            coupon_code,
                            coupon_value,
                            coupon_type,
                            coupon_message,
                            checkout_file_id
                            ) 
                        VALUES (
                            '$Coupon_code',
                            '$coupon_value',
                            '$coupon_type',
                            '$coupon_message',
                            '$last_id'
                            )";
                        if(!mysqli_query($conn,$sql1))
                        {
                            //echo "not Inserted";
                            $flag = 0;
                        }
                    }
                }
                
                
                if($flag != 1){
                    $result = "ERROR: Could not able to execute . " . mysqli_error($conn);
                }
                else{
                    require_once "create-checkout.php";
                    $result = createCheckout(
                        $file_name,

                        $PAYMENT_URL,
                        $CORP_ID,
                        $API_USERNAME,
                        $API_PASSWORD,
                        $AGENT_ID,
                        $PAYMENT_TYPE,
                        $PRODUCT_CODE,
                        $PAYMENT_PROCESS,
                        $SOURCE,

                        $plan_name,
                        $plan_type, 
                        $Yearly_payment_special, 
                        $thank_you_page_url, 
                        $Enrollment_Fee, 
                        $Yearly_Payment_Fee, 
                        $Monthly_Payment_Fee,
                        $CouponData
                    );
                }
                
                //  require_once "create-checkout.php";
                //  $result = createCheckout($file_name, $plan_type, $Yearly_Payment_Fee, $Monthly_Payment_Fee, $Yearly_payment_special, $Enrollment_Fee, $Promo_type, $Priority, $Promo_Factor, $source);
             }
                 else{
                 $result ="ERROR: Could not able to execute . " . mysqli_error($conn);
             }
         }
    }
    
}
echo $result;
?>