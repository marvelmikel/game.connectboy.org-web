<?php
include("include/security.php");
include("include/conn.php");
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer {$personalToken}",
        "User-Agent: {$userAgent}"
    )
));

$response = @curl_exec($ch);

$body = @json_decode($response);

if (isset($body->item->name)) {

    $id = $body->item->id;
    $name = $body->item->name;

    if($id == 25935289) {
$getquery4 = "select fname,lname,user_id from tbl_user_master where uname='$user'";
$getresult4 = mysqli_query($conn,$getquery4);
$getres4 = mysqli_fetch_array($getresult4);
$userid = $getres4['user_id'];

$selquery4 = "select * from tbl_lndn_home_page where id=1";
$selresult4 = mysqli_query($conn,$selquery4);
$selres4 = mysqli_fetch_array($selresult4);

if(isset($_POST['btnUpdate']))
  {
    $txtAftitle1 = mysqli_real_escape_string($conn,$_POST['txtAftitle1']);
    $txtAfDesc1 = mysqli_real_escape_string($conn,$_POST['txtAfDesc1']);
    $txtAfIcon1 = mysqli_real_escape_string($conn,$_POST['txtAfIcon1']);
    $txtAftitle2 = mysqli_real_escape_string($conn,$_POST['txtAftitle2']);
    $txtAfDesc2 = mysqli_real_escape_string($conn,$_POST['txtAfDesc2']);
    $txtAfIcon2 = mysqli_real_escape_string($conn,$_POST['txtAfIcon2']);
    $txtAftitle3 = mysqli_real_escape_string($conn,$_POST['txtAftitle3']);
    $txtAfDesc3 = mysqli_real_escape_string($conn,$_POST['txtAfDesc3']);
    $txtAfIcon3 = mysqli_real_escape_string($conn,$_POST['txtAfIcon3']);
    $txtAftitle4 = mysqli_real_escape_string($conn,$_POST['txtAftitle4']);
    $txtAfDesc4 = mysqli_real_escape_string($conn,$_POST['txtAfDesc4']);
    $txtAfIcon4 = mysqli_real_escape_string($conn,$_POST['txtAfIcon4']);
    $txtAftitle5 = mysqli_real_escape_string($conn,$_POST['txtAftitle5']);
    $txtAfDesc5 = mysqli_real_escape_string($conn,$_POST['txtAfDesc5']);
    $txtAfIcon5 = mysqli_real_escape_string($conn,$_POST['txtAfIcon5']);
    $txtAftitle6 = mysqli_real_escape_string($conn,$_POST['txtAftitle6']);
    $txtAfDesc6 = mysqli_real_escape_string($conn,$_POST['txtAfDesc6']);
    $txtAfIcon6 = mysqli_real_escape_string($conn,$_POST['txtAfIcon6']);
    $txtAftitle7 = mysqli_real_escape_string($conn,$_POST['txtAftitle7']);
    $txtAfDesc7 = mysqli_real_escape_string($conn,$_POST['txtAfDesc7']);
    $txtAfIcon7 = mysqli_real_escape_string($conn,$_POST['txtAfIcon7']);
    $txtAftitle8 = mysqli_real_escape_string($conn,$_POST['txtAftitle8']);
    $txtAfDesc8 = mysqli_real_escape_string($conn,$_POST['txtAfDesc8']);
    $txtAfIcon8 = mysqli_real_escape_string($conn,$_POST['txtAfIcon8']);

    if(isset($_FILES['txtFSimg']))
    {
      $file1 = $_FILES['txtFSimg'];

      //file properties

      $file1_name=$file1['name'];
      $file1_tmp=$file1['tmp_name'];
      $file1_error=$file1['error'];

      //file extension

      $file_ext=explode('.',$file1_name);
      $file_ext = strtolower($file1_name);

      if($file1_error==0)
      {
        $file1_new = uniqid('',true).'.'.$file_ext;
        $file1_destination='upload/'.$file1_new;
        move_uploaded_file($file1_tmp,$file1_destination);
      }

      if(isset($file1_destination))
      {
        $txtFSimg=$file1_destination;
        
      }
      else
      {
        $txtFSimg="";
      }
    }
    else
    {
      echo "image not load";
    }
    
    $txtDate = date("Y-m-d H:i:s");
      
      $updquery = " update tbl_lndn_home_page SET home_feature_img='$txtFSimg', home_ftr_title1='$txtAftitle1', home_ftr1='$txtAfDesc1', home_ftr_icon1='$txtAfIcon1', home_ftr_title2='$txtAftitle2', home_ftr2='$txtAfDesc2', home_ftr_icon2='$txtAfIcon2', home_ftr_title3='$txtAftitle3', home_ftr3='$txtAfDesc3', home_ftr_icon3='$txtAfIcon3', home_ftr_title4='$txtAftitle4', home_ftr4='$txtAfDesc4', home_ftr_icon4='$txtAfIcon4', home_ftr_title5='$txtAftitle5', home_ftr5='$txtAfDesc5', home_ftr_icon5='$txtAfIcon5', home_ftr_title6='$txtAftitle6', home_ftr6='$txtAfDesc6', home_ftr_icon6='$txtAfIcon6', home_ftr_title7='$txtAftitle7', home_ftr7='$txtAfDesc7', home_ftr_icon7='$txtAfIcon7', home_ftr_title8='$txtAftitle8', home_ftr8='$txtAfDesc8', home_ftr_icon8='$txtAfIcon8', last_modi_date='$txtDate' where id=1";
    
      if(mysqli_query($conn,$updquery))
      {
          header("Location:landing-page-app-feature");
      }
      else
      {
          //echo $updquery;
          echo '<script type="text/javascript">';
          echo 'setTimeout(function () { swal(
                                                "Oops...",
                                                "Something went wrong !!",
                                                "error"
                                              );';
          echo '}, 1000);</script>';
      } 
  }
  } else {
        header("location:error.php");
      exit;
    }
}
else
{
    header("location:error.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Landing Page Setting</title>

    <?php include_once("include/head-section.php"); ?>
    <!-- DataTables -->
    <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
    <script language="JavaScript" type="text/javascript">
      function checkDelete(){
          return confirm('Are you sure you want to delete this Record?');
      }
    </script>
    <style type="text/css">
      .validation
      {
        font-size: 12px;
        color: #f6504d;
      }
      .validation-box
      {
        border-color: #f6504d;
      }
      .fntawsm-icon
      {
        color: red;
      }
    </style>
  </head>

  <body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

      <!-- topbar and sidebar -->
      <?php include_once("include/navbar.php"); ?>

      <!-- ============================================================== -->
      <!-- Start right Content here -->
      <!-- ============================================================== -->
      <div class="content-page">
        <!-- Start content -->
        <div class="content">
          <div class="container">

            <!-- Page Content -->
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-box">
                  
                  <h4 class="m-t-0 header-title"><b>Landing Page - App Feature</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Update App Feature screen content and images.
                  </p>
                  <p class="fntawsm-icon">Important Notes:</p>
                  <p>For icon we have used font awesome library version 5.10.2</p>
                  <p>Visit <a href="https://fontawesome.com/icons">font awesome</a> and explore thousand of icons for free.</p>
                  <p>Example: Find this text on icon page <code data-balloon="Copy HTML" data-balloon-pos="down" class="dib f2 hover-cyan7"><span class="o-40">&lt;i class="</span>fas fa-ad<span class="o-40">"&gt;&lt;/i&gt;</span></code> and copy only <span class="fntawsm-icon">class name (fas fa-ad)</span> and paste it to icon box below.</p>
                  <hr>
                  <form action="landing-page-app-feature.php" data-parsley-validate novalidate method="post" enctype="multipart/form-data">
                    
                    <div class="col-lg-12"> 
                        <div class="form-group">
                          <label for="txtFSimg">Feature Screen Img *</label>
                          <input type="file" name="txtFSimg" parsley-trigger="change" <?php if($selres4['home_feature_img']=='') { echo "required"; } ?> class="form-control" id="txtFSimg">
                          <small><a href="<?php echo $selres4['home_feature_img']; ?>" target="_blank">current image</a></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-group" id="accordion-test-2"> 
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseOne-2" aria-expanded="false" class="collapsed">
                                                Feature #1
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseOne-2" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle1">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle1" parsley-trigger="change" required class="form-control" id="txtAftitle1" value="<?php echo $selres4['home_ftr_title1']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon1">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon1" parsley-trigger="change" required class="form-control" id="txtAfIcon1" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon1']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                      <label for="txtAfDesc1">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc1" parsley-trigger="change" required class="form-control" id="txtAfDesc1" ><?php echo $selres4['home_ftr1']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-2" class="collapsed" aria-expanded="false">
                                                Feature #2
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseTwo-2" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle2">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle2" parsley-trigger="change" required class="form-control" id="txtAftitle2" value="<?php echo $selres4['home_ftr_title2']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon2">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon2" parsley-trigger="change" required class="form-control" id="txtAfIcon2" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon2']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc2">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc2" parsley-trigger="change" required class="form-control" id="txtAfDesc2" ><?php echo $selres4['home_ftr2']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div> 
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-2" class="collapsed" aria-expanded="false">
                                                Feature #3
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseThree-2" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle3">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle3" parsley-trigger="change" required class="form-control" id="txtAftitle3" value="<?php echo $selres4['home_ftr_title3']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon3">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon3" parsley-trigger="change" required class="form-control" id="txtAfIcon3" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon3']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc3">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc3" parsley-trigger="change" required class="form-control" id="txtAfDesc3" ><?php echo $selres4['home_ftr3']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-3" class="collapsed" aria-expanded="false">
                                                Feature #4
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseThree-3" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle4">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle4" parsley-trigger="change" required class="form-control" id="txtAftitle4" value="<?php echo $selres4['home_ftr_title4']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon4">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon4" parsley-trigger="change" required class="form-control" id="txtAfIcon4" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon4']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc4">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc4" parsley-trigger="change" required class="form-control" id="txtAfDesc4" ><?php echo $selres4['home_ftr4']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-4" class="collapsed" aria-expanded="false">
                                                Feature #5
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseThree-4" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle5">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle5" parsley-trigger="change" required class="form-control" id="txtAftitle5" value="<?php echo $selres4['home_ftr_title5']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon5">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon5" parsley-trigger="change" required class="form-control" id="txtAfIcon5" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon5']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc5">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc5" parsley-trigger="change" required class="form-control" id="txtAfDesc5" ><?php echo $selres4['home_ftr5']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-5" class="collapsed" aria-expanded="false">
                                                Feature #6
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseThree-5" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle6">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle6" parsley-trigger="change" required class="form-control" id="txtAftitle6" value="<?php echo $selres4['home_ftr_title6']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon6">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon6" parsley-trigger="change" required class="form-control" id="txtAfIcon6" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon6']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc6">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc6" parsley-trigger="change" required class="form-control" id="txtAfDesc6" ><?php echo $selres4['home_ftr6']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-6" class="collapsed" aria-expanded="false">
                                                Feature #7
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseThree-6" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle7">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle7" parsley-trigger="change" required class="form-control" id="txtAftitle7" value="<?php echo $selres4['home_ftr_title7']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon7">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon7" parsley-trigger="change" required class="form-control" id="txtAfIcon7" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon7']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc7">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc7" parsley-trigger="change" required class="form-control" id="txtAfDesc7" ><?php echo $selres4['home_ftr7']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="panel panel-default"> 
                                    <div class="panel-heading"> 
                                        <h4 class="panel-title"> 
                                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseThree-7" class="collapsed" aria-expanded="false">
                                                Feature #8
                                            </a> 
                                        </h4> 
                                    </div> 
                                    <div id="collapseThree-7" class="panel-collapse collapse"> 
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="txtAftitle8">App Feature Title *</label>
                                                      <input type="text" name="txtAftitle8" parsley-trigger="change" required class="form-control" id="txtAftitle8" value="<?php echo $selres4['home_ftr_title8']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                      <label for="txtAfIcon8">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                                                      <input type="text" name="txtAfIcon8" parsley-trigger="change" required class="form-control" id="txtAfIcon8" placeholder="fas fa-ad" value="<?php echo $selres4['home_ftr_icon8']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12"> 
                                                    <div class="form-group">
                                                      <label for="txtAfDesc8">App Feature Description *</label>
                                                      <textarea maxlength="80" name="txtAfDesc8" parsley-trigger="change" required class="form-control" id="txtAfDesc8" ><?php echo $selres4['home_ftr8']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div>  
                    </div>

                    <!-- end row -->

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdate" id="btnUpdate" > Update</button>
                          <!-- <a href="user-list.php" class="btn btn-default waves-effect waves-light m-l-5"> Cancel</a> -->
                          <a href="privacy-policy" class="btn btn-default waves-effect waves-light"> Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- /Page Content -->

          </div> <!-- container -->
                               
        </div> <!-- content -->

        <?php include_once("include/footer.php"); ?>

      </div>
      <!-- ============================================================== -->
      <!-- End Right content here -->
      <!-- ============================================================== -->
      
    </div>
    <!-- END wrapper -->

    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <?php include_once("include/common_js.php"); ?>
      
      <script src="assets/plugins/moment/moment.js"></script>
      
      <script src="assets/js/jquery.core.js"></script>
      <script src="assets/js/jquery.app.js"></script>
      <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>
      <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

      <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
      <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
      <script src="assets/plugins/datatables/jszip.min.js"></script>
      <script src="assets/plugins/datatables/pdfmake.min.js"></script>
      <script src="assets/plugins/datatables/vfs_fonts.js"></script>
      <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
      <script src="assets/plugins/datatables/buttons.print.min.js"></script>
      <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
      <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
      <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
      <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
      <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
      <script src="assets/plugins/datatables/dataTables.colVis.js"></script>
      <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

      <script src="assets/pages/datatables.init.js"></script>

      <script type="text/javascript">
          $(document).ready(function () {
              $('#datatable').dataTable();
              $('#datatable-keytable').DataTable({keys: true});
              $('#datatable-responsive').DataTable();
              $('#datatable-colvid').DataTable({
                  "dom": 'C<"clear">lfrtip',
                  "colVis": {
                      "buttonText": "Change columns"
                  }
              });
              $('#datatable-scroller').DataTable({
                  ajax: "assets/plugins/datatables/json/scroller-demo.json",
                  deferRender: true,
                  scrollY: 380,
                  scrollCollapse: true,
                  scroller: true
              });
              var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
              var table = $('#datatable-fixed-col').DataTable({
                  scrollY: "300px",
                  scrollX: true,
                  scrollCollapse: true,
                  paging: false,
                  fixedColumns: {
                      leftColumns: 1,
                      rightColumns: 1
                  }
              });
          });
          TableManageButtons.init();

      </script>
    
  </body>
</html>