<?php
require('config.php'); 

	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
				=== 'on' ? "https" : "http") .
				"://" . $_SERVER['HTTP_HOST'] .
				$_SERVER['REQUEST_URI'];
				
if(isset($_POST['send_ioncube']))                                    
{ 
    require('api.php'); 
    
    $errors= array();
    
    $customer_email = $_POST['email'];
    if ($customer_email == '') {
        $errors[]= "Please enter a valid email in your profile.";
    }
      $file_name = $_FILES['encoded_file']['name'];
      $file_size = $_FILES['encoded_file']['size'];
      $file_tmp = $_FILES['encoded_file']['tmp_name'];
      $file_type = $_FILES['encoded_file']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['encoded_file']['name'])));
      
      $extension  = pathinfo( $_FILES["encoded_file"]["name"], PATHINFO_EXTENSION ); // 
 
     
    if ($extension != "") {        
        
      if ($extension == "zip") {  
      if($file_size > 10485760) {
         $errors[]= "Your file size is high, the ZIP file should be 10 MB maximum.";
      } }
      else {
         $errors[]= "Please select the ZIP file.";
      }
    }
    else {
        
      $errors[]= "Unfortunately, you did not select a file to upload.";
    }

          if(empty($errors)==true) {
             if ($extension == "zip") {
                 $data_id = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                 $order_idz = substr(str_shuffle($data_id), 0, 9);
                 $filename   = $order_idz . "_encode"; // 
                 $basename   = $filename . "." . $extension; // 
                 $destination  = "order_files/{$basename}";
                 
                 move_uploaded_file($file_tmp, $destination );

                 $zip = new ZipArchive();
                 $zip->open("order_files/$basename");
                 $result_stats = array();
                 for ($i = 0; $i < $zip->numFiles; $i++) {
                     $stat = $zip->statIndex($i);
                     if ($stat['size'])
                     $result_stats[] = $stat;
                }
             }
                $count_file = count($result_stats);
                if ($count_file != 0) {
                    $price_order = $ioncube_price_decode * $count_file;
                    
                    $api = new Api();

                    $link_upload = $link.'order_files/'.$basename;                    
                    $order_api = $api->add_order(array('file' => $link_upload)); # ZIP FILE
                    
                    if ($order_api->status == 'error') {
                         $errors[]= $order_api->message;
                         $order_status = 0;
                    }
                    else {
                        $order_api_id = $order_api->order;
                        
                    $submit_to_order = $db->prepare('INSERT INTO orders (order_id,order_api_id,order_email,order_price,order_count,order_encoded,order_status) VALUES (:order_id, :order_api_id, :order_email, :order_price, :order_count, :order_encoded, :order_status)');
                    $submit_to_order->execute(array(
                        ':order_id' => $order_idz,
                        ':order_api_id' => $order_api_id,                        
                        ':order_email' => $customer_email,
                        ':order_price' => $price_order,	
                        ':order_count' => $count_file,					
                        ':order_encoded' => $basename,				
                        ':order_status' => '0'
                    ));
                    
                    $order_status = 1;
                    }
             }
             $action = "info";
             }
             else {
                 $order_status = 0;
}
}
    ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <link rel="apple-touch-icon" type="image/png"
        href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
    <meta name="apple-mobile-web-app-title" content="CodePen">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />
    <link rel="mask-icon" type="image/x-icon"
        href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg"
        color="#111" />
    <title>ioncube decoder | demo version 1</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css'>
    <link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css'>
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <style>
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            opacity: 0.83;
            transition: opacity 0.6s;
            : 15px;
        }

        .alert.success {
            background-color: #04AA6D;
        }

        .alert.info {
            background-color: #2196F3;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }

        @charset "UTF-8";
        /*font Variables*/
        /*Color Variables*/
        @import url("https://fonts.googleapis.com/css?family=Roboto:300i,400,400i,500,700,900");

        .multi_step_form {
            background: #f6f9fb;
            display: block;
            overflow: hidden;
        }

        .multi_step_form #msform {
            text-align: center;
            position: relative;
            padding-top: 50px;
            min-height: 820px;
            max-width: 810px;
            margin: 0 auto;
            background: #ffffff;
            z-index: 1;
        }

        .multi_step_form #msform .tittle {
            text-align: center;
            padding-bottom: 55px;
        }

        .multi_step_form #msform .tittle h2 {
            font: 500 24px/35px "Roboto", sans-serif;
            color: #3f4553;
            padding-bottom: 5px;
            b
        }

        .multi_step_form #msform .tittle p {
            font: 400 16px/28px "Roboto", sans-serif;
            color: #5f6771;
        }

        .multi_step_form #msform fieldset {
            border: 0;
            padding: 20px 105px 0;
            position: relative;
            width: 100%;
            left: 0;
            right: 0;
        }

        .multi_step_form #msform fieldset:not(:first-of-type) {
            display: none;
        }

        .multi_step_form #msform fieldset h3 {
            font: 500 18px/35px "Roboto", sans-serif;
            color: #3f4553;
        }

        .multi_step_form #msform fieldset h6 {
            font: 400 15px/28px "Roboto", sans-serif;
            color: #5f6771;
            padding-bottom: 30px;
        }

        .multi_step_form #msform fieldset .intl-tel-input {
            display: block;
            background: transparent;
            border: 0;
            box-shadow: none;
            outline: none;
        }

        .multi_step_form #msform fieldset .intl-tel-input .flag-container .selected-flag {
            padding: 0 20px;
            background: transparent;
            border: 0;
            box-shadow: none;
            outline: none;
            width: 65px;
        }

        .multi_step_form #msform fieldset .intl-tel-input .flag-container .selected-flag .iti-arrow {
            border: 0;
        }

        .multi_step_form #msform fieldset .intl-tel-input .flag-container .selected-flag .iti-arrow:after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            font: normal normal normal 24px/7px Ionicons;
            color: #5f6771;
        }

        .multi_step_form #msform fieldset #phone {
            padding-left: 80px;
        }

        .multi_step_form #msform fieldset .form-group {
            padding: 0 10px;
        }

        .multi_step_form #msform fieldset .fg_2,
        .multi_step_form #msform fieldset .fg_3 {
            padding-top: 10px;
            display: block;
            overflow: hidden;
        }

        .multi_step_form #msform fieldset .fg_3 {
            padding-bottom: 70px;
        }

        .multi_step_form #msform fieldset .form-control,
        .multi_step_form #msform fieldset .product_select {
            border-radius: 3px;
            border: 1px solid #d8e1e7;
            padding: 0 20px;
            height: auto;
            font: 400 15px/48px "Roboto", sans-serif;
            color: #5f6771;
            box-shadow: none;
            outline: none;
            width: 100%;
        }

        .multi_step_form #msform fieldset .form-control.placeholder,
        .multi_step_form #msform fieldset .product_select.placeholder {
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .form-control:-moz-placeholder,
        .multi_step_form #msform fieldset .product_select:-moz-placeholder {
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .form-control::-moz-placeholder,
        .multi_step_form #msform fieldset .product_select::-moz-placeholder {
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .form-control::-webkit-input-placeholder,
        .multi_step_form #msform fieldset .product_select::-webkit-input-placeholder {
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .form-control:hover,
        .multi_step_form #msform fieldset .form-control:focus,
        .multi_step_form #msform fieldset .product_select:hover,
        .multi_step_form #msform fieldset .product_select:focus {
            border-color: #5cb85c;
        }

        .multi_step_form #msform fieldset .form-control:focus.placeholder,
        .multi_step_form #msform fieldset .product_select:focus.placeholder {
            color: transparent;
        }

        .multi_step_form #msform fieldset .form-control:focus:-moz-placeholder,
        .multi_step_form #msform fieldset .product_select:focus:-moz-placeholder {
            color: transparent;
        }

        .multi_step_form #msform fieldset .form-control:focus::-moz-placeholder,
        .multi_step_form #msform fieldset .product_select:focus::-moz-placeholder {
            color: transparent;
        }

        .multi_step_form #msform fieldset .form-control:focus::-webkit-input-placeholder,
        .multi_step_form #msform fieldset .product_select:focus::-webkit-input-placeholder {
            color: transparent;
        }

        .multi_step_form #msform fieldset .product_select:after {
            display: none;
        }

        .multi_step_form #msform fieldset .product_select:before {
            content: "";
            position: absolute;
            top: 0;
            right: 20px;
            font: normal normal normal 24px/48px Ionicons;
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .product_select .list {
            width: 100%;
        }

        .multi_step_form #msform fieldset .done_text {
            padding-top: 40px;
        }

        .multi_step_form #msform fieldset .done_text .don_icon {
            height: 36px;
            width: 36px;
            line-height: 36px;
            font-size: 22px;
            margin-bottom: 10px;
            background: #5cb85c;
            display: inline-block;
            border-radius: 50%;
            color: #ffffff;
            text-align: center;
        }

        .multi_step_form #msform fieldset .done_text h6 {
            line-height: 23px;
        }

        .multi_step_form #msform fieldset .code_group {
            margin-bottom: 60px;
        }

        .multi_step_form #msform fieldset .code_group .form-control {
            border: 0;
            border-bottom: 1px solid #a1a7ac;
            border-radius: 0;
            display: inline-block;
            width: 30px;
            font-size: 30px;
            color: #5f6771;
            padding: 0;
            margin-right: 7px;
            text-align: center;
            line-height: 1;
        }

        .multi_step_form #msform fieldset .passport {
            margin-top: -10px;
            padding-bottom: 30px;
            position: relative;
        }

        .multi_step_form #msform fieldset .passport .don_icon {
            height: 36px;
            width: 36px;
            line-height: 36px;
            font-size: 22px;
            position: absolute;
            top: 4px;
            right: 0;
            background: #5cb85c;
            display: inline-block;
            border-radius: 50%;
            color: #ffffff;
            text-align: center;
        }

        .multi_step_form #msform fieldset .passport h4 {
            font: 500 15px/23px "Roboto", sans-serif;
            color: #5f6771;
            padding: 0;
        }

        .multi_step_form #msform fieldset .input-group {
            padding-bottom: 40px;
        }

        .multi_step_form #msform fieldset .input-group .custom-file {
            width: 100%;
            height: auto;
        }

        .multi_step_form #msform fieldset .input-group .custom-file .custom-file-label {
            width: 168px;
            border-radius: 5px;
            cursor: pointer;
            font: 700 14px/40px "Roboto", sans-serif;
            border: 1px solid #99a2a8;
            text-align: center;
            transition: all 300ms linear 0s;
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .input-group .custom-file .custom-file-label i {
            font-size: 20px;
            padding-right: 10px;
        }

        .multi_step_form #msform fieldset .input-group .custom-file .custom-file-label:hover,
        .multi_step_form #msform fieldset .input-group .custom-file .custom-file-label:focus {
            background: #5cb85c;
            border-color: #5cb85c;
            color: #fff;
        }

        .multi_step_form #msform fieldset .input-group .custom-file input {
            display: none;
        }

        .multi_step_form #msform fieldset .file_added {
            text-align: left;
            padding-left: 190px;
            padding-bottom: 60px;
        }

        .multi_step_form #msform fieldset .file_added li {
            font: 400 15px/28px "Roboto", sans-serif;
            color: #5f6771;
        }

        .multi_step_form #msform fieldset .file_added li a {
            color: #5cb85c;
            font-weight: 500;
            display: inline-block;
            position: relative;
            padding-left: 15px;
        }

        .multi_step_form #msform fieldset .file_added li a i {
            font-size: 22px;
            padding-right: 8px;
            position: absolute;
            left: 0;
            transform: rotate(20deg);
        }

        .multi_step_form #msform #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
        }

        .multi_step_form #msform #progressbar li {
            list-style-type: none;
            color: #99a2a8;
            font-size: 9px;
            width: calc(100%/3);
            float: left;
            position: relative;
            font: 500 13px/1 "Roboto", sans-serif;
        }

        .multi_step_form #msform #progressbar li:nth-child(2):before {
            content: "";
        }

        .multi_step_form #msform #progressbar li:nth-child(3):before {
            content: "";
        }

        .multi_step_form #msform #progressbar li:before {
            content: "";
            font: normal normal normal 30px/50px Ionicons;
            width: 50px;
            height: 50px;
            line-height: 50px;
            display: block;
            background: #eaf0f4;
            border-radius: 50%;
            margin: 0 auto 10px auto;
        }

        .multi_step_form #msform #progressbar li:after {
            content: "";
            width: 100%;
            height: 10px;
            background: #eaf0f4;
            position: absolute;
            left: -50%;
            top: 21px;
            z-index: -1;
        }

        .multi_step_form #msform #progressbar li:last-child:after {
            width: 150%;
        }

        .multi_step_form #msform #progressbar li.active {
            color: #5cb85c;
        }

        .multi_step_form #msform #progressbar li.active:before,
        .multi_step_form #msform #progressbar li.active:after {
            background: #5cb85c;
            color: white;
        }

        .multi_step_form #msform .action-button {
            background: #5cb85c;
            color: white;
            border: 0 none;
            border-radius: 5px;
            cursor: pointer;
            min-width: 130px;
            font: 700 14px/40px "Roboto", sans-serif;
            border: 1px solid #5cb85c;
            margin: 0 5px;
            text-transform: uppercase;
            display: inline-block;
        }

        .multi_step_form #msform .action-button:hover,
        .multi_step_form #msform .action-button:focus {
            background: #405867;
            border-color: #405867;
        }

        .multi_step_form #msform .previous_button {
            background: transparent;
            color: #99a2a8;
            border-color: #99a2a8;
        }

        .multi_step_form #msform .previous_button:hover,
        .multi_step_form #msform .previous_button:focus {
            background: #405867;
            border-color: #405867;
            color: #fff;
        }
    </style>

    <script>
        window.console = window.console || function(t) {};
    </script>



    <script>
        if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
    </script>


</head>

<body translate="no">
    <!-- Multi step form -->
    <section class="multi_step_form">
        <form action="#" method="post" id="msform" enctype="multipart/form-data">
            <?php if($order_status == "0") { ?>
            <div class="alert" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle"></i>
                    <span>
                                                    <?= $errors[0]; $errors[1]; $errors[2] ?>
                                                </span>
                </div>
            </div>
            <?php } ?>
            <?php if($order_status == "1") { ?>
            <div class="alert success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle"></i>
                    <span>
                                                   Your order has been successfully placed. <br>Order ID for Tracking: <?=$order_idz?><br><hr><br>You can <a href="track.php">Track Your Order From Here</a>
                                                </span>
                </div>
            </div>
            <?php } ?>
            <!-- Tittle -->
            <div class="tittle">
                <h2>ionCube decode files | demo</h2>
                <p>This demo is synced with API <a href="https://panel.decodez.net">Decodez Panel.</a></p>
            </div>
            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active">Your Profile</li>
                <li>Upload Zip (Encode file)</li>
                <li>Payment Gateways</li>
            </ul>
            <!-- fieldsets -->
            <fieldset>
                <h3>Setup your Account</h3>
                <h6>Please enter a valid email. (Notification will be sent to this email)</h6>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="test@mail.com">
                </div>
                <button type="button" class="action-button previous_button">Back</button>
                <button type="button" class="next action-button">Continue</button>
            </fieldset>
            <fieldset>
                <h3>Upload Your Encoded file</h3>
                <h6>Please put your ioncube encoded files in a zip file and upload it here.</h6>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="encoded_file" id="file-5" class="inputfile inputfile-4" accept=".zip">
                        <label for="file-5"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Choose a Zip file&hellip;</span></label>
                    </div>
                </div>
                <button type="button" class="action-button previous previous_button">Back</button>
                <button type="button" class="next action-button">Continue</button>
            </fieldset>
            <fieldset>
                <h3>Payment Gateway</h3>
                <h6>Here you can put your favorite payment gateways.</h6>
                <hr>
                <p>Pay attention :<br>
      If you click on (Finish) now and your balance is sufficient in Decodez Panel, the order will be registered automatically through the web service.
                </p>
                <button type="button" class="action-button previous previous_button">Back</button>
                <button type="submit" name="send_ioncube" class="action-button">Finish</button>
            </fieldset>
        </form>
    </section>
    <!-- End Multi step form -->
    <script
        src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js">
    </script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js'></script>
    <script id="rendered-js">
        ;(function ($) {
  "use strict";

  //* Form js
  function verificationForm() {
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function () {
      if (animating) return false;
      animating = true;

      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //activate next step on progressbar using the index of next_fs
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

      //show the next fieldset
      next_fs.show();
      //hide the current fieldset with style
      current_fs.animate({
        opacity: 0 },
      {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring next_fs from the right(50%)
          left = now * 50 + "%";
          //3. increase opacity of next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'transform': 'scale(' + scale + ')',
            'position': 'absolute' });

          next_fs.css({
            'left': left,
            'opacity': opacity });

        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack' });

    });

    $(".previous").click(function () {
      if (animating) return false;
      animating = true;

      current_fs = $(this).parent();
      previous_fs = $(this).parent().prev();

      //de-activate current step on progressbar
      $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

      //show the previous fieldset
      previous_fs.show();
      //hide the current fieldset with style
      current_fs.animate({
        opacity: 0 },
      {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale previous_fs from 80% to 100%
          scale = 0.8 + (1 - now) * 0.2;
          //2. take current_fs to the right(50%) - from 0%
          left = (1 - now) * 50 + "%";
          //3. increase opacity of previous_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'left': left });

          previous_fs.css({
            'transform': 'scale(' + scale + ')',
            'opacity': opacity });

        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack' });

    });

    $(".submit").click(function () {
      return false;
    });
  };

  //* Select js
  function nice_Select() {
    if ($('.product_select').length) {
      $('select').niceSelect();
    };
  };
  /*Function Calls*/
  verificationForm();
  nice_Select();
})(jQuery);
//# sourceURL=pen.js
    </script>
    <script src="js/custom-file-input.js"></script>

</body>

</html>