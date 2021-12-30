
    <?php
            $URL = "testfile.php";
            $PAYMENT_URL ="gesdfdf";
            $CORP_ID ="435";
            $API_USERNAME ="43545";
            $API_PASSWORD ="345453";
            $AGENT_ID ="435543";
            $PAYMENT_TYPE ="345345";
            $PRODUCT_CODE ="345453";
            $PAYMENT_PROCESS ="3445dsgg";
            $SOURCE ="4bdgfbgf";

            $plan_name = "test"; 
            $plan_type = "Family"; 
            $Yearly_payment_special = "dvfbd"; 
            $thank_you_page_url = "dfbbdfg"; 
            $product_price_yearly = "169";
            $product_price_monthly = "14";
            $enrolment_fee =20;

            $coupons = '{"0":{"Coupon_code":"test","Coupon_value":"10","Coupon_type":"Flat","coupon_message":"$10 discount"}}';
            $couponsData = json_decode($coupons, true);

            $coupon_discount = 0;
            $coupon_discount_f = 0;
            $coupon_status_f = false;
            $PAYMENT_AMOUNT = NULL;
            $coupon_type_f = NULL;
            $coupon_value_f = null;
            $product_price_f = null;
            $enrolment_fee_f = (double)$enrolment_fee;
            $product_fee_time =  null;

            function toFixed($number, $decimals) {
                return number_format($number, $decimals, '.', "");
            }
              
            /* Function to Calculate Discount */
            function calculateDiscount(){
                if($GLOBALS["coupon_status_f"]==TRUE){
                    if($GLOBALS["coupon_type_f"] == "Percentage"){ //toFixed($a, 3);
                        $GLOBALS["coupon_discount_f"] = toFixed((((((double)toFixed($GLOBALS["product_price_f"], 2) + (double)toFixed($GLOBALS["enrolment_fee_f"], 2))/100)*(double)toFixed($GLOBALS["coupon_value_f"], 2))),2);
                    }
                    else{
                        $GLOBALS["coupon_discount_f"] = toFixed((double)$GLOBALS["coupon_value_f"],2);
                    }
                }
            }

            /* Function to Calculate final Payment */
            function calculateFinalPayment(){
                calculateDiscount();
                $GLOBALS["PAYMENT_AMOUNT"] = toFixed((((double)toFixed($GLOBALS["product_price_f"],2) + (double)toFixed($GLOBALS["enrolment_fee_f"],2)) - (double)toFixed($GLOBALS["coupon_discount_f"], 2)), 2);
            }

            /* Function to send User checking out mail to admin */
            function sendInfoMail($toEmail, $data)
            {
                $subject = "DOCWELLBEE.COM: User checking out";
            
                $message = "Hello,<br>";
                $message .= "A user tried to checkout just now.<br><br>";
                $message .= "<strong>User info:</strong><br>";
                $message .= "Name: " . $data["FirstName"] . " " . $data["LastName"] . "<br>";
                $message .= "Phone: " . $data["Phone"] . "<br>";
                $message .= "Email: " . $data["Email"] . "<br>";
                $message .= "<br><br>----------<br>";
                $message .= "docwellbe.com";
            
                /* Always set content-type when sending HTML email */
                $headers = "MIME-Version: 1.0" . "\r";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r";
            
                /* More headers */
                $headers .= "From: <checkout@docwellbee.com>" . "\r";
                /* $doc .=$message; */
                // mail($toEmail, $subject, $message, $headers);
            }
           
            /* Function to submit payment */
            function curlPost($url, $data = null, $headers = null)
            {
                /*$ch = curl_init($url); */
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
                if (!empty($data)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
            
                if (!empty($headers)) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
           
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);
           
                $paymentError = null;
           
                if ($error || $error !== "") {
                    $paymentError = $error;
                }
           
                return [
                "response" => $response,
                "error" => $paymentError,
                ];
            }
           
            function getDefaults($field = false)
            {
                $PRODUCT_SUB_CODE = (($GLOBALS["plan_type"]=="Individual")?16:21);
                $PRODUCT_PERIOD = null;
                if($GLOBALS["product_fee_time"] == "Monthly")
                {
                    $PRODUCT_PERIOD = 1;
                    $GLOBALS["product_price_f"] = $GLOBALS["product_price_monthly"]; /*To be seted */
                }
                else
                {
                    $PRODUCT_PERIOD = 5;
                    $GLOBALS["product_price_f"] =  $GLOBALS["product_price_yearly"]; /*To be seted */
                }


                $defaults = [
                    "PAYMENT_URL" => $GLOBALS["PAYMENT_URL"],
                    "API_USERNAME" => $GLOBALS["API_USERNAME"],
                    "API_PASSWORD" => $GLOBALS["API_PASSWORD"],
                    "CORP_ID" => $GLOBALS["CORP_ID"],
                    "AGENT_ID" => $GLOBALS["AGENT_ID"],
                    /*"COMPANY" => "Not mentioned", */
                    "COMPANY" => "",
                    "PAYMENT_TYPE" => $GLOBALS["PAYMENT_TYPE"],
                    "PAYMENT_AMOUNT" => $GLOBALS["PAYMENT_AMOUNT"],
                    "PRODUCT_CODE" => $GLOBALS["PRODUCT_CODE"],
                    /*"PRODUCT_SUBCODE" => "Individual plan: 16 and Family Plan: 21" */
                    "PRODUCT_SUBCODE" => $PRODUCT_SUB_CODE,
                    "PRODUCT_PERIOD" => $PRODUCT_PERIOD, /* 5= per year, 1= per month */
                    "PAYMENT_PROCESS" => $GLOBALS["PAYMENT_PROCESS"],
                    "ENROLLMENTFEE" => $GLOBALS["enrolment_fee"],
                    "EMAIL_TO" => "elalami@outlook.com",
                    "SOURCE" => $GLOBALS["SOURCE"],
                ];
           
                return ($field) ? $defaults[$field] : $defaults;
            }
           
            function proceedPayment($postData)
            {
                $defaults = getDefaults();
           
                $curlPostData = [
                    "API_USERNAME" => $defaults["API_USERNAME"],
                    "API_PASSWORD" => $defaults["API_PASSWORD"],
                    "CORP_ID" => $defaults["CORP_ID"],
                    "AGENT_ID" => $defaults["AGENT_ID"],
                    "COMPANY" => $defaults["COMPANY"],
                    "FIRST_NAME" => $postData["FIRST_NAME"],
                    "LAST_NAME" => $postData["LAST_NAME"],
                    "ADDRESS_1" => $postData["PAYMENT_ADDRESS"],
                    "CITY" => $postData["PAYMENT_CITY"],
                    "STATE" => $postData["PAYMENT_STATE"],
                    "ZIP_CODE" => $postData["PAYMENT_ZIP_CODE"],
                    "DAYPHONE" => $postData["DAYPHONE"],
                    "EMAIL" => $postData["EMAIL"],
                    "PAYMENT_TYPE" => $defaults["PAYMENT_TYPE"],
                    "PAYMENT_AMOUNT" => $GLOBALS["PAYMENT_AMOUNT"],
                    "PRODUCT_CODE" => $defaults["PRODUCT_CODE"],
                    "PRODUCT_SUBCODE" => $defaults["PRODUCT_SUBCODE"],
                    "PRODUCT_PERIOD" => $defaults["PRODUCT_PERIOD"],
                    "PAYMENT_PROCESS" => $defaults["PAYMENT_PROCESS"],
                    "CC_NUMBER" => $postData["CC_NUMBER"],
                    "CC_EXP_MONTH" => $postData["CC_EXP_MONTH"],
                    "CC_EXP_YEAR" => $postData["CC_EXP_YEAR"],
                    "CC_CVV2" => $postData["CC_CVV2"],
                    "PAYMENT_FIRST_NAME" => $postData["PAYMENT_FIRST_NAME"],
                    "PAYMENT_LAST_NAME" => $postData["PAYMENT_LAST_NAME"],
                    "PAYMENT_ZIP_CODE" => $postData["PAYMENT_ZIP_CODE"],
                    "SOURCE" => $postData["SOURCE"],
                    "DOB" => $postData["DOB"],
                    "GENDER" => $postData["GENDER"],
                ];
           
                $response_arr = curlPost($defaults["PAYMENT_URL"], $curlPostData);
           
                $response = $response_arr["response"];
                $payment_error = $response_arr["error"];
                $member_id = 0;
           
                if ($payment_error == null && $response !== "" && $response !== null && strpos($response, "|") !== false) 
                {
                    $response = explode("|", $response);
                    if ($response[0] == "1") 
                    {
                        /*$member_id = $response[1]; */
                        if (!isset($response[1])) 
                        {
                            $response[1] = "NILL";
                        }
           
                        if (!isset($response[2])) 
                        {
                            $response[2] = "NILL";
                        }
           
                        if (!isset($response[3])) {
                            $response[3] = "NILL";
                        }
           
                        if (!isset($response[4])) {
                            $response[4] = "NILL";
                        }
           
                        $member_id = $response[1] . "_" . $response[2] . "_" . $response[3] . "_" . $response[4];
                    }
                    else
                    {
                        $payment_error = $response[1];
                    }
                }
           
                return [
                "payment_error" => $payment_error,
                "member_id" => $member_id,
                ];
            }
           
            /* Coupon validation */
            if(isset($_GET["coupon_code"]))
            {
                $coupon_stat = false;
                $user_coupon = $_GET["coupon_code"];
                $message = null;
                foreach($couponsData as $couponData){
                    if($couponData["Coupon_code"] == $user_coupon)
                    {
                        $coupon_status_f = TRUE;
                        $coupon_value_f = (double)$couponData["Coupon_value"];
                        $coupon_type_f = $couponData["Coupon_type"];
                        $message = $couponData["coupon_message"];
                        $coupon_stat = true;
                        
                    }
                }
                if($coupon_stat){
                    $product_price_f = $product_price_monthly; 
                    calculateFinalPayment();
                    $discount_monthly = $coupon_discount_f;

                    $product_price_f = $product_price_yearly; 
                    calculateFinalPayment();
                    $discount_yearly = $coupon_discount_f;

                    $discount = array("discount_monthly" => $discount_monthly, "discount_yearly" => $discount_yearly, "message" => $message, "coponStatus" => "ok");
                    $data = json_encode($discount);
                    echo $data;
                    return;
                }
                $discount = array("coponStatus" => "notOk");
                $data = json_encode($discount);
                echo $data;
                return;
            }

            /* Form Submmission */
            else if(isset($_POST["submit"]))
            {
                if(
                    !empty( $_POST["FIRST_NAME"])
                    && !empty( $_POST["LAST_NAME"])
                    && !empty( $_POST["DAYPHONE"])
                    && !empty( $_POST["EMAIL"])
                    && !empty( $_POST["DOB"])
                    && !empty( $_POST["GENDER"])
                    && !empty( $_POST["PAYMENT_ADDRESS"])
                    && !empty( $_POST["PAYMENT_CITY"])
                    && !empty( $_POST["PAYMENT_ZIP_CODE"])
                    && !empty( $_POST["PAYMENT_STATE"])
                    && !empty( $_POST["PAYMENT_FIRST_NAME"])
                    && !empty( $_POST["PAYMENT_LAST_NAME"])
                    && !empty( $_POST["CC_NUMBER"])
                    && !empty( $_POST["CC_EXP_MONTH"])
                    && !empty( $_POST["CC_EXP_YEAR"])
                    && !empty( $_POST["CC_CVV2"])
                    && !empty( $_POST["PRODUCT_FEE"])
                ){
                    $postData = array(
                        
                        "FIRST_NAME" => $_POST["FIRST_NAME"],
                        "LAST_NAME" => $_POST["LAST_NAME"],
                        "DAYPHONE" => $_POST["DAYPHONE"],
                        "EMAIL" => $_POST["EMAIL"],
                        "DOB" => $_POST["DOB"],
                        "GENDER" => $_POST["GENDER"],
                        "PAYMENT_ADDRESS" => $_POST["PAYMENT_ADDRESS"],
                        "PAYMENT_CITY" => $_POST["PAYMENT_CITY"],
                        "PAYMENT_ZIP_CODE" => $_POST["PAYMENT_ZIP_CODE"],
                        "PAYMENT_STATE" => $_POST["PAYMENT_STATE"],
                        "PAYMENT_FIRST_NAME" => $_POST["PAYMENT_FIRST_NAME"],
                        "PAYMENT_LAST_NAME" => $_POST["PAYMENT_LAST_NAME"],
                        "CC_NUMBER" => $_POST["CC_NUMBER"],
                        "CC_EXP_MONTH" => $_POST["CC_EXP_MONTH"],
                        "CC_EXP_YEAR" => $_POST["CC_EXP_YEAR"],
                        "CC_CVV2" => $_POST["CC_CVV2"],
                        "PRODUCT_FEE" => $_POST["PRODUCT_FEE"],
                        "coupon_code" => $_POST["coupon_code"],
                        "SOURCE" => $_POST["SOURCE"],
                    );

                    if($postData["coupon_code"]){
                        $user_coupon = $postData["coupon_code"];
                        $flag = 0;
                        foreach($couponsData as $couponData){
                            if($couponData["Coupon_code"] == $user_coupon)
                            {
                                $coupon_status_f = TRUE;
                                $flag = 1;
                                $coupon_value_f = (double)$couponData["Coupon_value"];
                                $coupon_type_f = $couponData["Coupon_type"];
                                break;
                            }
                        }
                        if($flag != 1){
                            $coupon_status_f = false;
                        }
                    }
                    
                    $product_fee_time = $postData["PRODUCT_FEE"];
                    // $product_price_f = (double)$postData["PRODUCT_FEE"];
                    $product_price_f = ($postData["PRODUCT_FEE"]=="Monthly")?$product_price_monthly:$product_price_yearly;
                    calculateFinalPayment();

                    // $result = array("coupon_status_f" => $coupon_status_f, "coupon_type_f" => $coupon_type_f, "product_price_f" => $product_price_f,"EnrolmentFee"=>$enrolment_fee_f, "coupon_discount_f" => $coupon_discount_f, "PaymentAmount" => $PAYMENT_AMOUNT);
                    // $result = json_encode($result);
                    // echo $result;
                    // return;

                    /* $defaults = getDefaults();
                    /* foreach($defaults as $key => $value){
                    /*     echo $doc .=$key . "=>" . $value ."<br>";
                    /* }
           
                    /* Call Send Mail Function */
                    $EmailTo = getDefaults("EMAIL_TO");
                    sendInfoMail($EmailTo, [
                        "FirstName" => $postData["FIRST_NAME"],
                        "LastName" => $postData["LAST_NAME"],
                        "Phone" => $postData["DAYPHONE"],
                        "Email" => $postData["EMAIL"],
                    ]);
           
                    
                    /* Call ProceedPayment Function */
                    $response = proceedPayment($postData);
           

                    if ($response["payment_error"] !== null && $response["payment_error"] !== "") 
                    {
                        $result = array("PaymentStatus" => "False", "URL"=> "");
                        // $result = array("product_price_f" => $product_price_f,"EnrolmentFee"=>$enrolment_fee_f, "coupon_discount_f" => $coupon_discount_f, "PaymentAmount" => $PAYMENT_AMOUNT);
                        $result = json_encode($result);
                        echo $result;
                        return;
                    } 
                    else 
                    {
                        //$URL = "https://dndmedicalservices.com/index.php/thank-you?result=1&msg=" . $response["member_id"];
                        $result = array("PaymentStatus" => "True", "URL"=> $thank_you_page_url);
                        $result = json_encode($result);
                        echo $result;
                        return;
           
                        /*die("PAYMENT SUCCESS member id: " . $response["member_id"]);*/
                    
                        /*$URL = "http:/*dev.sites/docwellbe/pre-checkout.php?payment_success=" . $response["member_id"];
                        /* $URL = "https://dndmedicalservices.com/index.php/thank-you?result=1&msg=" . $response["member_id"];
                        /* echo "<script type="text/javascript">document.location.href="{$URL}";</script>";
                        /* echo "<META HTTP-EQUIV="refresh" content="0;URL=" . $URL . "">";
                    
                        /*header("location: docwellbee.com/lp/thankyou?result=1&msg=" . $response["member_id"]);
                        /*header("Location: http:/*dev.sites/docwellbe/pre-checkout.php?payment_success=" . $response["member_id"]); */
                    }
                }
            }
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tag -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/docwellbee-logo-web 1.png" type="image/x-icon">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <style>
    html {
        margin: 0;
        padding: 0;
    }

    body {
        background-color: #E5E5E5;
        margin: 0;
        padding: 0;
        font-family: "Montserrat", sans-serif;
    }

    .nav-custom {
        /* Height: 80px; */
        background: #FFFFFF;
    }

    .bg-custom {
        background-color: #EEEEEE;
    }

    .call-btn-custom {
        background: #8CC63F;
        border-radius: 71px;
        height: 50px;
        width: 180px;
    }

    .right-sided-logo {
        height: 55px;
    }

    .star-fill {
        color: #FA7800;
    }

    .review-text {
        font-family: Open Sans;
        font-style: normal;
        font-weight: normal;
        font-size: 11px;
        line-height: 14px;
        text-align: center;
        text-transform: capitalize;
    }

    .checkout-box {
        background-color: #FFFFFF;
        box-shadow: 0px 4px 22px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
    }

    .checkout-header {
        font-family: Open Sans;
        font-style: normal;
        font-weight: 800;
        font-size: 20px;
        line-height: 27px;
        text-transform: capitalize;
        color: #33AAA9;
    }

    .checkout-field-label {
        font-family: Open Sans;
        font-style: normal;
        font-weight: normal;
        font-size: 15px;
        line-height: 28px;
        /* identical to box height, or 187% */
        text-transform: capitalize;
        color: #5B6D81;
    }

    .cart-bottom-text {
        font-family: Open Sans;
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 20px;
        text-transform: capitalize;
        color: #5B6D81;
    }

    .payment-image {
        width: 252px;
        height: 29px;
    }

    .submit-button {
        background: #FC6600;
        border-radius: 71px;
    }

    .footer-text {
        font-family: Open Sans;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        line-height: 19px;
        /* identical to box height */
        text-transform: capitalize;
        color: #343434;
        opacity: 0.6;
    }

    .checkout-summary {
        background: #212F3E;
        border-radius: 10px;
    }

    .plan-price {
        background: #445363;
        border-radius: 10px;
    }

    .plan-cost {
        background: #1B2B3C;
    }

    .refund-notice {
        font-family: Open Sans;
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 20px;
        /* or 167% */
        text-align: right;
        text-transform: capitalize;
        color: #5B6D81;
    }

    .plan-benefits {
        background: rgba(255, 255, 255, 0.4);
        border-radius: 10px;
    }

    .custom-bg-check {
        color: #5B6D81;
    }
    </style>
    <title>Checkout</title>
</head>

<body class="overflow-auto bg-custom p-0 m-0">
    <!-- HEADER START-->
    <header class="m-0 p-0 nav-custom">
        <!-- Navbar -->
        <nav class="container">
            <div class="row d-flex align-items-center justify-content-start">
                <div class="col-6 my-2"> <img class="img-fluid" src="images/docwellbee-logo-web 1.png" alt="" srcset="">
                </div>
                <div class="d-none col-6 mt-2 d-md-flex align-items-center">
                    <div class="right-sided-logo d-md-flex align-items-center">
                        <div class="me-2"> <img class="img-fluid" src="images/Google-Logo 1.png" alt="" srcset="">
                        </div>
                    </div>
                    <div class="right-sided-logo mt-1 me-2 d-flex align-items-center">
                        <div>
                            <div class=""> <i class="fas fa-star star-fill"></i> <i class="fas fa-star star-fill"></i>
                                <i class="fas fa-star star-fill"></i> <i class="fas fa-star star-fill"></i> <i
                                    class="fas fa-star-half-alt star-fill"></i>
                            </div>
                            <div class="review-text"> 4.6 Stars in 28 Reviews </div>
                        </div>
                    </div>
                    <div class="right-sided-logo mt-2 me-2 d-flex align-items-center">
                        <div class=""> <img src="images/30-Day money Back Guarantee.png" alt="" srcset=""> </div>
                    </div>
                    <a href="tel:(877) 333-6121"
                        class="text-decoration-none right-sided-logo text-white fs-3 d-flex align-items-center">
                        <div class="call-btn-custom p-1 d-flex align-items-center justify-content-center">
                            <div class="mx-1">
                                <i class="fas fa-phone-alt "></i>
                            </div>
                            <div class="mx-1">
                                <h6 class="m-0 p-0">JOIN NOW</h6>
                                <h6 class="m-0 p-0">(877) 333-6121</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 d-block d-md-none">
                    <a href="tel:(877) 333-6121"
                        class="text-decoration-none right-sided-logo text-white fs-6 d-flex align-items-center">
                        <div class="call-btn-custom p-0 d-flex align-items-center justify-content-center">
                            <div class="mx-1 fs-3">
                                <i class="fas fa-phone-alt "></i>
                            </div>
                            <div class="m-0">
                                <h6 class="m-0 p-0">JOIN NOW</h6>
                                <h6 class="m-0 p-0">(877) 333-6121</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </nav>
        <!-- Navbar -->
    </header>
    <!-- HEADER END-->
    <!-- MAIN SECTION START -->
    <main class="container main-custom mt-3">
        <h2 class="text-dark">Checkout</h2>
        <div class="row">
            <div class="col-12 col-md-8 mb-4">
                <form action="" method="POST">
                <input type="hidden" name="SOURCE" id="SOURCE" value="<?php echo $SOURCE; ?>">
                    <div class="border-0 checkout-box p-2 p-md-5"> 
                        <h3 class="checkout-header fs-3"><?php echo $plan_name; ?></h3>
                        <h5>Plan Type: “<?php echo $plan_type; ?> <span id="main_plan_type1"></span> Plan”
                        </h5>
                        <!-- Payment piriod -->
                        <div class="fs-6">
                            <h5 class="checkout-field-label">Select a billing option:</h5>
                            <div class="form-check border-0 mb-2 rounded">
                                <div class="ms-2 d-flex align-items-start"> <input class="form-check-input" type="radio"
                                        name="PRODUCT_FEE" value="Monthly" id="PRODUCT_FEE_Field_1"> <label
                                        class="form-check-label ms-2" for="PRODUCT_FEE_Field_1">
                                        Monthly Payment $<?php echo $product_price_monthly; ?>/Month + $<?php echo $enrolment_fee; ?> Enrollment fee
                                    </label> </div>
                            </div>
                            <div class="form-check border-0 rounded">
                                <div class="ms-2 d-flex align-items-start"> <input class="form-check-input" type="radio"
                                        name="PRODUCT_FEE" value="Yearly" id="PRODUCT_FEE_Field_2"> <label
                                        class="form-check-label p-0 ms-2" for="PRODUCT_FEE_Field_2">
                                        <p class="m-0">Yearly Payment $<?php echo $product_price_yearly; ?>/Year + $<?php echo $enrolment_fee; ?> Enrollment fee
                                            <br>
                                            <span class="text-danger">
                                                <?php echo $Yearly_payment_special; ?>
                                            </span>
                                        </p>
                                    </label> </div>
                            </div>
                        </div>
                        <!-- Plan Cost -->
                        <div class="plan-cost p-3 rounded mt-2 text-white">
                            <div class="row">
                                <div class="col-8">
                                    <h5>Plan Cost</h5>
                                </div>
                                <div class="col-4">$<span id="plan_cost"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-8">Enrollment Fee</div>
                                <div class="col-4">$<span id="enrollment_fee">0</span></div>
                            </div>
                            <div class="row">
                                <div class="col-8">Discount <span id="Coupon_message" class="text-danger d-none"> </span><button id="remove_coupon" class="btn btn-sm p-0 text-danger px-2 d-none">X</button></div>
                                <div class="col-4">$<span id="coupon">0</span></div>
                            </div>
                            <div class="row fw-bold">
                                <div class="col-8">
                                    <h6> Today’s Total Payment: </h6>
                                </div>
                                <div class="col-4">$<span id="total_payment">0</span></div>
                            </div>
                        </div>
                        <!-- Coupon -->
                        <div class="row mt-3 d-flex justify-content-end">
                            <div class="col-6 col-md-4"> <input id="coupon_code" name="coupon" class="form-control" type="text">
                            </div>
                            <button id="coupon_btn"
                                    class="col-4 col-md-2 me-3 btn btn-sm btn-warning rounded rounded-3 px-0 px-md-2 py-2">Apply Coupon</button> </div>
                        
                        <!-- Contact Information -->
                        <div class="mt-5">
                            <h5 class="checkout-header">Contact Information</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3"> <label for="FIRST_NAME"
                                            class="checkout-field-label form-label">First
                                            Name*</label> <input type="text" class="form-control" id="FIRST_NAME"
                                            name="FIRST_NAME" placeholder="" required> </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3"> <label for="LAST_NAME"
                                            class="checkout-field-label form-label">Last
                                            Name*</label> <input type="text" class="form-control" id="LAST_NAME"
                                            name="LAST_NAME" placeholder="" required> </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3"> <label for="DAYPHONE"
                                            class="checkout-field-label form-label">Phone*</label> <input type="tel"
                                            class="form-control" id="DAYPHONE" name="DAYPHONE" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3"> <label for="EMAIL"
                                            class="form-label checkout-field-label">Email*</label> <input type="email"
                                            class="form-control" id="EMAIL" name="EMAIL" placeholder="" required> </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3"> <label for="DOB" class="checkout-field-label form-label">Date of
                                    Birth*</label> <input type="text" class="form-control" id="DOB"
                                    name="DOB" placeholder="dd/mm/yyyy" required> </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3"> <label for="GENDER"
                                            class="form-label checkout-field-label">Gender*</label> <select
                                            class="form-select" name="GENDER" id="GENDER" required>
                                            <option value="">SELECT</option>
                                            <option value="M">MALE</option>
                                            <option value="F">FEMALE</option>
                                        </select> </div>
                                </div>
                            </div>
                        </div>


                        <!-- New Update -->
                        <!-- Dependents -->
                        <div class="mt-5">
                            <h5 class="checkout-header">Dependents</h5>
                            <div class="row">
                            <!-- Dependents List -->
                                <div class="col-12 col-md-6"> 
                                    <!-- <h6 class="">Change Dependents</h6> -->
                                    <div class="">
                                        <table  class="table ">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Relation</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dependentsTable">
                                                <!-- <tr>
                                                    <td>Hasan Ahmed</td>
                                                    <td>Son</td>
                                                    <td>
                                                        <a class="edit" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i> </a>
                                                        <a class="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-user-minus"></i></a>
                                                    </td>
                                                </tr>   -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Dependent Form -->
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3"> 
                                                <label for="DEP_RELATIONSHIP"
                                                    class="form-label checkout-field-label">Relationship*
                                                </label> 
                                                <select class="form-select" name="DEP_RELATIONSHIP" id="DEP_RELATIONSHIP">
                                                    <option value="">Select</option>
                                                    <option value="Spouse">Spouse</option>
                                                    <option value="Daughter">Daughter</option>
                                                    <option value="Son">Son</option>
                                                    <option value="Mother">Mother</option>
                                                    <option value="Father">Father</option>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3"> 
                                                <label for="DEP_FIRST_NAME" class="checkout-field-label form-label">
                                                    First Name*
                                                </label> 
                                                <input type="text" class="form-control" id="DEP_FIRST_NAME"
                                                    name="DEP_FIRST_NAME" placeholder=""> 
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3"> 
                                                <label for="DEP_LAST_NAME" class="checkout-field-label form-label">
                                                    Last Name*
                                                </label> 
                                                <input type="text" class="form-control" id="DEP_LAST_NAME" name="DEP_LAST_NAME" placeholder=""> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3"> 
                                                <label for="DEP_DOB" class="checkout-field-label form-label">
                                                    Date of Birth*
                                                </label> 
                                                <input type="text" class="form-control" id="DEP_DOB" name="DEP_DOB" placeholder="dd/mm/yyyy" > 
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3"> 
                                                <label for="DEP_GENDER"
                                                    class="form-label checkout-field-label">Gender*</label> <select
                                                    class="form-select" name="DEP_GENDER" id="DEP_GENDER" >
                                                    <option value="">SELECT</option>
                                                    <option value="M">MALE</option>
                                                    <option value="F">FEMALE</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <input id="addDependents" class="btn btn-sm btn-warning" type="button" value="Save Dependents">
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                        </div>


                        <!-- Billing Address -->
                        <div class="mt-5">
                            <h5 class="checkout-header">Billing Address</h5>
                            <div class="row">
                                <div class="col-12 mb-3"> <label for="PAYMENT_ADDRESS"
                                        class="form-label checkout-field-label">Street
                                        Address*</label> <input type="text" class="form-control" id="PAYMENT_ADDRESS"
                                        name="PAYMENT_ADDRESS" placeholder="" required> </div>
                                <div class="col-4"> <label for="PAYMENT_CITY"
                                        class="form-label checkout-field-label">City*</label> <input type="text"
                                        class="form-control" id="PAYMENT_CITY" name="PAYMENT_CITY" placeholder=""
                                        required> </div>
                                <div class="col-4"> <label for="PAYMENT_STATE"
                                        class="form-label checkout-field-label">State*</label> <select
                                        class="form-select" name="PAYMENT_STATE" id="PAYMENT_STATE" required
                                        aria-label="" required>
                                        <option value="">Select</option>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">District Of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select> </div>
                                <div class="col-4"> <label for="PAYMENT_ZIP_CODE"
                                        class="form-label checkout-field-label">Zip
                                        Code*</label> <input type="text" class="form-control" id="PAYMENT_ZIP_CODE"
                                        name="PAYMENT_ZIP_CODE" placeholder="" required> </div>
                            </div>
                        </div>
                        <!-- Credit card Information -->
                        <div class="mt-5 row">
                            <div class="row mb-2 d-flex justify-content-start">
                                <div class="col-12 col-md-7 ms-2 p-0 d-flex align-items-center">
                                    <h5 class="checkout-header">Credit Card Information</h5>
                                </div>
                                <div class="col-12 col-md-4 ms-2 m-md-0 p-0 d-flex align-items-center"> <img
                                        class="payment-image" src="images/footer-payment-logos-new-opt 1.png" alt=""
                                        srcset=""> </div>
                            </div>
                            <div class="col-6"> <label for="PAYMENT_FIRST_NAME"
                                    class="form-label checkout-field-label">First Name On
                                    Card*</label> <input type="text" class="form-control" id="PAYMENT_FIRST_NAME"
                                    name="PAYMENT_FIRST_NAME" placeholder="" required> </div>
                            <div class="col-6"> <label for="PAYMENT_LAST_NAME"
                                    class="form-label checkout-field-label">Last Name On
                                    Card*</label> <input type="text" class="form-control" id="PAYMENT_LAST_NAME"
                                    name="PAYMENT_LAST_NAME" placeholder="" required> </div>
                            <div class="col-12 mt-2"> <label for="CC_NUMBER"
                                    class="form-label checkout-field-label">Card Number*</label> <input type="text"
                                    class="form-control" id="CC_NUMBER" name="CC_NUMBER" placeholder="" required> </div>
                            <div class="row mt-2">
                                <div class="col-4 col-md-3"> <label for="CC_EXP_MONTH"
                                        class="form-label checkout-field-label">Exp.
                                        Month*</label> <input type="number" class="form-control" id="CC_EXP_MONTH"
                                        name="CC_EXP_MONTH" placeholder="" max="12" min="1" required> </div>
                                <div class="col-4 col-md-3"> <label for="CC_EXP_YEAR"
                                        class="form-label checkout-field-label">Exp. Year*</label> <input type="number"
                                        class="form-control" id="CC_EXP_YEAR" name="CC_EXP_YEAR" placeholder=""
                                        required> </div>
                                <div class="col-4 col-md-3"> <label for="CC_CVV2"
                                        class="form-label checkout-field-label">CVV Code*</label> <input type="text"
                                        class="form-control" id="CC_CVV2" name="CC_CVV2" placeholder="" required> </div>
                            </div>
                            <!-- Cart Bottom Text -->
                            <div class="cart-bottom-text mt-3"> All credit/debit card transactions through this website
                                are <br> guaranteed to be safe and secure with 256-bit SSL Certificates. </div>
                            <!-- Submit -->
                            <div class="mt-3"> <button type="submit" value="submit"
                                    class="submit-button border-0 btn text-white fw-bold py-3 px-5"
                                    id="submit-btn">Process Payment</button> </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-4">
                <!-- Checkout Summary -->
                <div class="checkout-summary text-white text-center py-3">
                    <h3 class="py-2">Checkout Summary</h3>
                    <hr class="text-white border border-1 p-0 mb-2">
                    <h5><?php echo $plan_name; ?></h5>
                    <h5>Plan Type: <span class="fs-6">
                            <?php echo $plan_type; ?> <span id="payment_time"></span> Payment
                        </span>
                    </h5>
                    <div class="plan-price mt-2 mx-5 py-2">
                        <h5>Plan Price</h5>
                        <h3 class="d-none" id="plan_price_summery">$<span id="plan_price"></span>/<span
                                id="plan_renew"></span></h3>
                    </div>
                    <p class="my-2">Effective Date: <span id="today"></span></p>
                </div>
                <!-- Refund Notice -->
                <div class="refund-notice text-center my-4"> *There is a non-refundable application fee <br> of $
                    <?php echo $enrolment_fee; ?> included with your first payment
                </div>
                <!-- Plan Benefits -->
                <div class="plan-benefits container py-4">
                    <h5 class="">Plan Benefits</h5>
                    <div class="row">
                        <div class="col-6"> <i class="fas fa-check-circle custom-bg-check"></i> Dental </div>
                        <div class="col-6"> <i class="fas fa-check-circle custom-bg-check"></i> Teladoc </div>
                        <div class="col-6"> <i class="fas fa-check-circle custom-bg-check"></i> Vision </div>
                        <div class="col-6"> <i class="fas fa-check-circle custom-bg-check"></i> Hearing </div>
                        <div class="col-6"> <i class="fas fa-check-circle custom-bg-check"></i> Labs </div>
                        <div class="col-6"> <i class="fas fa-check-circle custom-bg-check"></i> Pharmacy </div>
                    </div>
                </div>
                <!-- SSL Seal -->
                <div class="mt-4"> <img class="img-fluid" src="images/ssl-secure-checkout-trust-seal.png" alt=""> </div>
            </div>
        </div>
    </main>
    <!-- MAIN SECTION END -->
    <!-- FOOTER START -->
    <footer class="text-center mt-5">
        <p class="footer-text">© 2021 Doc Wellbee, Inc.. All Rights Reserved.</p>
    </footer>
    <!-- FOOTER END -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Custom JavaScripts -->
    <script>
    $(document).ready(function() {
        const showDate = () => {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, "0");
            var mm = String(today.getMonth() + 1).padStart(2, "0");
            var yyyy = today.getFullYear();
            today = mm + "/" + dd + "/" + yyyy;
            document.getElementById("today").innerHTML = today;
        }
        showDate();

        // New Update 
        let type, value, PRODUCT_FEE = null,
            coupon_code = null, coupon_status = false, discount_monthly=null, discount_yearl=null, allDependents = [], dependentsCounter=1;

        /* Event Listener For Billing Option 1 */
        document.getElementById("PRODUCT_FEE_Field_1").addEventListener("click",
            function() {
                document.getElementById("plan_price_summery").classList.remove("d-none");
                document.getElementById("plan_price_summery").classList.add("d-block");
                document.getElementById("enrollment_fee").innerHTML = <?php echo $enrolment_fee; ?>;
                document.getElementById("plan_price").innerHTML = <?php echo $product_price_monthly; ?>;
                document.getElementById("plan_cost").innerHTML = <?php echo $product_price_monthly; ?>;
                document.getElementById("plan_renew").innerHTML = "Month";
                document.getElementById("payment_time").innerHTML = "Monthly";
                document.getElementById("main_plan_type1").innerHTML = "Monthly";
                PRODUCT_FEE = "Monthly";
                showCouponValue();
                calculateTotal();
            }
        );

        /* Event Listener For Billing Option 2 */
        document.getElementById("PRODUCT_FEE_Field_2").addEventListener("click",
            function() {
                document.getElementById("plan_price_summery").classList.remove("d-none");
                document.getElementById("plan_price_summery").classList.add("d-block");
                document.getElementById("plan_price").innerHTML = <?php echo $product_price_yearly; ?>;
                document.getElementById("enrollment_fee").innerHTML = <?php echo $enrolment_fee; ?>;
                document.getElementById("plan_cost").innerHTML = <?php echo $product_price_yearly; ?>;
                document.getElementById("plan_renew").innerHTML = "Year";
                document.getElementById("payment_time").innerHTML = "Yearly";
                document.getElementById("main_plan_type1").innerHTML = "Yearly";
                PRODUCT_FEE = "Yearly";
                showCouponValue();
                calculateTotal();
            }
        );

        /* Event Listener For Coupon */
        document.getElementById("coupon_btn").addEventListener("click",
            function() {
                // alert(PRODUCT_FEE);
                if(PRODUCT_FEE == null){
                    alert("Select A Billing Option.");
                }
                else{
                    // alert(PRODUCT_FEE);
                    const couponText = document.getElementById("coupon_code").value;
                    const url = `<?php echo $URL; ?>?coupon_code=${couponText}`;
                    $.getJSON(url, function(data, status) {
                        // alert(data);
                        discount_monthly = data.discount_monthly;
                        discount_yearly = data.discount_yearly;
                        message = data.message;
                        //  alert(discount_monthly);
                        //  alert(discount_yearly);
                        //  alert(message);
                        if (data.coponStatus == "ok") { 
                            coupon_code = document.getElementById("coupon_code").value;
                            document.getElementById("coupon").value = ""; 

                            document.getElementById("Coupon_message").classList.remove("d-none");
                            document.getElementById("remove_coupon").classList.remove("d-none");
                            document.getElementById("Coupon_message").innerHTML = "("+message+")";
                            

                            document.getElementById("coupon_btn").innerHTML = "Applied";
                            document.getElementById("coupon_btn").classList.add("disabled");
                            document.getElementById("coupon_btn").classList.remove("btn-warning");
                            document.getElementById("coupon_btn").classList.add("btn-primary");
                            document.getElementById("coupon").disabled = true;
                            document.getElementById("coupon_code").disabled = true;

                            coupon_code = document.getElementById("coupon_code").value;
                            document.getElementById("coupon_code").value = "";

                            coupon_status = true;
                            // calculateDiscount(type, value);
                            showCouponValue();
                            calculateTotal();
                        } else {
                            alert("Coupon Not Applicable.");
                        }
                    });
                }
                
            }
        );
        const showCouponValue = () =>{
            if(coupon_status){
                if(PRODUCT_FEE=="Monthly"){
                    document.getElementById("coupon").innerHTML = discount_monthly;
                }
                else{
                    document.getElementById("coupon").innerHTML = discount_yearly;
                }
            }
            else{
                document.getElementById("coupon").innerHTML = 0;
            }
            
        };

        /* Event Listener For Coupon remove*/
        document.getElementById("remove_coupon").addEventListener("click",
            function() {
                document.getElementById("Coupon_message").classList.add("d-none");
                document.getElementById("remove_coupon").classList.add("d-none");

                document.getElementById("coupon_btn").innerHTML = "Apply Coupon";
                document.getElementById("coupon_btn").classList.remove("disabled");
                document.getElementById("coupon_btn").classList.remove("btn-primary");
                document.getElementById("coupon_btn").classList.add("btn-warning");
                document.getElementById("coupon").disabled = false;
                document.getElementById("coupon_code").disabled = false;

                document.getElementById("coupon").innerHTML = "0";
                discount_monthly = 0;
                discount_yearly = 0;
                message = "";
                calculateTotal();
            }
        );

        // const calculateDiscount = (type, value) => {
        //     const plan_cost = parseFloat(document.getElementById("plan_cost").innerHTML);
        //     const enrollment_fee = parseFloat(document.getElementById("enrollment_fee").innerHTML);
        //     if (type == "Percentage") {
        //         const discount = ((plan_cost + enrollment_fee) / 100) * value;
        //         document.getElementById("coupon").innerHTML = Math.floor(discount * 100) / 100;
        //         calculateTotal();
        //     } else {
        //         const discount = value;
        //         document.getElementById("coupon").innerHTML = Math.floor(discount * 100) / 100;
        //         calculateTotal();
        //     }
        // }


        // New Update 
        // Show Dependents List
        const printDepenedentsList = (singleDependent, dependentsCounter) =>{
            var dependentsRow = " ";

            dependentsRow += '<tr id="' + dependentsCounter + '">';

            dependentsRow += "<td>" + singleDependent.DEP_FIRST_NAME + " " + singleDependent.DEP_LAST_NAME  + "</td>";
            dependentsRow += "<td>" + singleDependent.DEP_RELATIONSHIP + "</td>";
            dependentsRow += "<td>";
            dependentsRow += '<a onclick="editDependents('+dependentsCounter+')" class="btn"><i class="fas fa-edit"></i> </a>';
            dependentsRow += '<a onclick="deleteDependents('+dependentsCounter+')" class="btn"><i class="fas fa-user-minus"></i> </a>';
            dependentsRow += "</td>";

            dependentsRow += "</tr>";
            console.log(dependentsRow);
            tableBody = $("#dependentsTable");
            tableBody.append(dependentsRow);
        }

        // Add dependents
        const addDependents = () =>{
                // alert("Clicked");
                const singleDependent = {
                    DEP_RELATIONSHIP: $("#DEP_RELATIONSHIP").val(),
                    DEP_FIRST_NAME: $("#DEP_FIRST_NAME").val(),
                    DEP_LAST_NAME: $("#DEP_LAST_NAME").val(),
                    DEP_DOB: $("#DEP_DOB").val(),
                    DEP_GENDER: $("#DEP_GENDER").val()
                }
                console.log(singleDependent);
                allDependents[dependentsCounter] = singleDependent;
                console.log(allDependents);
                printDepenedentsList(singleDependent, dependentsCounter);
                dependentsCounter++;

        }
        // Event Listener for add dependents
        document.getElementById("addDependents").addEventListener("click", addDependents);







        const calculateTotal = () => {

            var plan_cost = parseFloat(document.getElementById("plan_cost").innerHTML).toFixed(2);
            // alert(typeof(plan_cost));
            // alert(plan_cost);

            var enrollment_fee = parseFloat(document.getElementById("enrollment_fee").innerHTML).toFixed(2);
            // alert(typeof(enrollment_fee));
            // alert(enrollment_fee);

            var discount = parseFloat(document.getElementById("coupon").innerHTML).toFixed(2);
            // alert(typeof(discount));
            // alert(discount);

            var total = ((parseFloat(plan_cost) + parseFloat(enrollment_fee)) - parseFloat(discount)).toFixed(2);
            // alert(typeof(total));
            // alert(total);

            document.getElementById("total_payment").innerHTML = total;
            //document.getElementById("plan_price").innerHTML = Math.floor(total * 100) / 100; 
        }

        // auto click yearly payment
        var fl = 1;
        const autoclick = () =>{
            $("#PRODUCT_FEE_Field_2").trigger("click");
            document.getElementById("plan_price_summery").classList.remove("d-none");
            document.getElementById("plan_price_summery").classList.add("d-block");
            document.getElementById("plan_price").innerHTML = <?php echo $product_price_yearly; ?>;
            document.getElementById("enrollment_fee").innerHTML = <?php echo $enrolment_fee; ?>;
            document.getElementById("plan_cost").innerHTML = <?php echo $product_price_yearly; ?>;
            document.getElementById("plan_renew").innerHTML = "Year";
            document.getElementById("payment_time").innerHTML = "Yearly";
            document.getElementById("main_plan_type1").innerHTML = "Yearly";
            PRODUCT_FEE = "Yearly";
            showCouponValue();
            calculateTotal();
        }
        if(fl==1){
            autoclick();
            fl =0;
        }

        /*Form Subission */
        $("form").submit(function(event) {
            var formData = {
                SOURCE: $("#SOURCE").val(),
                FIRST_NAME: $("#FIRST_NAME").val(),
                LAST_NAME: $("#LAST_NAME").val(),
                DAYPHONE: $("#DAYPHONE").val(),
                EMAIL: $("#EMAIL").val(),
                DOB: $("#DOB").val(),
                GENDER: $("#GENDER").val(),
                PAYMENT_ADDRESS: $("#PAYMENT_ADDRESS").val(),
                PAYMENT_CITY: $("#PAYMENT_CITY").val(),
                PAYMENT_ZIP_CODE: $("#PAYMENT_ZIP_CODE").val(),
                PAYMENT_STATE: $("#PAYMENT_STATE").val(),
                PAYMENT_FIRST_NAME: $("#PAYMENT_FIRST_NAME").val(),
                PAYMENT_LAST_NAME: $("#PAYMENT_LAST_NAME").val(),
                CC_NUMBER: $("#CC_NUMBER").val(),
                CC_EXP_MONTH: $("#CC_EXP_MONTH").val(),
                CC_EXP_YEAR: $("#CC_EXP_YEAR").val(),
                CC_CVV2: $("#CC_CVV2").val(),
                PRODUCT_FEE: PRODUCT_FEE,
                submit: $("#submit-btn").val(),
                coupon_code: coupon_code
            };
            console.log(formData);
            // $.ajax({
            //     type: "post",
            //     dataType : "json",
            //     url: "<?php echo $URL; ?>",
            //     data: formData,
            //     success: function(response) {
            //         // alert(response); 
            //         if (response.PaymentStatus == "True") {
            //             window.location.href = response.URL;
            //         } else {
            //             alert("Payment failed, Please check your payment information");
            //         }
            //         /*alert(response); */
            //     }
            // });
            event.preventDefault();
        });
    });
    </script>
</body>

</html>