<?php

    require_once("Rest.inc.php");
    require_once("db.php");
    require_once('../admin/include/DATA_CONFIG.php');
   
    $access_key = $PURCODE;
    $refer_bonus = '5';         // sign up bonus in coins
    $referer_bonus = '10';      // referer bonus in coins
    $use_of_bonus = '10';       // use of bonus to join match and lottery in percentage
                
    class functions extends REST {
    
        private $mysqli = NULL;
        private $db = NULL;
        
        public function __construct($db) {
            parent::__construct();
            $this->db = $db;
            $this->mysqli = $db->mysqli;
        }
        
        public function checkConnection() {
            if (mysqli_ping($this->mysqli)) {
                $respon = array(
                    'status' => 'ok', 'database' => 'connected'
                );
                $this->response($this->json($respon), 200);
            } else {
                $respon = array(
                    'status' => 'failed', 'database' => 'not connected'
                );
                $this->response($this->json($respon), 404);
            }
        }
        
        public function userRegister() {
            include "../include/config.php";
            include "../public/register.php";
            global $access_key;
            global $refer_bonus;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $sql = "SELECT count(id) as totrow FROM user_details WHERE device_id = '".$_GET['device_id']."' AND is_block = '1'"; 
                $res = mysqli_query($connect, $sql);
                $res_count = mysqli_fetch_array($res);
        
                if($res_count['totrow'] > 0) {
                    $set['result'][]=array('msg' => "You are not eligible to create new account. Please contact us.", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);
                } 
                else {
                    if(isset($_GET['referer'])) {
                        $qry = "SELECT count(id) as tot_row FROM user_details WHERE mobile = '".$_GET['mobile']."' OR email = '".$_GET['email']."' OR  username = '".$_GET['username']."'"; 
                        $sel = mysqli_query($connect, $qry);
                        $sel_res = mysqli_fetch_array($sel);
                    
                        if($sel_res['tot_row'] > 0) {
                            $set['result'][]=array('msg' => "This username, email id or mobile number already used.", 'success'=>'0');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        } 
                        else {
                            $qry1 = "SELECT count(id) as tot_row1 FROM user_details WHERE refer = '".$_GET['referer']."' AND status='1'"; 
                            $sel1 = mysqli_query($connect, $qry1);
                            $sel1_res = mysqli_fetch_array($sel1);

                            if($sel1_res['tot_row1'] > 0) {
                                $today = date("Y-m-d"); 
                                
                                $qry_refered="SELECT refered FROM user_details WHERE refer='".$_GET['referer']."' AND status='1'";
                                $total_refered = mysqli_fetch_array(mysqli_query($connect,$qry_refered));
                                $total_refered = $total_refered['refered']; 
                                $total=$total_refered+1;
                            
                                $data1 = array(
                                    'user_type'=>'Normal',  
                                    'fname'  => $_GET['fname'],
                                    'lname'  => $_GET['lname'],
                                    'username'  => $_GET['username'],
                                    'password'  => md5($_GET['password']),
                                    'email'  =>  $_GET['email'],
                                    'country_code'  =>  $_GET['country_code'],
                                    'mobile'  =>  $_GET['mobile'],
                                    'refer'  => $_GET['username'],
                                    'referer'  =>  $_GET['referer'],
                                    'cur_balance' => $refer_bonus,
                                    'won_balance'  => '0',
                                    'bonus_balance' => $refer_bonus,
                                    'created_date' => date("Y-m-d"),
                                    'device_id' => $_GET['device_id'],
                                    'is_block' => '0',
                                    'status'  =>  '1'
                                );
            
                                $data2 = array(
                                    'refered'=>$total   
                                );
                                
                                $data3 = array(
                                    'username'  => $_GET['username'],
                                    'refer_points'  =>  '0',
                                    'refer_code'  =>  $_GET['referer'],
                                    'refer_status'  => '0',
                                    'refer_date'=>$today
                                );
                                
                                $qry1 = Insert('user_details', $data1); 
                                $qry2 = Update('user_details', $data2,"WHERE refer = '".$_GET['referer']."'");  
                                $qry3 = Insert('referral_details', $data3); 
                                
                                $qry4 = "SELECT id FROM user_details WHERE username = '".$_GET['username']."'"; 
                                $userdata = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                $id = $userdata['id'];
                        
                                $set1['result'][] = array('data' => '$id', 'msg' => "Your account has been register succesfully.", 'success'=>'2');
                                echo $val= str_replace('\\/', '/', json_encode($set1, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            } else {
                                $set1['result'][]=array('msg' => "Referral code not found or wrong!", 'success'=>'1');
                                echo $val= str_replace('\\/', '/', json_encode($set1, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            }
                        
                        }
                        
                    } 
                    else if(isset($_GET['email'])) {
                        $qry = "SELECT count(id) as totrow2 FROM user_details WHERE mobile = '".$_GET['mobile']."' OR email = '".$_GET['email']."' OR  username = '".$_GET['username']."'"; 
                        $sel = mysqli_query($connect, $qry);
                        $selres = mysqli_fetch_array($sel);
                    
                        if($selres['totrow2'] > 0) {
                            $set['result'][]=array('msg' => "This username, email or mobile already used!", 'success'=>'0');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        } else {
                            $data = array(
                                'user_type'=>'Normal',  
                                'fname'  => $_GET['fname'],
                                'lname'  => $_GET['lname'],
                                'username'  => $_GET['username'],
                                'password'  =>  md5($_GET['password']),
                                'email'  =>  $_GET['email'],
                                'country_code'  =>  $_GET['country_code'],
                                'mobile'  =>  $_GET['mobile'],
                                'refer'  => $_GET['username'],
                                'cur_balance' => '0',
                                'won_balance'  => '0',
                                'bonus_balance' => '0',
                                'created_date'=> date("Y-m-d"),
                                'device_id' => $_GET['device_id'],
                                'is_block' => '0',
                                'status'  =>  '1'
                            );
            
                            $qry = Insert('user_details', $data);                                    
                            
                            $qry4 = "SELECT id FROM user_details WHERE username = '".$_GET['username']."'"; 
                            $userdata = mysqli_fetch_array(mysqli_query($connect,$qry4));
                            $id = $userdata['id'];
                                 
                            $set['result'][] = array('data' => '$id', 'msg' => "Your account has been register succesfully.", 'success'=>'1');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        }
                    }
                    else {
                         header( 'Content-Type: application/json; charset=utf-8' );
                         $json = json_encode($set);
                         echo $json;
                         mysqli_close($connect);       
                    }
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function userLogin() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $sql = "SELECT count(*) as totrow FROM user_details WHERE device_id = '".$_GET['device_id']."' AND is_block = '1'"; 
                $res = mysqli_query($connect, $sql);
                $res_res = mysqli_fetch_array($res);
                
                if($res_res['totrow'] > 0) {
                    $set['result'][]=array('msg' => "You are not eligible to login this account. Please contact us.", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);
                } 
                else if (isset($_GET['password'])) {
                    $qry = "SELECT status, is_block, id, fname, lname, user_profile, username, email, country_code, mobile FROM user_details WHERE (mobile = '".$_GET['username']."' OR email = '".$_GET['username']."' OR username = '".$_GET['username']."') AND password = '".md5($_GET['password'])."'"; 
                    $result = mysqli_query($connect, $qry);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($num_rows > 0 && $row['status'] == 1 && $row['is_block'] == 0) {         
                        $set['result'][] = array('id' => $row['id'], 'fname' => $row['fname'], 'lname' => $row['lname'], 'user_profile' => $row['user_profile'], 'username' => $row['username'], 'email' => $row['email'], 'country_code' => $row['country_code'], 'mobile' => $row['mobile'], 'success' => '1'); 
                    } else if ($num_rows > 0 && $row['status'] == 0) {
                        $set['result'][] = array('msg' => 'Your account has been locked.', 'success' => '0');
                    } else if ($num_rows > 0 && $row['is_block'] == 1) {
                        $set['result'][] = array('msg' => 'Your device has been locked.', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'Account not found. Please sign up first.', 'success' => '0');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else if (isset($_GET['social'])) {
                    $qry = "SELECT status, is_block, id, fname, lname, user_profile, username, email, country_code, mobile FROM user_details WHERE username = '".$_GET['username']."'"; 
                    $result = mysqli_query($connect, $qry);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($num_rows > 0 && $row['status'] == 1 && $row['is_block'] == 0) {         
                        $set['result'][] = array('id' => $row['id'], 'fname' => $row['fname'], 'lname' => $row['lname'], 'user_profile' => $row['user_profile'], 'username' => $row['username'], 'email' => $row['email'], 'country_code' => $row['country_code'], 'mobile' => $row['mobile'], 'success' => '1'); 
                    } else if ($num_rows > 0 && $row['status'] == 0) {
                        $set['result'][] = array('msg' => 'Your account has been locked.', 'success' => '0');
                    } else if ($num_rows > 0 && $row['is_block'] == 1) {
                        $set['result'][] = array('msg' => 'Your device has been locked.', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'Account not found. Please sign up first.', 'success' => '0');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                }
                else{
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getUserProfile() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $id = $_GET['id'];
        
                $qry = "SELECT fname, lname, user_profile, gender, dob, cur_balance, won_balance, bonus_balance, status FROM user_details WHERE id = '$id' ";
                $result = mysqli_query($connect, $qry);  
                $num_rows = mysqli_num_rows($result);
                $row = mysqli_fetch_assoc($result);
                
                if($num_rows > 0) {
                    $set['result'][] = array(
                        'fname' => $row['fname'],
                        'lname' => $row['lname'],
                        'user_profile' => $row['user_profile'],
                        'gender' => $row['gender'],
                        'dob' => $row['dob'],
                        'cur_balance' => $row['cur_balance'],
                        'won_balance' => $row['won_balance'],
                        'bonus_balance' => $row['bonus_balance'],
                        'status' => $row['status'],
                        'success'=>'1'
                    );
                }
                else {
                    $set['result'][]=array('msg' => "No record found.", 'success'=>'0');
                }
        
                header( 'Content-Type: application/json; charset=utf-8' );
                $json = json_encode($set);
                echo $json;
                mysqli_close($connect); 
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function updateUserProfile() {
            include "../include/config.php";
            include "../public/register.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        if(isset($_GET['password'])) {
                            $data = array(
                                'password'  =>  md5($_GET['password'])
                            );
                        } else if(isset($_GET['cur_balance'])) {
                            $data = array(
                                'cur_balance'  =>  $_GET['cur_balance'],
                                'won_balance'  =>  $_GET['won_balance'],
                                'bonus_balance'  =>  $_GET['bonus_balance']
                            );
                        } else {
                            $data = array(
                                'fname'  =>  $_GET['fname'],
                                'lname'  =>  $_GET['lname'],
                                'gender' => $_GET['gender'],
                                'dob' => $_GET['dob'],
                                'modified_date' => date("Y-m-d")
                            );
                        }
                            
                        $user_edit = Update('user_details', $data, "WHERE id = '".$_GET['id']."'");
                        $set['result'][] = array('msg'=>'Updated', 'success'=>'1');
                                 
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);
                        echo $json;
                        mysqli_close($connect);
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function updateUserPhoto() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_POST['access_key']) && $access_key == $_POST['access_key']) {
                $akcode = trim($_POST['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        // check if "user_profile" abd "user_id" is set 
                        if(isset($_POST["user_profile"]) && isset($_POST["id"])) {
                
                            $data = $_POST["user_profile"];
                            $user_id = $_POST["id"];
                
                            $sql = "select count(id) as totrow from user_details where id='$user_id'";
                            $res = mysqli_query($connect, $sql);
                            $res_count = mysqli_fetch_array($res);  
                            $response = array();
                        
                            if($res_count['totrow'] > 0) {
                                $path = "$user_id.jpg";
                                $actualpath = "../admin/upload/avatar/$path";
                                $actuallink = "upload/avatar/$path";
                                
                                $qry = "UPDATE user_details SET user_profile='$actuallink' where id='$user_id'";
                                $result = mysqli_query($connect,$qry);
                        
                                if(mysqli_query($connect,$qry)){
                                    file_put_contents($actualpath,base64_decode($data));
                                }
                                
                                $success = "1";
                                $msg = "Your profile has been update successfully.";
                                array_push($response, array("success"=>$success,"msg"=>$msg));
                                echo json_encode($response);
                                mysqli_close($connect);
                            }
                            else {
                                 $success = "0";
                                 $msg = "Your profile not found in our record.";
                                 array_push($response, array("success"=>$success,"msg"=>$msg));
                                 echo json_encode($response);
                                 mysqli_close($connect);
                            }
                        } 
                        else {
                            echo 'not set';
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function resetPassword() {
            include "../include/config.php";
            include "../public/reset.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $data = array(
                            'password'  =>  md5($_GET['password'])
                        );
                            
                        $user_edit = Update('user_details', $data, "WHERE mobile = '".$_GET['mobile']."'");
                        $set['result'][] = array('msg'=>'Password Updated Successfully!!!', 'success'=>'1');
                                 
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);
                        echo $json;
                        mysqli_close($connect);
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function forgotPassword() {
            include "../include/config.php";
            global $access_key;
            $uniqidStr = md5(uniqid(mt_rand()));
        
            if(!isset($_GET['access_key']) || $access_key != $_GET['access_key']){
                $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                mysqli_close($connect);
            }
            else {    
                $qry = "SELECT email, fname, lname FROM user_details WHERE email = '".$_GET['email']."' AND status = '1' AND is_block = '0'";
                $row = mysqli_fetch_array(mysqli_query($connect, $qry));
                
                $email = $row['email'];
                
                if ($row['email'] != "") {
                    $pwdquery = "update user_details set forgot_pass_identity='{$uniqidStr}' where email='{$email}' AND status = '1' AND is_block = '0'";
                    mysqli_query($connect,$pwdquery);
        
                    //mail to respondent
                    include '../classes/class.phpmailer.php'; // include the class name
                    
                    $mail = new PHPMailer(); // create a new object
					//$mail->IsSMTP(); // enable SMTP
					$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
					$mail->SMTPAuth = true; // authentication enabled
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
					$mail->Host = "smtp.gmail.com";
					$mail->Port = 587; // or 587
					$mail->IsHTML(true);
					$mail->Username = "your_email@gmail.com";
					$mail->Password = "your_password";
					$mail->SetFrom("your_email@gmail.com");
					$mail->Subject = "[IMPORTANT] SkyWinner Forgot Password Information";
                    $mail->Body = '<div style="background-color: #f9f9f9;" align="center"><br />
							  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
								<tbody>
								  <tr>
									<td width="600" valign="top" bgcolor="#FFFFFF"><br>
									  <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
										<tbody>
										  <tr>
											<td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
												<tbody>
												  <tr>
													<td><p style="color: #262626; font-size: 28px; margin-top:0px;"><strong>Dear '.$row['fname'].' '.$row['lname'].'</strong></p>
													  <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;">You have requested a to reset your password, </p>
													  <a href ="http://multigames.skywinner.in/ghfytjvj456/reset-password.php?fp_code='.$uniqidStr.'">Reset Password</a>
													  <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px;">Thanks you,<br />
														SkyWinner App.</p></td>
												  </tr>
												</tbody>
											  </table></td>
										  </tr>
										   
										</tbody>
									  </table></td>
								  </tr>
								  <tr>
									<td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © SkyWinner App.</td>
								  </tr>
								</tbody>
							  </table>
							</div>';
         
                    $mail->AddAddress($_GET['email']);
                    if(!$mail->Send()){
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    }
                    else
                    {
                        $set['result'][]=array('msg' => "Password has been sent on your mail!",'success'=>'1');
                    }
                } else {
                    $set['result'][]=array('msg' => "Email not found in our database! '".$_GET['email']."'",'success'=>'0');        
                }
        
                header( 'Content-Type: application/json; charset=utf-8');
                $json = json_encode($set);
                echo $json;
                mysqli_close($connect);
            }
        }
        
        public function verifyRefer() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                 if (isset($_GET['refer'])) {
                    $qry = "SELECT status, is_block FROM user_details WHERE refer = '".$_GET['refer']."'"; 
                    $result = mysqli_query($connect, $qry);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($num_rows > 0 && $row['status'] == 1 && $row['is_block'] == 0) {         
                        $set['result'][] = array('msg' => 'Promo code Applied.', 'success' => '1'); 
                    } else if ($num_rows > 0 && $row['status'] == 0) {
                        $set['result'][] = array('msg' => 'This promo code has been expired.', 'success' => '0');
                    } else if ($num_rows > 0 && $row['is_block'] == 1) {
                        $set['result'][] = array('msg' => 'This promo code has been expired.', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'Invalid promo code.', 'success' => '0');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else{
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function verifyMobile() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if (isset($_GET['mobile'])) {
                    $qry = "SELECT status, is_block FROM user_details WHERE mobile = '".$_GET['mobile']."'"; 
                    $result = mysqli_query($connect, $qry);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($num_rows > 0 && $row['status'] == 1 && $row['is_block'] == 0) {         
                        $set['result'][] = array('msg' => 'Success', 'success' => '1'); 
                    } else if ($num_rows > 0 && $row['status'] == 0) {
                        $set['result'][] = array('msg' => 'Your account has been locked.', 'success' => '0');
                    } else if ($num_rows > 0 && $row['is_block'] == 1) {
                        $set['result'][] = array('msg' => 'Your device has been locked.', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'User mobile number not found. Try to register with same number.', 'success' => '0');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else{
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function verifyRegister() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if (isset($_GET['mobile'])) {
                    $sql = "SELECT count(id) as num_res FROM user_details WHERE device_id = '".$_GET['device_id']."' AND is_block = '1'"; 
                    $res = mysqli_query($connect, $sql);
                    $res_rows = mysqli_fetch_array($res);
                    
                    $qry = "SELECT status, is_block FROM user_details WHERE mobile = '".$_GET['mobile']."' OR email = '".$_GET['email']."' OR username = '".$_GET['username']."'"; 
                    $result = mysqli_query($connect, $qry);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($res_rows['num_res'] > 0) {           
                        $set['result'][] = array('msg' => 'Your device has been locked. Please contact us.', 'success' => '0'); 
                    } else if ($num_rows > 0 && $row['status'] == 1 && $row['is_block'] == 0) {          
                        $set['result'][] = array('msg' => 'This username, email id or mobile number already used.', 'success' => '0'); 
                    } else if ($num_rows > 0 && $row['status'] == 0) {
                        $set['result'][] = array('msg' => 'Your account has been locked. Please contact us', 'success' => '0');
                    } else if ($num_rows > 0 && $row['is_block'] == 1) {
                        $set['result'][] = array('msg' => 'Your device has been locked. Please contact us.', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'Success', 'success' => '1');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else{
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        
        public function getGameList() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $flag = array(); 
                        
                $query = "SELECT id, title, banner, url, type FROM game_details ORDER BY id ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchPlay() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $game_id = $_GET['game_id'];
                $flag = array();
                
                $query = "SELECT t1.id, t1.game_id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.prize_pool, t1.banner, t1.per_kill, t1.entry_fee, t1.entry_type, t1.version, t1.map, t1.is_private, t1.match_type, t1.sponsored_by, t1.match_status, t1.match_desc, t1.is_cancel, t1.cancel_reason, t1.platform, t1.pool_type, t1.admin_share, t1.bet_status, t2.match_id, t2.access_key, t2.room_id, t2.room_pass, t2.room_size, t2.total_joined, t3.slot, t3.id As joined_status, COUNT(t3.id) AS user_joined, t4.rules, t5.image
                FROM match_details t1 
                LEFT JOIN room_details t2 ON t1.id = t2.match_id
                LEFT JOIN participant_details t3 ON (t1.id = t3.match_id AND t3.user_id='$user_id')
                LEFT JOIN tbl_rules t4 ON t1.match_rules = t4.rule_id
                LEFT JOIN tbl_image t5 ON t1.banner = t5.img_id
                WHERE t1.game_id='$game_id' AND t1.match_status='0' AND t1.is_del='0' GROUP BY t1.id ORDER BY t1.id ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchLive() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $game_id = $_GET['game_id'];
                $flag = array();
                
                $query = "SELECT t1.id, t1.game_id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.pool_type, t1.admin_share, t1.prize_pool, t1.match_desc, t1.banner, t1.per_kill, t1.entry_fee, t1.entry_type, t1.version, t1.map, t1.is_private, t1.match_type, t1.sponsored_by, t1.spectate_url, t1.match_status, t1.is_cancel, t1.cancel_reason, t1.platform, t2.match_id, t2.room_id, t2.room_pass, t2.room_size, t2.total_joined, t3.id As joined_status, t3.slot, t4.rules, t5.title AS game,t5.url 
                FROM match_details t1 
                LEFT JOIN room_details t2 ON t1.id = t2.match_id
                LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                LEFT JOIN tbl_rules t4 ON t1.match_rules = t4.rule_id
                LEFT JOIN game_details t5 ON t1.game_id = t5.id
                WHERE t1.game_id='$game_id' AND t1.match_status='1' AND t1.is_del='0' GROUP BY t1.id ORDER BY t1.id ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchResult() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $game_id = $_GET['game_id'];
                $flag = array();
                
                $query = "SELECT t1.id, t1.game_id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.pool_type, t1.admin_share, t1.prize_pool, t1.banner, t1.per_kill, t1.entry_fee, t1.entry_type, t1.version, t1.map, t1.is_private, t1.match_type, t1.sponsored_by, t1.match_notes, t1.spectate_url, t1.match_status, t1.match_desc, t1.platform, t2.total_joined, t3.id As joined_status 
                FROM match_details t1 
                LEFT JOIN room_details t2 ON t1.id = t2.match_id
                LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                WHERE t1.game_id='$game_id' AND t1.match_status='3' AND t1.is_del='0' GROUP BY t1.id ORDER BY t1.id DESC LIMIT 10";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchUpcoming() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $game_id = $_GET['game_id'];
                $flag = array();
                
                $query = "SELECT t1.id, t1.game_id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.prize_pool, t1.banner, t1.per_kill, t1.entry_fee, t1.entry_type, t1.version, t1.map, t1.is_private, t1.match_type, t1.sponsored_by, t1.match_status, t1.match_desc, t1.is_cancel, t1.cancel_reason, t1.platform, t1.pool_type, t1.admin_share, t1.bet_status, t2.match_id, t2.access_key, t2.room_id, t2.room_pass, t2.room_size, t2.total_joined, t3.slot, t3.id As joined_status, COUNT(t3.id) AS user_joined, t4.rules, t5.image
                FROM match_details t1 
                LEFT JOIN room_details t2 ON t1.id = t2.match_id
                LEFT JOIN participant_details t3 ON (t1.id = t3.match_id AND t3.user_id='$user_id')
                LEFT JOIN tbl_rules t4 ON t1.match_rules = t4.rule_id
                LEFT JOIN tbl_image t5 ON t1.banner = t5.img_id
                WHERE t1.game_id='$game_id' AND t1.match_status='0' AND t1.is_del='0' AND t3.user_id='$user_id' GROUP BY t1.id ORDER BY t1.id ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchOngoing() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $game_id = $_GET['game_id'];
                $flag = array();
                
                $query = "SELECT t1.id, t1.game_id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.pool_type, t1.admin_share, t1.prize_pool, t1.match_desc, t1.banner, t1.per_kill, t1.entry_fee, t1.entry_type, t1.version, t1.map, t1.is_private, t1.match_type, t1.sponsored_by, t1.spectate_url, t1.match_status, t1.is_cancel, t1.cancel_reason, t1.platform, t2.match_id, t2.room_id, t2.room_pass, t2.room_size, t2.total_joined, t3.id As joined_status, t3.slot, t4.rules, t5.title AS game,t5.url 
                FROM match_details t1 
                LEFT JOIN room_details t2 ON t1.id = t2.match_id
                LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                LEFT JOIN tbl_rules t4 ON t1.match_rules = t4.rule_id
                LEFT JOIN game_details t5 ON t1.game_id = t5.id
                WHERE t1.game_id='$game_id' AND t1.match_status='1' AND t1.is_del='0' AND t3.user_id='$user_id' GROUP BY t1.id ORDER BY t1.id ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchCompleted() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $game_id = $_GET['game_id'];
                $flag = array();
                
                $query = "SELECT t1.id, t1.game_id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.pool_type, t1.admin_share, t1.prize_pool, t1.banner, t1.per_kill, t1.entry_fee, t1.entry_type, t1.version, t1.map, t1.is_private, t1.match_type, t1.sponsored_by, t1.match_notes, t1.spectate_url, t1.match_status, t1.match_desc, t1.platform, t2.total_joined, t3.id As joined_status 
                FROM match_details t1 
                LEFT JOIN room_details t2 ON t1.id = t2.match_id
                LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                WHERE t1.game_id='$game_id' AND t1.match_status='3' AND t1.is_del='0' AND t3.user_id='$user_id' GROUP BY t1.id ORDER BY t1.id DESC LIMIT 10";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
            
        public function getMatchTimer() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $match_id = $_GET['match_id'];
                $current_time = time();
                
                $qry = "SELECT time FROM match_details where id = '$match_id'";
                $result = mysqli_query($connect, $qry);  
                $row = mysqli_fetch_assoc($result);
                                 
                $set['result'][] = array(
                    'time' => $row['time'],
                    'msg' => $current_time,
                    'success' => '1'
                );
        
                header( 'Content-Type: application/json; charset=utf-8' );
                $json = json_encode($set);
                echo $json;
                mysqli_close($connect);
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        public function getRoomDetails() {
    		include "../include/config.php";          
            global $access_key;
			
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
            
            	$match_id = $_GET['match_id'];
						
				$qry = "SELECT room_id, room_pass FROM room_details where match_id = '$match_id'";
				$result = mysqli_query($connect, $qry);	 
				$row = mysqli_fetch_assoc($result);
				mysqli_close($connect);
				
				$set['result'][] = array(
					'room_id' => $row['room_id'],
					'room_pass' => $row['room_pass'],
					'success' => '1'
				);
		
				header( 'Content-Type: application/json; charset=utf-8' );
				$json = json_encode($set);
				echo $json;
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	mysqli_close($connect);
            }
    	}
    	
        
        public function getLotteryList() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $currenttime = time() + 19800;
                $flag = array();
                
                $query = "SELECT t1.id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.time AS timestamp, t1.fee, t1.prize, t1.size,
                t1.joined AS total_joined,
                COUNT(DISTINCT(t2.id)) AS my_number, 
                t3.rules, 
                t4.image,
                '$currenttime' AS currenttime
                FROM lottery_details t1
                LEFT JOIN result_details t2 ON (t1.id = t2.lottery_id AND t2.user_id='$user_id')
                LEFT JOIN tbl_rules t3 ON t1.rules = t3.rule_id
                LEFT JOIN tbl_image t4 ON t1.cover = t4.img_id
                WHERE t1.status='0' GROUP By t1.id ORDER BY t1.id DESC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getLotteryParticipant() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $lottery_id = $_GET['lottery_id'];
                        $flag = array();
                
                        $query = "SELECT id, name, lottery_no FROM result_details WHERE lottery_id='$lottery_id' ORDER BY lottery_no ASC";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        public function getLotteryMy() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $lottery_id = $_GET['lottery_id'];
                        $user_id = $_GET['user_id'];
                        $flag = array();
                        
                        $query = "SELECT id, name, lottery_no FROM result_details WHERE lottery_id ='$lottery_id' AND user_id ='$user_id'";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getLotteryResult() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $flag = array();
                
                $query = "SELECT t1.id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y') AS time, t1.prize,
                t2.name, t2.lottery_no,
                t3.image
                FROM lottery_details t1 
                LEFT JOIN result_details t2 ON (t1.id = t2.lottery_id)
                LEFT JOIN tbl_image t3 ON t1.cover = t3.img_id
                WHERE t2.win='1' AND t1.status='1' ORDER BY t1.id DESC LIMIT 0,5";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        
        public function joinLottery() {
            include "../include/config.php";
            include "../public/join.php";
            global $access_key;
            global $use_of_bonus;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if(isset($_GET['lottery_id']) && isset($_GET['user_id'])) {
        
                    $lottery_id = $_GET['lottery_id'];
                    $user_id = $_GET['user_id'];
                    $name = $_GET['name'];
                    $entry_fee = $_GET['fee'];
                    $bonus = round(($use_of_bonus * $entry_fee) / 100);
                    
                    $qry0 = "SELECT size, joined FROM lottery_details WHERE id = '".$_GET['lottery_id']."'"; 
                    $row0 = mysqli_fetch_array(mysqli_query($connect,$qry0));
                    $room_size = $row0['size'];
                    $total_joined = $row0['joined']+1;
                    
                    if($total_joined < $room_size) {
                        $qry2 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                        $row2 = mysqli_fetch_array(mysqli_query($connect,$qry2));
                        $cur_balance1 = $row2['cur_balance']; 
                        $won_balance1 = $row2['won_balance'];
                        $bonus_balance1 = $row2['bonus_balance'];
                        
                        $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                        if ($check_balance >= $entry_fee) {
            
                            if ($bonus_balance1 >= $bonus) {
                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                $diff = $depoit_balance1 + $bonus;
                                if($diff >= $entry_fee) {
                                    $won_balance2 = $won_balance1;
                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                                else {
                                    $fee = $entry_fee - $diff;
                                    $won_balance2 = $won_balance1 - $fee;
                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                            }
                            else {
                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                $diff = $depoit_balance1 + $bonus_balance1;
                                if($diff >= $entry_fee) {
                                    $won_balance2 = $won_balance1;
                                    $bonus_balance2 = '0';
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                                else {
                                    $fee = $entry_fee - $diff;
                                    $won_balance2 = $won_balance1 - $fee;
                                    $bonus_balance2 = '0';
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                            }
                    
                            $qry3 = "SELECT joined FROM lottery_details WHERE id = '".$_GET['lottery_id']."'"; 
                            $row3 = mysqli_fetch_array(mysqli_query($connect,$qry3));
                            $joined = $row3['joined']+1; 
                        
                            $data1 = array(
                                'lottery_id'  => $lottery_id,
                                'user_id'  => $user_id,
                                'name'  => $name
                            );
                        
                            $data2 = array(
                                'cur_balance'  =>  $cur_balance2,
                                'won_balance'  =>  $won_balance2,
                                'bonus_balance'  =>  $bonus_balance2
                            );
                        
                            $data3 = array(
                                'joined'  =>  $joined
                            );
                                                    
                            $qry4 = Insert('result_details', $data1);
                            $qry5 = Update('user_details', $data2,"WHERE id = '".$_GET['user_id']."'");
                            $qry6 = Update('lottery_details', $data3,"WHERE id = '".$_GET['lottery_id']."'");
                        
                            $set['result'][] = array('msg' => "You succesfully registred on this draw.", 'success'=>'1');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        }
                        else {
                            $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        }
                    }
                    else if($total_joined == $room_size) {
                        $qry2 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                        $row2 = mysqli_fetch_array(mysqli_query($connect,$qry2));
                        $cur_balance1 = $row2['cur_balance']; 
                        $won_balance1 = $row2['won_balance'];
                        $bonus_balance1 = $row2['bonus_balance'];
                        
                        $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                        if ($check_balance >= $entry_fee) {
            
                            if ($bonus_balance1 >= $bonus) {
                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                $diff = $depoit_balance1 + $bonus;
                                if($diff >= $entry_fee) {
                                    $won_balance2 = $won_balance1;
                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                                else {
                                    $fee = $entry_fee - $diff;
                                    $won_balance2 = $won_balance1 - $fee;
                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                            }
                            else {
                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                $diff = $depoit_balance1 + $bonus_balance1;
                                if($diff >= $entry_fee) {
                                    $won_balance2 = $won_balance1;
                                    $bonus_balance2 = '0';
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                                else {
                                    $fee = $entry_fee - $diff;
                                    $won_balance2 = $won_balance1 - $fee;
                                    $bonus_balance2 = '0';
                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                }
                            }
                    
                            $qry3 = "SELECT joined FROM lottery_details WHERE id = '".$_GET['lottery_id']."'"; 
                            $row3 = mysqli_fetch_array(mysqli_query($connect,$qry3));
                            $joined = $row3['joined']+1; 
                        
                            $qry4 = "SELECT id FROM result_details WHERE lottery_id = '".$_GET['lottery_id']."' ORDER BY RAND() LIMIT 0,1"; 
                            $row4 = mysqli_fetch_array(mysqli_query($connect,$qry4));
                            $rand_id = $row4['id']; 
                        
                            $data1 = array(
                                'lottery_id'  => $lottery_id,
                                'user_id'  => $user_id,
                                'name'  => $name
                            );
                        
                            $data2 = array(
                                'cur_balance'  =>  $cur_balance2,
                                'won_balance'  =>  $won_balance2,
                                'bonus_balance'  =>  $bonus_balance2
                            );
                        
                            $data3 = array(
                                'joined'  =>  $joined,
                                'status'  =>  '1'
                            );
                            
                            $data4 = array(
                                'win'  =>  '1'
                            );
                            
                            $qry4 = Insert('result_details', $data1);
                            $qry5 = Update('user_details', $data2,"WHERE id = '".$_GET['user_id']."'");
                            $qry6 = Update('lottery_details', $data3,"WHERE id = '".$_GET['lottery_id']."'");
                            $qry7 = Update('result_details', $data4,"WHERE id = '$rand_id'");
                        
                            $set['result'][] = array('msg' => "You succesfully registred on this draw.", 'success'=>'1');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        }
                        else {
                            $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        }
                    } else {
                        $set['result'][]=array('msg' => "Lottery is.", 'success'=>'0');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
      
        public function getMatchParticipants() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $match_id = $_GET['match_id'];
                $flag = array();
        
                $query = "SELECT id, user_id, pubg_id, slot FROM participant_details WHERE match_id='$match_id' AND is_canceled = '0' ORDER BY slot ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getMyEntries() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $match_id = $_GET['match_id'];
                        $user_id = $_GET['user_id'];
                        $flag = array();
                        
                        $query = "SELECT id, user_id, match_id, pubg_id, slot, is_canceled FROM participant_details WHERE match_id ='$match_id' AND user_id ='$user_id' AND is_canceled = '0'";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function cancelMyEntries() {
            include "../include/config.php";
            include "../public/register.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $user_id = $_GET['user_id'];
                        $match_id = $_GET['match_id'];
                        //$pubg_id = $_GET['pubg_id'];
                        $order_id = time().$user_id;
                        
                        $qry1 = "SELECT entry_fee FROM match_details WHERE id = '$match_id'"; 
                        $matchdata = mysqli_fetch_array(mysqli_query($connect,$qry1));
                        $entry_fee = $matchdata['entry_fee'];
                        
                        $qry2 = "SELECT cur_balance FROM user_details WHERE id = '$user_id'"; 
                        $userdata = mysqli_fetch_array(mysqli_query($connect,$qry2));
                        $cur_balance = $userdata['cur_balance'];
                        $new_cur_balance = $cur_balance + $entry_fee;
                        
                        $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '$match_id'"; 
                        $joindata = mysqli_fetch_array(mysqli_query($connect,$qry3));
                        $total_joined = $joindata['total_joined'];  
                        $joined=$total_joined-1;
                                                    
                        $sql = "DELETE FROM participant_details WHERE user_id = '$user_id' AND match_id = '$match_id'";
                        $result = mysqli_query($connect, $sql);  
                        
                        if(mysqli_query($connect, $sql)) {
                            $data1 = array(
                                'cur_balance'  =>  $new_cur_balance
                            );
                            
                            $data2 = array(
                                'total_joined'  =>  $joined
                            );
                            
                            $data3 = array(
                                'user_id'  => $user_id,
                                'order_id'  => $order_id,
                                'payment_id'  => $order_id,
                                'req_amount'  => $entry_fee,
                                'coins_used'  => $entry_fee,
                                'getway_name'  => 'System',
                                'remark'  => 'Refund Entry Fee',
                                'type'  =>  '1',
                                'date' => time(),
                                'status'  =>  '1'
                            );
                                                    
                            $qry4 = Update('user_details', $data1,"WHERE id = '$user_id'");
                            $qry5 = Update('room_details', $data2,"WHERE match_id = '$match_id'");
                            $qry6 = Insert('transaction_details', $data3);  
                            $set['result'][] = array('msg' => "You have successfully cancelled all entries in this match. Also entry fee refunded to your wallet.",'success'=>'1');
                
                            header( 'Content-Type: application/json; charset=utf-8' );
                            echo $json = json_encode($set);
                            mysqli_close($connect);
                        }
                        else {
                            $set['result'][] = array('msg' => "oops! failed cancelled entries.",'success'=>'1');
                            header( 'Content-Type: application/json; charset=utf-8' );
                            echo $json = json_encode($set);
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function updateMyEntries() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $id = $_GET['id'];
                        $match_id = $_GET['match_id'];
                        $user_id = $_GET['user_id'];
                        $pubg_id = $_GET['pubg_id'];
                        
                        $qry = "SELECT count(id) as totrow FROM participant_details WHERE match_id = '$match_id' AND pubg_id = '$pubg_id'"; 
                        $sel = mysqli_query($connect, $qry);
                        $res_count = mysqli_fetch_array($sel);
                
                        if($res_count['totrow'] > 0) {
                            $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            mysqli_close($connect);
                        }
                        else {
                            $sql = "UPDATE participant_details SET pubg_id = '$pubg_id' WHERE id = '$id' AND user_id = '$user_id'";
                            
                            if(mysqli_query($connect, $sql)) {
                                $set['result'][] = array('msg' => "Successfully updated game username.",'success'=>'1');
                                header( 'Content-Type: application/json; charset=utf-8' );
                                echo $json = json_encode($set);
                            }
                            else {
                                $set['result'][] = array('msg' => "oops! failed update game username.",'success'=>'1');
                                header( 'Content-Type: application/json; charset=utf-8' );
                                echo $json = json_encode($set);
                            }
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
    
        }
        
        public function getMatchWinner() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $match_id = $_GET['match_id'];
                $flag = array();
        
                $query = "SELECT id, user_id, pubg_id, kills, position, prize FROM participant_details WHERE match_id='$match_id' AND position='1' AND is_canceled = '0' GROUP BY pubg_id ORDER BY prize DESC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getMatchRunnerup() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $match_id = $_GET['match_id'];
                $flag = array();
        
                $query = "SELECT id, user_id, pubg_id, kills, position, prize FROM participant_details WHERE match_id='$match_id' AND is_canceled = '0' AND position BETWEEN '2' AND '10' GROUP BY pubg_id ORDER BY position ASC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMatchFullResult() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $match_id = $_GET['match_id'];
                $flag = array();
        
                $query = "SELECT id, user_id, pubg_id, kills, prize FROM participant_details WHERE match_id='$match_id' AND is_canceled = '0' GROUP BY pubg_id ORDER BY prize DESC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }   
        
        public function getMySummary() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
        
                $qry = "SELECT COUNT(DISTINCT(t1.match_id)) AS maches_played, sum(t1.kills) AS total_kills, sum(t1.prize) AS amount_won 
                FROM participant_details t1
                LEFT JOIN match_details t2 ON (t1.match_id = t2.id and t1.user_id='$user_id')
                WHERE t1.user_id = '$user_id' AND t2.is_cancel='0' AND t2.is_del='0'";
                $result = mysqli_query($connect, $qry);  
                $row = mysqli_fetch_assoc($result);
                                 
                $set['result'][] = array(
                    'maches_played' => $row['maches_played'],
                    'total_kills'=>$row['total_kills'],
                    'amount_won'=>$row['amount_won'],
                    'success'=>'1'
                );
        
                header( 'Content-Type: application/json; charset=utf-8' );
                $json = json_encode($set);
                echo $json;
                mysqli_close($connect);
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
                
        public function getMyStatistics() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $flag = array();
        
                $query = "SELECT t1.id, t1.title, from_unixtime(t1.time+19800, '%d/%m/%Y at %h:%i %p') AS time, t1.entry_fee, SUM(t3.prize) AS prize 
                FROM participant_details t3 
                LEFT JOIN match_details t1 ON (t3.match_id = t1.id and t3.user_id='$user_id')
                WHERE t3.user_id='$user_id' AND t1.is_cancel='0' AND t1.is_del='0' GROUP BY t1.id ORDER BY t1.id DESC LIMIT 0,50";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getMyTransactions() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $user_id = $_GET['user_id'];
                $flag = array();
        
                $query = "SELECT id, user_id, order_id, payment_id, req_amount, remark, type, from_unixtime(date, '%d-%m-%Y') AS date, getway_name, coins_used, status, request_name, req_from FROM transaction_details WHERE user_id='$user_id' ORDER BY id DESC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
    
        public function getTopPlayers() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
            
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $flag = array();
                        
                        $query = "SELECT t2.username AS pubg_id, sum(t3.prize) AS prize 
                        FROM participant_details t3 
                        LEFT JOIN user_details t2 ON (t2.id = t3.user_id)
                        LEFT JOIN match_details t1 ON (t3.match_id = t1.id)
                        WHERE t1.is_cancel='0' AND t1.is_del='0'
                        GROUP BY t2.username ORDER BY sum(t3.prize) DESC LIMIT 0,10";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getMyReferralsSummary() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $refer_code = $_GET['refer_code'];
        
                        $qry = "SELECT count(refer_code) AS refer_code, sum(refer_points) AS refer_points FROM referral_details
                        WHERE refer_code='$refer_code'";
                        $result = mysqli_query($connect, $qry);  
                        $row = mysqli_fetch_assoc($result);
                                         
                        $set['result'][] = array(
                            'refer_code' => $row['refer_code'],
                            'refer_points'=>$row['refer_points'],
                            'success'=>'1'
                        );
                
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);
                        echo $json;
                        mysqli_close($connect);
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getMyReferralsList() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $refer_code = $_GET['refer_code'];
                        $flag = array();
                
                        $query = "SELECT t1.refer_date, t1.refer_status, t2.fname, t2.lname 
                        FROM referral_details t1
                        LEFT JOIN user_details t2 ON t1.username = t2.username
                        WHERE t1.refer_code='$refer_code'";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getTopLeaders() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $flag = array();
                        
                        $query = "SELECT sum(t1.refer_points) AS refer_points, t2.fname, t2.lname 
                        FROM referral_details t1
                        LEFT JOIN user_details t2 ON t1.refer_code = t2.refer
                        GROUP BY t1.refer_code ORDER BY refer_points DESC LIMIT 0,10";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getMyRewardsSummary() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $username = $_GET['username'];
        
                        $qry = "SELECT count(username) AS rewards, sum(reward_points) AS earnings FROM rewarded_details
                        WHERE username='$username'";
                        $result = mysqli_query($connect, $qry);  
                        $row = mysqli_fetch_assoc($result);
                                         
                        $set['result'][] = array(
                            'rewards' => $row['rewards'],
                            'earnings'=>$row['earnings'],
                            'success'=>'1'
                        );
                
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);
                        echo $json;
                        mysqli_close($connect);
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
                
        public function getMyRewardsList() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $username = $_GET['username'];
                        $flag = array();
                
                        $query = "SELECT from_unixtime(reward_date, '%d-%m-%Y') AS reward_date, COUNT(reward_date) AS reward_count, SUM(reward_points) AS reward_points 
                        FROM rewarded_details
                        WHERE username='$username' GROUP BY from_unixtime(reward_date, '%d-%m-%Y') ORDER BY reward_date DESC";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getTopRewards() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
            
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $flag = array();
                        
                        $query = "SELECT sum(t1.reward_points) AS reward_points, t2.fname, t2.lname 
                        FROM rewarded_details t1
                        LEFT JOIN user_details t2 ON t1.username = t2.username
                        GROUP BY t1.username ORDER BY reward_points DESC LIMIT 0,10";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
    
        
        public function joinMatch() {
            include "../include/config.php";
            include "../public/join.php";
            global $access_key;
            global $referer_bonus;
            global $use_of_bonus;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if(isset($_GET['match_id']) && isset($_GET['pubg_id1'])) {
        
                    $entry_type = $_GET['entry_type'];
                    $entry_fee = $_GET['entry_fee'];
                    $match_type = $_GET['match_type'];
                    $is_private = $_GET['is_private'];
                    $accessKey = $_GET['accessKey'];
                    $bonus = round(($use_of_bonus * $entry_fee) / 100);
                    
                    $qry0 = "SELECT room_size, total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                    $size = mysqli_fetch_array(mysqli_query($connect,$qry0));
                    $room_size = $size['room_size'];
                    $total_joined = $size['total_joined'];
                    
                    if($total_joined < $room_size) {
                        if($is_private == "yes") {
                            $qry3 = "SELECT private_match_code FROM match_details WHERE id = '".$_GET['match_id']."'"; 
                            $private_match_code = mysqli_fetch_array(mysqli_query($connect,$qry3));
                            $private_match_code = $private_match_code['private_match_code'];
                            
                            if ($private_match_code == $accessKey) {
                                if ($match_type == 'Tournament') {
                                    $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND pubg_id = '".$_GET['pubg_id1']."'"; 
                                    $sel1 = mysqli_query($connect, $qry1);
                                    $sel1_res = mysqli_fetch_array($sel1);
                                    
                                    if($sel1_res['row_count'] > 0) {
                                        $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                        mysqli_close($connect);
                                    }
                                    else {
                                        if ($entry_type == 'Paid') {
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $sel4 = mysqli_query($connect, $qry4);
                                                
                                            if(mysqli_num_rows($sel4) > 0) {
                                                $today = date("Y-m-d");
                                               
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                    
                                                $qry5 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                                $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $refer_code = $refer_code['refer_code'];    
                                                
                                                $qry6 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                                $balance = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                                $cur_balance = $balance['cur_balance']; 
                                                $bonus_balance = $balance['bonus_balance'];
                                                $cur_balance=$cur_balance+$referer_bonus;
                                                $bonus_balance=$bonus_balance+$referer_bonus;
                                                
                                                $qry7 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry8 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry8));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                                                    
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'refer_points'  =>  $referer_bonus,
                                                        'refer_status'  => '1',
                                                        'refer_date'=>$today
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance,
                                                        'bonus_balance'  =>  $bonus_balance
                                                    );
                                                    
                                                    $data4 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data5 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                        $qry9 = Insert('participant_details', $data1);
                                                    }
                                                    $qry10 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                    $qry11 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                    
                                                    $qry12 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry13 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                            else {
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                                
                                                $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                        $qry6 = Insert('participant_details', $data1);              
                                                    }
                                                    $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                    $qry6 = Insert('participant_details', $data1);
                                                }
                                                $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                    }
                                }
                                else if ($match_type == 'Solo') {
                                    $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND pubg_id = '".$_GET['pubg_id1']."'"; 
                                    $sel1 = mysqli_query($connect, $qry1);
                                    $sel1_res = mysqli_fetch_array($sel1);
                                    
                                    if($sel1_res['row_count'] > 0) {
                                        $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                        mysqli_close($connect);
                                    }
                                    else {
                                        if ($entry_type == 'Paid') {
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $sel4 = mysqli_query($connect, $qry4);
                                                
                                            if(mysqli_num_rows($sel4) > 0) {
                                                $today = date("Y-m-d");
                                               
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                    
                                                $qry5 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                                $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $refer_code = $refer_code['refer_code'];    
                                                
                                                $qry6 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                                $balance = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                                $cur_balance = $balance['cur_balance']; 
                                                $bonus_balance = $balance['bonus_balance'];
                                                $cur_balance=$cur_balance+$referer_bonus;
                                                $bonus_balance=$bonus_balance+$referer_bonus;
                                                
                                                $qry7 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry8 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry8));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                                                    
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'refer_points'  =>  $referer_bonus,
                                                        'refer_status'  => '1',
                                                        'refer_date'=>$today
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance,
                                                        'bonus_balance'  =>  $bonus_balance
                                                    );
                                                    
                                                    $data4 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data5 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                        $qry9 = Insert('participant_details', $data1);
                                                    }
                                                    $qry10 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                    $qry11 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                    
                                                    $qry12 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry13 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                            else {
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                                
                                                $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                        $qry6 = Insert('participant_details', $data1);              
                                                    }
                                                    $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                    $qry6 = Insert('participant_details', $data1);
                                                }
                                                $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                    }
                                }
                                else if ($match_type == 'Duo') {
                                    $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND (pubg_id = '".$_GET['pubg_id1']."' OR pubg_id = '".$_GET['pubg_id2']."')"; 
                                    $sel1 = mysqli_query($connect, $qry1);
                                    $sel1_res = mysqli_fetch_array($sel1);
                                    
                                    if($sel1_res['row_count'] > 0) {
                                        $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                        mysqli_close($connect);
                                    }
                                    else {
                                        if ($entry_type == 'Paid') {
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $sel4 = mysqli_query($connect, $qry4);
                                                
                                            if(mysqli_num_rows($sel4) > 0) {
                                                $today = date("Y-m-d");
                                                
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                               
                                                $qry5 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                                $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $refer_code = $refer_code['refer_code'];    
                                                
                                                $qry6 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                                $balance = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                                $cur_balance = $balance['cur_balance']; 
                                                $bonus_balance = $balance['bonus_balance'];
                                                $cur_balance=$cur_balance+$referer_bonus;
                                                $bonus_balance=$bonus_balance+$referer_bonus;
                                                
                                                $qry7 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry8 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry8));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data11 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id2'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'refer_points'  =>  $referer_bonus,
                                                        'refer_status'  => '1',
                                                        'refer_date'=>$today
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance,
                                                        'bonus_balance'  =>  $bonus_balance
                                                    );
                                                    
                                                    $data4 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data5 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                    $qry9 = Insert('participant_details', $data1);
                                                    }
                                                    
                                                    if ($_GET['pubg_id2']!="null") {
                                                    $qry99 = Insert('participant_details', $data11);
                                                    }
                                                    
                                                    $qry10 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                    $qry11 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                    
                                                    $qry12 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry13 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                            else {
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                                
                                                $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                        
                                                    $data11 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id2'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                    $qry6 = Insert('participant_details', $data1);  
                                                    }
                                                    
                                                    if ($_GET['pubg_id2']!="null") {
                                                    $qry66 = Insert('participant_details', $data11);
                                                    }
                                                    
                                                    $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                    
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry6 = Insert('participant_details', $data1);      }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry66 = Insert('participant_details', $data11);
                                                }
                                                $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }   
                                    }
                                }
                                else if ($match_type == 'Squad') {
                                    $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND (pubg_id = '".$_GET['pubg_id1']."' OR pubg_id = '".$_GET['pubg_id2']."' OR pubg_id = '".$_GET['pubg_id3']."' OR pubg_id = '".$_GET['pubg_id4']."')"; 
                                    $sel1 = mysqli_query($connect, $qry1);
                                    $sel1_res = mysqli_fetch_array($sel1);
                                    
                                    if($sel1_res['row_count'] > 0) {
                                        $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                        mysqli_close($connect);
                                    }
                                    else {
                                        if ($entry_type == 'Paid') {
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $sel4 = mysqli_query($connect, $qry4);
                                                
                                            if(mysqli_num_rows($sel4) > 0) {
                                                $today = date("Y-m-d");
                                                
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                               
                                                $qry5 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                                $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $refer_code = $refer_code['refer_code'];    
                                                
                                                $qry6 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                                $balance = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                                $cur_balance = $balance['cur_balance']; 
                                                $bonus_balance = $balance['bonus_balance'];
                                                $cur_balance=$cur_balance+$referer_bonus;
                                                $bonus_balance=$bonus_balance+$referer_bonus;
                                                
                                                $qry7 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry8 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry8));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                        
                                                    $data11 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id2'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id3'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data1111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id4'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'refer_points'  =>  $referer_bonus,
                                                        'refer_status'  => '1',
                                                        'refer_date'=>$today
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance,
                                                        'bonus_balance'  =>  $bonus_balance
                                                    );
                                                    
                                                    $data4 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data5 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                    $qry9 = Insert('participant_details', $data1);
                                                    }
                                                    
                                                    if ($_GET['pubg_id2']!="null") {
                                                    $qry99 = Insert('participant_details', $data11);
                                                    }
                                                    
                                                    if ($_GET['pubg_id3']!="null") {
                                                    $qry999 = Insert('participant_details', $data111);
                                                    }
                                                    
                                                    if ($_GET['pubg_id4']!="null") {
                                                    $qry999 = Insert('participant_details', $data1111);
                                                    }
                                                    
                                                    $qry10 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                    $qry11 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                    
                                                    $qry12 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry13 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                            else {
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                                
                                                $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                        
                                                    $data11 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id2'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id3'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data1111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id4'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                        $qry6 = Insert('participant_details', $data1);  
                                                    }
                                                    
                                                    if ($_GET['pubg_id2']!="null") {
                                                        $qry66 = Insert('participant_details', $data11);
                                                    }
                                                    
                                                    if ($_GET['pubg_id3']!="null") {
                                                        $qry666 = Insert('participant_details', $data111);
                                                    }
                                                    
                                                    if ($_GET['pubg_id4']!="null") {
                                                        $qry6666 = Insert('participant_details', $data1111);
                                                    }
                                                    
                                                    $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id3'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data1111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id4'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry6 = Insert('participant_details', $data1);  
                                                }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry66 = Insert('participant_details', $data11);
                                                }
                                                if ($_GET['pubg_id3']!="null") {
                                                $qry666 = Insert('participant_details', $data111);
                                                }
                                                if ($_GET['pubg_id4']!="null") {    
                                                $qry6666 = Insert('participant_details', $data1111);
                                                }
                                                    
                                                $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }   
                                    }
                                }
                                else if ($match_type == 'TDM') {
                                    $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND (pubg_id = '".$_GET['pubg_id1']."' OR pubg_id = '".$_GET['pubg_id2']."' OR pubg_id = '".$_GET['pubg_id3']."' OR pubg_id = '".$_GET['pubg_id4']."')"; 
                                    $sel1 = mysqli_query($connect, $qry1);
                                    $sel1_res = mysqli_fetch_array($sel1);
                                    
                                    if($sel1_res['row_count'] > 0) {
                                        $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                        mysqli_close($connect);
                                    }
                                    else {
                                        if ($entry_type == 'Paid') {
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $sel4 = mysqli_query($connect, $qry4);
                                                
                                            if(mysqli_num_rows($sel4) > 0) {
                                                $today = date("Y-m-d");
                                                
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                               
                                                $qry5 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                                $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $refer_code = $refer_code['refer_code'];    
                                                
                                                $qry6 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                                $balance = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                                $cur_balance = $balance['cur_balance']; 
                                                $bonus_balance = $balance['bonus_balance'];
                                                $cur_balance=$cur_balance+$referer_bonus;
                                                $bonus_balance=$bonus_balance+$referer_bonus;
                                                
                                                $qry7 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry8 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry8));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                        
                                                    $data11 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id2'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id3'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data1111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id4'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'refer_points'  =>  $referer_bonus,
                                                        'refer_status'  => '1',
                                                        'refer_date'=>$today
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance,
                                                        'bonus_balance'  =>  $bonus_balance
                                                    );
                                                    
                                                    $data4 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data5 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                    $qry9 = Insert('participant_details', $data1);
                                                    }
                                                    
                                                    if ($_GET['pubg_id2']!="null") {
                                                    $qry99 = Insert('participant_details', $data11);
                                                    }
                                                    
                                                    if ($_GET['pubg_id3']!="null") {
                                                    $qry999 = Insert('participant_details', $data111);
                                                    }
                                                    
                                                    if ($_GET['pubg_id4']!="null") {
                                                    $qry999 = Insert('participant_details', $data1111);
                                                    }
                                                    
                                                    $qry10 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                    $qry11 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                    
                                                    $qry12 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry13 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                            else {
                                                $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                                $slot = $slot['slot'];  
                                                $match_slot=$slot+1;
                                                
                                                $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                                $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                                $total_joined = $total_joined['total_joined'];  
                                                $joined=$total_joined+1;
                                                
                                                $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                                $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                                $cur_balance1 = $wallte['cur_balance']; 
                                                $won_balance1 = $wallte['won_balance'];
                                                $bonus_balance1 = $wallte['bonus_balance'];
                                                
                                                $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                    if ($bonus_balance1 >= $bonus) {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = $bonus_balance1 - $bonus;
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    else {
                                                        $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                        $diff = $depoit_balance1 + $bonus_balance1;
                                                        if($diff >= $entry_fee) {
                                                            $won_balance2 = $won_balance1;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                        else {
                                                            $fee = $entry_fee - $diff;
                                                            $won_balance2 = $won_balance1 - $fee;
                                                            $bonus_balance2 = '0';
                                                            $cur_balance2 = $cur_balance1 - $entry_fee;
                                                        }
                                                    }
                                                    
                                                    $data1 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id1'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                        
                                                    $data11 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id2'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id3'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                                    
                                                    $data1111 = array(
                                                        'match_id'  => $_GET['match_id'],
                                                        'user_id'  => $_GET['user_id'],
                                                        'pubg_id'  => $_GET['pubg_id4'],
                                                        'slot'  => $match_slot,
                                                        'name'  =>  $_GET['name']
                                                    );
                                    
                                                    $data2 = array(
                                                        'total_joined'  =>  $joined
                                                    );
                                                    
                                                    $data3 = array(
                                                        'cur_balance'  =>  $cur_balance2,
                                                        'won_balance'  =>  $won_balance2,
                                                        'bonus_balance'  =>  $bonus_balance2
                                                    );
                                                    
                                                    if ($_GET['pubg_id1']!="null") {
                                                        $qry6 = Insert('participant_details', $data1);  
                                                    }
                                                    
                                                    if ($_GET['pubg_id2']!="null") {
                                                        $qry66 = Insert('participant_details', $data11);
                                                    }
                                                    
                                                    if ($_GET['pubg_id3']!="null") {
                                                        $qry666 = Insert('participant_details', $data111);
                                                    }
                                                    
                                                    if ($_GET['pubg_id4']!="null") {
                                                        $qry6666 = Insert('participant_details', $data1111);
                                                    }
                                                    
                                                    $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                    
                                                    $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                    
                                                    $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                                else {
                                                    $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                    mysqli_close($connect);
                                                }
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id3'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data1111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id4'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry6 = Insert('participant_details', $data1);  
                                                }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry66 = Insert('participant_details', $data11);
                                                }
                                                if ($_GET['pubg_id3']!="null") {
                                                $qry666 = Insert('participant_details', $data111);
                                                }
                                                if ($_GET['pubg_id4']!="null") {    
                                                $qry6666 = Insert('participant_details', $data1111);
                                                }
                                                    
                                                $qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }   
                                    }
                                }
                            }
                            else {
                                $set['result'][]=array('msg' => "Invalide Secret Key", 'success'=>'3');
                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            }
                        } 
                        else {
                            if ($match_type == 'Solo') {
                                $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND pubg_id = '".$_GET['pubg_id1']."'"; 
                                $sel1 = mysqli_query($connect, $qry1);
                                $sel1_res = mysqli_fetch_array($sel1);
                                
                                if($sel1_res['row_count'] > 0) {
                                    $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                    mysqli_close($connect);
                                }
                                else {
                                    if ($entry_type == 'Paid') {
                                        $qry3 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                        $sel3 = mysqli_query($connect, $qry3);
                                            
                                        if(mysqli_num_rows($sel3) > 0) {
                                            $today = date("Y-m-d");
                                            
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                           
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $refer_code = $refer_code['refer_code'];    
                                            
                                            $qry5 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                            $balance = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance = $balance['cur_balance']; 
                                            $bonus_balance = $balance['bonus_balance'];
                                            $cur_balance=$cur_balance+$referer_bonus;
                                            $bonus_balance=$bonus_balance+$referer_bonus;
                                            
                                            $qry6 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry7 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'refer_points'  =>  $referer_bonus,
                                                    'refer_status'  => '1',
                                                    'refer_date'=>$today
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance,
                                                    'bonus_balance'  =>  $bonus_balance
                                                );
                                                
                                                $data4 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data5 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry8 = Insert('participant_details', $data1);
                                                }
                                            
                                                $qry9 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                $qry10 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                
                                                $qry11 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry12 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                    $qry5 = Insert('participant_details', $data1);
                                                }
                                                $qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                    }
                                    else {
                                        $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                        $slot = $slot['slot'];  
                                        $match_slot=$slot+1;
                                                
                                        $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                        $total_joined = $total_joined['total_joined'];  
                                        $joined=$total_joined+1;
                                        
                                        $qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                        $wallte = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                        $cur_balance1 = $wallte['cur_balance']; 
                                        $won_balance1 = $wallte['won_balance'];
                                        $bonus_balance1 = $wallte['bonus_balance'];
                                        
                                        $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                        if ($check_balance >= $entry_fee) {
                            
                                            if ($bonus_balance1 >= $bonus) {
                                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                $diff = $depoit_balance1 + $bonus;
                                                if($diff >= $entry_fee) {
                                                    $won_balance2 = $won_balance1;
                                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                                else {
                                                    $fee = $entry_fee - $diff;
                                                    $won_balance2 = $won_balance1 - $fee;
                                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                            }
                                            else {
                                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                $diff = $depoit_balance1 + $bonus_balance1;
                                                if($diff >= $entry_fee) {
                                                    $won_balance2 = $won_balance1;
                                                    $bonus_balance2 = '0';
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                                else {
                                                    $fee = $entry_fee - $diff;
                                                    $won_balance2 = $won_balance1 - $fee;
                                                    $bonus_balance2 = '0';
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                            }
                                            
                                            $data1 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id1'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                            
                                            $data2 = array(
                                                'total_joined'  =>  $joined
                                            );
                                            
                                            $data3 = array(
                                                'cur_balance'  =>  $cur_balance2,
                                                'won_balance'  =>  $won_balance2,
                                                'bonus_balance'  =>  $bonus_balance2
                                            );
                                            
                                            if ($_GET['pubg_id1']!="null") {
                                            $qry5 = Insert('participant_details', $data1);              }
                                            $qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                            
                                            $qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                            
                                            $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                            mysqli_close($connect);
                                        }
                                        else {
                                            $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                            mysqli_close($connect);
                                        }
                                    }   
                                }
                            }
                            else if ($match_type == 'Duo') {
                                $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND (pubg_id = '".$_GET['pubg_id1']."' OR pubg_id = '".$_GET['pubg_id2']."')"; 
                                $sel1 = mysqli_query($connect, $qry1);
                                $sel1_res = mysqli_fetch_array($sel1);
                                
                                if($sel1_res['row_count'] > 0) {
                                    $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                    mysqli_close($connect);
                                }
                                else {
                                    if ($entry_type == 'Paid') {
                                        $qry3 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                        $sel3 = mysqli_query($connect, $qry3);
                                            
                                        if(mysqli_num_rows($sel3) > 0) {
                                            $today = date("Y-m-d");
                                            
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                           
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $refer_code = $refer_code['refer_code'];    
                                            
                                            $qry5 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                            $balance = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance = $balance['cur_balance']; 
                                            $bonus_balance = $balance['bonus_balance'];
                                            $cur_balance=$cur_balance+$referer_bonus;
                                            $bonus_balance=$bonus_balance+$referer_bonus;
                                            
                                            $qry6 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry7 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                            
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'refer_points'  =>  $referer_bonus,
                                                    'refer_status'  => '1',
                                                    'refer_date'=>$today
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance,
                                                    'bonus_balance'  =>  $bonus_balance
                                                );
                                                
                                                $data4 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data5 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry8 = Insert('participant_details', $data1);
                                                }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry88 = Insert('participant_details', $data11);
                                                }
                                                $qry9 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                $qry10 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                
                                                $qry11 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry12 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                                
                                            $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                            
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry5 = Insert('participant_details', $data1);
                                                }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry55 = Insert('participant_details', $data11);
                                                }
                                                
                                                $qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                    }
                                    else {
                                        $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                        $slot = $slot['slot'];  
                                        $match_slot=$slot+1;
                                                
                                        $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                        $total_joined = $total_joined['total_joined'];  
                                        $joined=$total_joined+1;
                                        
                                        $qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                        $wallte = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                        $cur_balance1 = $wallte['cur_balance']; 
                                        $won_balance1 = $wallte['won_balance'];
                                        $bonus_balance1 = $wallte['bonus_balance'];
                                        
                                        $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                        if ($check_balance >= $entry_fee) {
                            
                                            if ($bonus_balance1 >= $bonus) {
                                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                $diff = $depoit_balance1 + $bonus;
                                                if($diff >= $entry_fee) {
                                                    $won_balance2 = $won_balance1;
                                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                                else {
                                                    $fee = $entry_fee - $diff;
                                                    $won_balance2 = $won_balance1 - $fee;
                                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                            }
                                            else {
                                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                $diff = $depoit_balance1 + $bonus_balance1;
                                                if($diff >= $entry_fee) {
                                                    $won_balance2 = $won_balance1;
                                                    $bonus_balance2 = '0';
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                                else {
                                                    $fee = $entry_fee - $diff;
                                                    $won_balance2 = $won_balance1 - $fee;
                                                    $bonus_balance2 = '0';
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                            }
                                            
                                            $data1 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id1'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                                            
                                            $data11 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id2'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                                                
                                                
                                            $data2 = array(
                                                'total_joined'  =>  $joined
                                            );
                                            
                                            $data3 = array(
                                                'cur_balance'  =>  $cur_balance2,
                                                'won_balance'  =>  $won_balance2,
                                                'bonus_balance'  =>  $bonus_balance2
                                            );
                                            
                                            if ($_GET['pubg_id1']!="null") {
                                            $qry5 = Insert('participant_details', $data1);
                                            }
                                            if ($_GET['pubg_id2']!="null") {
                                            $qry55 = Insert('participant_details', $data11);        }
                                            
                                            $qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                            
                                            $qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                            
                                            $set['result'][] = array('msg' => "Joined succesfully...!!!", 'success'=>'2');
                                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                            mysqli_close($connect);
                                        }
                                        else {
                                            $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                            mysqli_close($connect);
                                        }
                                    }   
                                }
                            }
                            else if ($match_type == 'Squad') {
                                $qry1 = "SELECT count(id) as row_count FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND (pubg_id = '".$_GET['pubg_id1']."' OR pubg_id = '".$_GET['pubg_id2']."' OR pubg_id = '".$_GET['pubg_id3']."' OR pubg_id = '".$_GET['pubg_id4']."')"; 
                                $sel1 = mysqli_query($connect, $qry1);
                                $sel1_res = mysqli_fetch_array($sel1);
                                
                                if($sel1_res['row_count'] > 0) {
                                    $set['result'][]=array('msg' => "This game username is already exist!", 'success'=>'0');
                                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                    mysqli_close($connect);
                                }
                                else {
                                    if ($entry_type == 'Paid') {
                                        $qry3 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                        $sel3 = mysqli_query($connect, $qry3);
                                            
                                        if(mysqli_num_rows($sel3) > 0) {
                                            $today = date("Y-m-d");
                                            
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                           
                                            $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                            $refer_code = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $refer_code = $refer_code['refer_code'];    
                                            
                                            $qry5 = "SELECT cur_balance,bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                            $balance = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                            $cur_balance = $balance['cur_balance']; 
                                            $bonus_balance = $balance['bonus_balance'];
                                            $cur_balance=$cur_balance+$referer_bonus;
                                            $bonus_balance=$bonus_balance+$referer_bonus;
                                            
                                            $qry6 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry7 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                                if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id3'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data1111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id4'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data2 = array(
                                                    'refer_points'  =>  $referer_bonus,
                                                    'refer_status'  => '1',
                                                    'refer_date'=>$today
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance,
                                                    'bonus_balance'  =>  $bonus_balance
                                                );
                                                
                                                $data4 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data5 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry8 = Insert('participant_details', $data1);
                                                }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry88 = Insert('participant_details', $data11);
                                                }
                                                if ($_GET['pubg_id3']!="null") {
                                                $qry888 = Insert('participant_details', $data111);
                                                }
                                                if ($_GET['pubg_id4']!="null") {
                                                $qry8888 = Insert('participant_details', $data1111);
                                                }
                                                
                                                $qry9 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                                $qry10 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                                
                                                $qry11 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry12 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                        else {
                                            $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                            $slot = $slot['slot'];  
                                            $match_slot=$slot+1;
                                            
                                            $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                            $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                            $total_joined = $total_joined['total_joined'];  
                                            $joined=$total_joined+1;
                                            
                                            $qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                            $wallte = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                            $cur_balance1 = $wallte['cur_balance']; 
                                            $won_balance1 = $wallte['won_balance'];
                                            $bonus_balance1 = $wallte['bonus_balance'];
                                            
                                            $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                            if ($check_balance >= $entry_fee) {
                            
                                                if ($bonus_balance1 >= $bonus) {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = $bonus_balance1 - $bonus;
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                else {
                                                    $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                    $diff = $depoit_balance1 + $bonus_balance1;
                                                    if($diff >= $entry_fee) {
                                                        $won_balance2 = $won_balance1;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                    else {
                                                        $fee = $entry_fee - $diff;
                                                        $won_balance2 = $won_balance1 - $fee;
                                                        $bonus_balance2 = '0';
                                                        $cur_balance2 = $cur_balance1 - $entry_fee;
                                                    }
                                                }
                                                
                                                $data1 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id1'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                
                                                $data11 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id2'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id3'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                                
                                                $data1111 = array(
                                                    'match_id'  => $_GET['match_id'],
                                                    'user_id'  => $_GET['user_id'],
                                                    'pubg_id'  => $_GET['pubg_id4'],
                                                    'slot'  => $match_slot,
                                                    'name'  =>  $_GET['name']
                                                );
                                               
                                                $data2 = array(
                                                    'total_joined'  =>  $joined
                                                );
                                                
                                                $data3 = array(
                                                    'cur_balance'  =>  $cur_balance2,
                                                    'won_balance'  =>  $won_balance2,
                                                    'bonus_balance'  =>  $bonus_balance2
                                                );
                                                
                                                if ($_GET['pubg_id1']!="null") {
                                                $qry5 = Insert('participant_details', $data1);
                                                }
                                                if ($_GET['pubg_id2']!="null") {
                                                $qry55 = Insert('participant_details', $data11);
                                                }
                                                if ($_GET['pubg_id3']!="null") {
                                                $qry555 = Insert('participant_details', $data111);
                                                }
                                                if ($_GET['pubg_id4']!="null") {
                                                $qry5555 = Insert('participant_details', $data1111);
                                                }
                                                $qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                                
                                                $qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                                
                                                $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                            else {
                                                $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                                mysqli_close($connect);
                                            }
                                        }
                                    }
                                    else {
                                        $qry2 = "SELECT MAX(slot) AS slot FROM participant_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        $slot = mysqli_fetch_array(mysqli_query($connect,$qry2));
                                        $slot = $slot['slot'];  
                                        $match_slot=$slot+1;
                                            
                                        $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        $total_joined = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                        $total_joined = $total_joined['total_joined'];  
                                        $joined=$total_joined+1;
                                        
                                        $qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                        $wallte = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                        $cur_balance1 = $wallte['cur_balance']; 
                                        $won_balance1 = $wallte['won_balance'];
                                        $bonus_balance1 = $wallte['bonus_balance'];
                                        
                                        $check_balance =  ($cur_balance1 - $bonus_balance1) + $bonus;
                                        if ($check_balance >= $entry_fee) {
                            
                                            if ($bonus_balance1 >= $bonus) {
                                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                $diff = $depoit_balance1 + $bonus;
                                                if($diff >= $entry_fee) {
                                                    $won_balance2 = $won_balance1;
                                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                                else {
                                                    $fee = $entry_fee - $diff;
                                                    $won_balance2 = $won_balance1 - $fee;
                                                    $bonus_balance2 = $bonus_balance1 - $bonus;
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                            }
                                            else {
                                                $depoit_balance1 = $cur_balance1 - ($won_balance1 + $bonus_balance1);
                                                $diff = $depoit_balance1 + $bonus_balance1;
                                                if($diff >= $entry_fee) {
                                                    $won_balance2 = $won_balance1;
                                                    $bonus_balance2 = '0';
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                                else {
                                                    $fee = $entry_fee - $diff;
                                                    $won_balance2 = $won_balance1 - $fee;
                                                    $bonus_balance2 = '0';
                                                    $cur_balance2 = $cur_balance1 - $entry_fee;
                                                }
                                            }
                                            
                                            $data1 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id1'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                                            
                                            $data11 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id2'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                                            
                                            $data111 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id3'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                                            
                                            $data1111 = array(
                                                'match_id'  => $_GET['match_id'],
                                                'user_id'  => $_GET['user_id'],
                                                'pubg_id'  => $_GET['pubg_id4'],
                                                'slot'  => $match_slot,
                                                'name'  =>  $_GET['name']
                                            );
                            
                                            $data2 = array(
                                                'total_joined'  =>  $joined
                                            );
                                            
                                            $data3 = array(
                                                'cur_balance'  =>  $cur_balance2,
                                                'won_balance'  =>  $won_balance2,
                                                'bonus_balance'  =>  $bonus_balance2
                                            );
                                            
                                            if ($_GET['pubg_id1']!="null") {
                                            $qry4 = Insert('participant_details', $data1);
                                            }
                                            if ($_GET['pubg_id2']!="null") {
                                            $qry44 = Insert('participant_details', $data11);
                                            }
                                            if ($_GET['pubg_id3']!="null") {
                                            $qry444 = Insert('participant_details', $data111);
                                            }
                                            if ($_GET['pubg_id4']!="null") {
                                            $qry4444 = Insert('participant_details', $data1111);
                                            }
                                                
                                            $qry5 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                            
                                            $qry6 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                            
                                            $set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                            mysqli_close($connect);
                                        }
                                        else {
                                            $set['result'][]=array('msg' => "You have not enough deposit or winning balance to participate.", 'success'=>'0');
                                            echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                            mysqli_close($connect);
                                        }
                                    }   
                                }
                            }    
                        }
                    }
                    else {
                        $set['result'][]=array('msg' => "You can't join this match due to match is full.", 'success'=>'0');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                    
                } else {
                     header( 'Content-Type: application/json; charset=utf-8' );
                     $json = json_encode($set);
                     echo $json;
                     mysqli_close($connect);       
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getAddCoins() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $flag = array();
                        
                $query = "SELECT id, title, subtitle, message, amount, coins, image, status, type, currency FROM payout_details WHERE type = '1' AND status = '0' ORDER BY id DESC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getRedeemCoins() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $flag = array();
                        
                $query = "SELECT id, title, subtitle, message, amount, coins, image, status, type, currency FROM payout_details WHERE type = '0' AND status = '0' ORDER BY id DESC";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }   
        
        public function AddTransaction() {
            include "../include/config.php";
            include "../public/transaction.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if(isset($_GET['request_name']) && isset($_GET['req_from'])) {
                    $play_coins = $_GET['coins_used'];
                    $user_id = $_GET['user_id'];
                    $current_time = time();
                    $order_id = time().$user_id;
                    
                    $sql = "Select id from transaction_details WHERE user_id = '$user_id' AND status = '0'";
                    $res = mysqli_query($connect, $sql);
                    $num_res = mysqli_num_rows($res);
                    
                    if ($num_res == 0) {    
                        $qry = "SELECT cur_balance, won_balance FROM user_details WHERE id = '$user_id'"; 
                        $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                        $tot_coins = $userdata['cur_balance'];
                        $won_coins = $userdata['won_balance'];
                        $new_tot_coins = $tot_coins - $play_coins;
                        $new_won_coins = $won_coins - $play_coins;
                            
                        $data1 = array(
                            'user_id'  => $_GET['user_id'],
                            'order_id'  => $order_id,
                            'request_name'  => $_GET['request_name'],
                            'req_from'  => $_GET['req_from'],
                            'req_amount'  => $_GET['req_amount'],
                            'coins_used'  => $_GET['coins_used'],
                            'getway_name'  => $_GET['getway_name'],
                            'remark'  => $_GET['remark'],
                            'type'  =>  $_GET['type'],
                            'date' => $current_time,
                            'status'  =>  '0'
                        );
        
                        $data2 = array(
                            'cur_balance'  =>  $new_tot_coins,
                            'won_balance'  =>  $new_won_coins
                        );
                        
                        $qry1 = Insert('transaction_details', $data1);
                        $qry2 = Update('user_details', $data2,"WHERE id = '$user_id'");
                    
                        $set['result'][] = array('msg' => "Your request has been successfully sent. Please wait for approval.", 'success'=>'1');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                    else {
                        $set['result'][] = array('msg' => "Oops! You can't send new redeem play coins request until old one review.",'success'=>'1');
                        header( 'Content-Type: application/json; charset=utf-8' );
                        echo $json = json_encode($set);
                        mysqli_close($connect);
                    }
                    
                } else if(isset($_GET['order_id']) && isset($_GET['payment_id'])) {
                    $play_coins = $_GET['coins_used'];
                    $user_id = $_GET['user_id'];
                    $current_time = time();
                    $order_id = time().$user_id;
                    
                    $qry = "SELECT cur_balance FROM user_details WHERE id = '$user_id'"; 
                    $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                    $tot_coins = $userdata['cur_balance'];
                    $new_tot_coins = $tot_coins + $play_coins;
                    
                    $data1 = array(
                        'user_id'  => $_GET['user_id'],
                        'order_id'  => $order_id,
                        'payment_id'  => $_GET['payment_id'],
                        'req_amount'  => $_GET['req_amount'],
                        'coins_used'  => $_GET['coins_used'],
                        'getway_name'  => $_GET['getway_name'],
                        'remark'  => $_GET['remark'],
                        'type'  =>  $_GET['type'],
                        'date' => $current_time,
                        'status'  =>  '1'
                    );
                        
                    $data2 = array(
                        'cur_balance'  =>  $new_tot_coins
                    );
                    
                    $qry1 = Insert('transaction_details', $data1);  
                    $qry2 = Update('user_details', $data2,"WHERE id = '$user_id'");
                    
                    $set['result'][] = array('msg' => "Your request has been successfully approved. Please check your wallet.", 'success'=>'1');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);
                    
                } else {
                     header( 'Content-Type: application/json; charset=utf-8' );
                     $json = json_encode($set);
                     echo $json;
                     mysqli_close($connect);       
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
            
        public function addReward() {
            include "../include/config.php";
            include "../public/rewards.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
            
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        if(isset($_GET['username']) && isset($_GET['reward_points'])) {
                            $username = $_GET['username'];
                            $play_coins = $_GET['reward_points'];
                            $reward_limit = $_GET['reward_limits'];
                            $current_time = time();
                                
                            $sql = "SELECT count(id) AS count FROM rewarded_details WHERE username = '$username' AND from_unixtime(reward_date, '%Y-%m-%d') = CURDATE() ORDER BY id DESC LIMIT 1"; 
                            $res = mysqli_fetch_array(mysqli_query($connect, $sql));     
                            $count = $res['count']+1;
                               
                            if($count >= $reward_limit) {
                                $qry = "SELECT cur_balance, bonus_balance FROM user_details WHERE username = '$username'"; 
                                $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                                $tot_coins = $userdata['cur_balance'];
                                $bonus_coins = $userdata['bonus_balance'];
                                $new_tot_coins = $tot_coins + $play_coins;
                                $new_bonus_coins = $bonus_coins + $play_coins;
                                 
                                $data1 = array(
                                    'username'  => $username,
                                    'reward_points'  => $play_coins,
                                    'reward_date'  =>  $current_time
                                );
                                        
                                $data2 = array(
                                    'cur_balance'  =>  $new_tot_coins,
                                    'bonus_balance'  =>  $new_bonus_coins
                                );
                                
                                $qry1 = Insert('rewarded_details', $data1);
                                $qry2 = Update('user_details', $data2,"WHERE username = '$username'");
                                
                                $diff = ($current_time + 86400 - ($current_time % 86400)) - $current_time;
                                $set['result'][]=array('msg' => $diff, 'success'=>'0');
                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            } 
                            else {    
                                $data1 = array(
                                    'username'  => $username,
                                    'reward_points'  => '0',
                                    'reward_date'  =>  $current_time
                                );
                                        
                                $qry1 = Insert('rewarded_details', $data1);
                                
                                $diff = $reward_limit - $count;
                                $set['result'][]=array('msg' => "Please complete this task. $diff time letf to redeem reward.", 'success'=>'1');
                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            }
                            
                        } else {
                             header( 'Content-Type: application/json; charset=utf-8' );
                             $json = json_encode($set);
                             echo $json;
                             mysqli_close($connect);       
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        
        public function getRewards() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        if(isset($_GET['username']) && isset($_GET['reward_limits'])) {
                            $username = $_GET['username'];
                            $reward_limit = $_GET['reward_limits'];
                            $current_time = time();
                                
                            $sql = "SELECT count(id) AS count FROM rewarded_details WHERE username = '$username' AND from_unixtime(reward_date, '%Y-%m-%d') = CURDATE() ORDER BY id DESC LIMIT 1"; 
                            $res = mysqli_fetch_array(mysqli_query($connect, $sql));     
                            $count = $res['count'];
                               
                            if($count >= $reward_limit) {
                                $diff = ($current_time + 86400 - ($current_time % 86400)) - $current_time;
                                $set['result'][]=array('msg' => $diff, 'success'=>'0');
                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            } 
                            else {
                                $set['result'][]=array('msg' => $count, 'success'=>'1');
                                echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                mysqli_close($connect);
                            }
                        } else {
                             header( 'Content-Type: application/json; charset=utf-8' );
                             $json = json_encode($set);
                             echo $json;
                             mysqli_close($connect);       
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        public function getProducts() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $flag = array();
                        
                        $query = "SELECT id,brand,name,image,price,price_discount,description,url FROM product_details ORDER BY id ASC";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getSlider() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $prod_id = $_GET['prod_id'];
                        $flag = array();
                        
                        $query = "SELECT id,prod_id,prod_img FROM tbl_product_img WHERE prod_id = '$prod_id' ORDER BY id ASC";
                        $result = mysqli_query($connect,$query);
                
                        if($result){
                            while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                            header( 'Content-Type: application/json; charset=utf-8' );
                            print(json_encode($flag));
                            mysqli_close($connect);
                        }
                        else {
                            mysqli_close($connect);
                        }
                    } else {
                        $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        $this->response($this->json($respon), 404);
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array( 'success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
            
        
        public function verifyCard() {
            include "../include/config.php";
            include "../public/rewards.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if(isset($_GET['user_id']) && isset($_GET['voucher_code'])) {
                    $user_id = $_GET['user_id'];
                    $voucher_code = $_GET['voucher_code'];
                        
                    $sql = "SELECT status, is_expired, amount, coin, transaction_id FROM tbl_gift_voucher WHERE voucher_code = '$voucher_code' AND is_del = '0'"; 
                    $result = mysqli_query($connect, $sql);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($num_rows > 0 && $row['status'] == 1 && $row['is_expired'] == 0) {         
                        $current_time = time();
                        $order_id = time().$user_id;
                        $play_coins = $row['coin'];
                    
                        $qry = "SELECT cur_balance FROM user_details WHERE id = '$user_id'"; 
                        $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                        $tot_coins = $userdata['cur_balance'];
                        $new_tot_coins = $tot_coins + $play_coins;
                        
                        $data1 = array(
                            'user_id'  => $user_id,
                            'order_id'  => $order_id,
                            'payment_id'  => $row['transaction_id'],
                            'req_amount'  => $row['amount'],
                            'coins_used'  => $row['coin'],
                            'getway_name'  => 'Gift Card',
                            'remark'  => 'Added From Gift Card',
                            'type'  =>  '1',
                            'date' => $current_time,
                            'status'  =>  '1'
                        );
                            
                        $data2 = array(
                            'cur_balance'  =>  $new_tot_coins
                        );
                        
                        $data3 = array(
                            'status'  =>  '0'
                        );
                        
                        $qry1 = Insert('transaction_details', $data1);  
                        $qry2 = Update('user_details', $data2,"WHERE id = '$user_id'");
                        $qry3 = Update('tbl_gift_voucher', $data3,"WHERE voucher_code = '$voucher_code'");
                        
                        $set['result'][] = array('msg' => "Your request has been successfully approved. Please check your wallet.", 'success'=>'1');
                    } else if ($num_rows > 0 && $row['status'] == 0) {
                        $set['result'][] = array('msg' => 'This gift card voucher already used.', 'success' => '0');
                    } else if ($num_rows > 0 && $row['is_expired'] == 1) {
                        $set['result'][] = array('msg' => 'This gift card voucher was expired.', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'Invalid gift card voucher..', 'success' => '0');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else {
                     header( 'Content-Type: application/json; charset=utf-8' );
                     $json = json_encode($set);
                     echo $json;
                     mysqli_close($connect);       
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        public function addMoney() {
            include "../include/config.php";
            include "../public/rewards.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if(isset($_GET['user_id']) && isset($_GET['transaction_id'])) {
                    $user_id = $_GET['user_id'];
                    $transaction_id = $_GET['transaction_id'];
                    $amount = $_GET['amount'];
                    $coin = $_GET['coin'];
                    $method = $_GET['method'];
                    $note = $_GET['note'];
                        
                    $sql = "SELECT status FROM tbl_offline_plyments WHERE transaction_id = '$transaction_id'"; 
                    $result = mysqli_query($connect, $sql);
                    $num_rows = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                        
                    if ($num_rows == 0 && $row['status'] == 0) {         
                        
                        $qry = "SELECT cur_balance FROM user_details WHERE id = '$user_id'"; 
                        $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                        $tot_coins = $userdata['cur_balance'];
                        $new_tot_coins = $tot_coins + $play_coins;
                        
                        $data1 = array(
                            'user_id'  => $user_id,
                            'transaction_id'  => $transaction_id,
                            'amount'  => $amount,
                            'wallet'  => $method,
                            'note'  => $note,
                            'coins'  => $coin,
                            'status'  =>  '0'
                        );
                        
                        $qry1 = Insert('tbl_offline_plyments', $data1);  
                        
                        $set['result'][] = array('msg' => "Your request has been successfully approved. Please check your wallet.", 'success'=>'1');
                    } else if ($num_rows == 0 && $row['status'] == 1) {
                        $set['result'][] = array('msg' => 'This transaction is already approved!', 'success' => '0');
                    } else {
                        $set['result'][] = array('msg' => 'Already submitted this transaction request!', 'success' => '0');
                    }
                     
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else {
                     header( 'Content-Type: application/json; charset=utf-8' );
                     $json = json_encode($set);
                     echo $json;
                     mysqli_close($connect);       
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        public function addPaymentFailed() {
            include "../include/config.php";
            include "../public/rewards.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                if(isset($_GET['user_id']) && isset($_GET['payout_id'])) {
                    $user_id = $_GET['user_id'];
                    $payout_id = $_GET['payout_id'];
                    $payment_id = $_GET['payment_id'];
                    $order_id = $_GET['order_id'];
                        
                    $data1 = array(
                            'user_id'  => $user_id,
                            'payout_id'  => $payout_id,
                            'payment_id'  => $payment_id,
                            'order_id'  => $order_id,
                            'status'  =>  '0'
                    );
                        
                    $qry1 = Insert('tbl_payment_failed', $data1);  
                    header( 'Content-Type: application/json; charset=utf-8' );
                    $json = json_encode($set);
                    echo $json;
                    mysqli_close($connect);
                } else {
                     header( 'Content-Type: application/json; charset=utf-8' );
                     $json = json_encode($set);
                     echo $json;
                     mysqli_close($connect);       
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
            
        public function getUpdateApp() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $qry = "SELECT id, force_update, whats_new, update_date, latest_version_name, latest_version_code, update_url FROM update_details";
                $result = mysqli_query($connect, $qry);  
                $row = mysqli_fetch_assoc($result);
                                 
                $set['result'][] = array(
                    'id' => $row['id'],
                    'force_update' => $row['force_update'],
                    'whats_new' => strip_tags($row['whats_new']),
                    'update_date' => $row['update_date'],
                    'latest_version_name' => $row['latest_version_name'],
                    'latest_version_code' => $row['latest_version_code'],
                    'update_url' => $row['update_url'],
                    'success'=>'1'
                );
        
                header( 'Content-Type: application/json; charset=utf-8' );
                $json = json_encode($set);  
                echo $json;
                mysqli_close($connect);
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getNotification() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $flag = array();
                        
                $query = "SELECT id,title,message,image,url,created FROM announcement_details ORDER BY id DESC LIMIT 0,10";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode($flag));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getAnnouncement() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $flag = array();
                        
                $query = "SELECT title FROM announcement_details ORDER BY id DESC LIMIT 0,5";
                $result = mysqli_query($connect,$query);
        
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $flag[]=$row;
                    }
                    header( 'Content-Type: application/json; charset=utf-8' );
                    print(json_encode(array("Result"=>$flag)));
                    mysqli_close($connect);
                }
                else {
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        
        
        public function getFAQ() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $qry = "SELECT content FROM tbl_faq";
                $result = mysqli_query($connect, $qry);  
                $row = mysqli_fetch_assoc($result);
                                 
                $set['result'][] = array(
                    'content' => $row['content'],
                    'success'=>'1'
                );
        
                header( 'Content-Type: application/json; charset=utf-8' );
                $json = json_encode($set);  
                echo $json;
                mysqli_close($connect);
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
                
                
                
        public function getAboutUs() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $qry = "SELECT content FROM tbl_about";
                        $result = mysqli_query($connect, $qry);  
                        $row = mysqli_fetch_assoc($result);
                                         
                        $set['result'][] = array(
                            'content' => $row['content'],
                            'success'=>'1'
                        );
                
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);  
                        echo $json;
                        mysqli_close($connect);
                    } 
                    else {
                        $set['result'][]=array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
            
        public function getTermsConditions() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $qry = "SELECT content FROM tbl_terms_conditions";
                        $result = mysqli_query($connect, $qry);  
                        $row = mysqli_fetch_assoc($result);
                                         
                        $set['result'][] = array(
                            'content' => $row['content'],
                            'success'=>'1'
                        );
                
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);  
                        echo $json;
                        mysqli_close($connect);
                    } 
                    else {
                        $set['result'][]=array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
        public function getPrivacyPolicy() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $qry = "SELECT content FROM tbl_privacy_policy";
                        $result = mysqli_query($connect, $qry);  
                        $row = mysqli_fetch_assoc($result);
                                         
                        $set['result'][] = array(
                            'content' => $row['content'],
                            'success'=>'1'
                        );
                
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);  
                        echo $json;
                        mysqli_close($connect);
                    } 
                    else {
                        $set['result'][]=array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
    
        public function getContactUs() {
            include "../include/config.php";
            global $access_key;
                
            if(isset($_GET['access_key']) && $access_key == $_GET['access_key']) {
                $akcode = trim($_GET['access_key']);
                $personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
                // Make sure the code is valid before sending it to Envato
                if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
                    // throw new Exception("Invalid code");
                    $set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
                    echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    mysqli_close($connect);            
                }
                
                // Build the request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer {$personalToken}",
                        "User-Agent: {$userAgent}"
                    )
                ));
            
                // Send the request with warnings supressed
                $response = @curl_exec($ch);
                $body = @json_decode($response);
            
                if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                    try {
                        throw new Exception("Error parsing response");
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            
                if (isset($body->item->name)) {
                    $id = $body->item->id;
                    $name = $body->item->name;
            
                    if($id == 25935289) {
                        $qry = "SELECT title, phone, email, address, other, whatsapp_no, messenger_id, fb_follow, ig_follow, twitter_follow, youtube_follow FROM tbl_contact";
                        $result = mysqli_query($connect, $qry);  
                        $row = mysqli_fetch_assoc($result);
                                         
                        $set['result'][] = array(
                            'title' => $row['title'],
                            'phone' => $row['phone'],
                            'email' => $row['email'],
                            'address' => $row['address'],
                            'other' => $row['other'],
                            'whatsapp_no' => $row['whatsapp_no'],
                            'messenger_id' => $row['messenger_id'],
                            'fb_follow' => $row['fb_follow'],
                            'ig_follow' => $row['ig_follow'],
                            'twitter_follow' => $row['twitter_follow'],
                            'youtube_follow' => $row['youtube_follow'],
                            'success'=>'1'
                        );
                
                        header( 'Content-Type: application/json; charset=utf-8' );
                        $json = json_encode($set);  
                        echo $json;
                        mysqli_close($connect);
                    } 
                    else {
                        $set['result'][]=array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                        echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        mysqli_close($connect);
                    }
                }
                else {
                    $respon = array('success' => '0', 'msg' => 'Oops, API Key is Incorrect!');
                    $this->response($this->json($respon), 404);
                    mysqli_close($connect);
                }
            } 
            else {
                $respon = array( 'success' => '0', 'msg' => 'Forbidden, API Key is Required!');
                $this->response($this->json($respon), 404);
                mysqli_close($connect);
            }
        }
        
    }
?>