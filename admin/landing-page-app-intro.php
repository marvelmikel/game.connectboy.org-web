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
    $txtAintro = mysqli_real_escape_string($conn,$_POST['txtAintro']);
    $txtAftr1 = mysqli_real_escape_string($conn,$_POST['txtAftr1']);
    $txtAftr2 = mysqli_real_escape_string($conn,$_POST['txtAftr2']);
    $txtAftr3 = mysqli_real_escape_string($conn,$_POST['txtAftr3']);
    $txtAftrDesc1 = mysqli_real_escape_string($conn,$_POST['txtAftrDesc1']);
    $txtAftrDesc2 = mysqli_real_escape_string($conn,$_POST['txtAftrDesc2']);
    $txtAftrDesc3 = mysqli_real_escape_string($conn,$_POST['txtAftrDesc3']);
    $txtAftrIcon1 = mysqli_real_escape_string($conn,$_POST['txtAftrIcon1']);
    $txtAftrIcon2 = mysqli_real_escape_string($conn,$_POST['txtAftrIcon2']);
    $txtAftrIcon3 = mysqli_real_escape_string($conn,$_POST['txtAftrIcon3']);

    $txtDate = date("Y-m-d H:i:s");
      
      $updquery = "update tbl_lndn_home_page set app_intro='$txtAintro', app_intro_ftr_title1='$txtAftr1', app_intro_ftr_title2='$txtAftr2', app_intro_ftr_title3='$txtAftr3', app_intro_feature1='$txtAftrDesc1', app_intro_feature2='$txtAftrDesc2', app_intro_feature3='$txtAftrDesc3', app_intro_icon1='$txtAftrIcon1', app_intro_icon2='$txtAftrIcon2', app_intro_icon3='$txtAftrIcon3', last_modi_date='$txtDate' where id=1";
    
      if(mysqli_query($conn,$updquery))
      {
          header("Location:landing-page-app-intro");
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
                  
                  <h4 class="m-t-0 header-title"><b>Landing Page - App Intro</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Update App introduction screen content and images.
                  </p>
                  <p class="fntawsm-icon">Important Notes:</p>
                  <p>For icon we have used font awesome library version 5.10.2</p>
                  <p>Visit <a href="https://fontawesome.com/icons">font awesome</a> and explore thousand of icons for free.</p>
                  <p>Example: Find this text on icon page <code data-balloon="Copy HTML" data-balloon-pos="down" class="dib f2 hover-cyan7"><span class="o-40">&lt;i class="</span>fas fa-ad<span class="o-40">"&gt;&lt;/i&gt;</span></code> and copy only <span class="fntawsm-icon">class name (fas fa-ad)</span> and paste it to icon box below.</p>
                  <hr>
                  <form action="landing-page-app-intro.php" data-parsley-validate novalidate method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="form-group">
                              <label for="txtAintro">App Introduction *</label>
                              <textarea maxlength="100" name="txtAintro" parsley-trigger="change" required class="form-control" id="txtAintro" ><?php echo $selres4['app_intro']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3><u>Feature 1</u></h3>
                        <div class="col-lg-6">
                            <div class="form-group">
                              <label for="txtAftr1">App Feature Title *</label>
                              <input type="text" name="txtAftr1" parsley-trigger="change" required class="form-control" id="txtAftr1" value="<?php echo $selres4['app_intro_ftr_title1']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6"> 
                            <div class="form-group">
                              <label for="txtAftrIcon1">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                              <input type="text" name="txtAftrIcon1" parsley-trigger="change" required class="form-control" id="txtAftrIcon1" placeholder="fas fa-ad" value="<?php echo $selres4['app_intro_icon1']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-12"> 
                            <div class="form-group">
                              <label for="txtAftrDesc1">App Feature Description *</label>
                              <textarea maxlength="140" name="txtAftrDesc1" parsley-trigger="change" required class="form-control" id="txtAftrDesc1" ><?php echo $selres4['app_intro_feature1']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3><u>Feature 2</u></h3>
                        <div class="col-lg-6"> 
                            <div class="form-group">
                              <label for="txtAftr2">App Feature Title *</label>
                              <input type="text" name="txtAftr2" parsley-trigger="change" required class="form-control" id="txtAftr2" value="<?php echo $selres4['app_intro_ftr_title2']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6"> 
                            <div class="form-group">
                              <label for="txtAftrIcon2">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                              <input type="text" name="txtAftrIcon2" parsley-trigger="change" required class="form-control" id="txtAftrIcon2" placeholder="fas fa-ad" value="<?php echo $selres4['app_intro_icon2']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-12"> 
                            <div class="form-group">
                              <label for="txtAftrDesc2">App Feature Description *</label>
                              <textarea maxlength="140" name="txtAftrDesc2" parsley-trigger="change" required class="form-control" id="txtAftrDesc2" ><?php echo $selres4['app_intro_feature2']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3><u>Feature 3</u></h3>
                        <div class="col-lg-6"> 
                            <div class="form-group">
                              <label for="txtAftr3">App Feature Title *</label>
                              <input type="text" name="txtAftr3" parsley-trigger="change" required class="form-control" id="txtAftr3" value="<?php echo $selres4['app_intro_ftr_title3']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6"> 
                            <div class="form-group">
                              <label for="txtAftrIcon3">App Feature Icon *</label> <small class="fntawsm-icon">add only class name</small>
                              <input type="text" name="txtAftrIcon3" parsley-trigger="change" required class="form-control" id="txtAftrIcon3" placeholder="fas fa-ad" value="<?php echo $selres4['app_intro_icon3']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-12"> 
                            <div class="form-group">
                              <label for="txtAftrDesc3">App Feature Description *</label>
                              <textarea maxlength="140" name="txtAftrDesc3" parsley-trigger="change" required class="form-control" id="txtAftrDesc3" ><?php echo $selres4['app_intro_feature3']; ?></textarea>
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