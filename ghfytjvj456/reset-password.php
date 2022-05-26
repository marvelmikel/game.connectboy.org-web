<?php
include("../include/config.php");

if(isset($_GET['fp_code']))
{
    $fp_code = $_GET['fp_code'];

    $selquery="select forgot_pass_identity from user_details where forgot_pass_identity='$fp_code'  AND status = '1' AND is_block = '0'";
    $selquery1 = mysqli_query($connect,$selquery);
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reset Password</title>

    
    <link rel="shortcut icon" href="assets/images/favicon_1.ico">
    
    <link href="../admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../admin/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="../admin/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="../admin/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../admin/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="../admin/assets/css/responsive.css" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    <script src="../admin/assets/js/modernizr.min.js"></script>
    
    <link href="../admin/assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css">
  </head>
  <body>

    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
      <div class=" card-box">
        <div class="panel-heading">
          <h3 class="text-center"> Reset Password </h3>
        </div>

        <div class="panel-body">
        <?php
          $selres = mysqli_num_rows($selquery1);
          if ($selres == 0) {
            echo "You may broken something!!!";
          }
          else
          {

              if(isset($_POST['btnResetpass']))
              {
                $txtNpass = $_POST['txtNpass'];
                $txtCpass = $_POST['txtCpass'];

                if ($txtNpass==$txtCpass) {
                  
                  $txtPass=md5($txtCpass);

                  $pwdquery = "update user_details set password='{$txtPass}' where forgot_pass_identity='{$fp_code}'  AND status = '1' AND is_block = '0'";
                      if(mysqli_query($connect,$pwdquery))
                      {
                        echo '<script type="text/javascript">';
                        echo 'setTimeout(function () { swal(
                                                        "Done...",
                                                        "Your password has been changed successfully",
                                                        "success"
                                                      );';
                        echo '}, 1000);</script>';

                      }
                }
                else
                {
                  echo '<script type="text/javascript">';
                  echo 'setTimeout(function () { swal(
                                                        "Oops...",
                                                        "Confirm Password must be same as new password",
                                                        "error"
                                                      );';
                  echo '}, 1000);</script>';
                }
                
              }
        ?>

          <form action="reset-password.php?fp_code=<?php echo $_GET['fp_code'];?>" method="post" data-parsley-validate novalidate>
              <div class="form-group">
                <label for="pass1">New Password</label>
                <input id="pass1" name="txtNpass" type="password" placeholder="Password" required class="form-control">
              </div>
              <div class="form-group">
                <label for="passWord2">Confirm Password</label>
                <input data-parsley-equalto="#pass1" name="txtCpass" type="password" required placeholder="Password" class="form-control" id="passWord2">
              </div>
              <br>
              <div class="form-group text-center m-b-0">
                <button class="btn btn-primary waves-effect waves-light" name="btnResetpass" type="submit">
                  Submit
                </button>
                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5">
                  Cancel
                </button>
              </div>

          </form>

        <?php } ?>

        </div>
      </div>
      

    </div>

    <script>
      var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <?php include_once("include/common_js.php"); ?>

    <script src="../admin/assets/js/jquery.core.js"></script>
    <script src="../admin/assets/js/jquery.app.js"></script>
    <script type="text/javascript" src="../admin/assets/plugins/parsleyjs/parsley.min.js"></script>
  </body>
</html>