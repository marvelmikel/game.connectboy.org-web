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


$query=mysqli_query($conn,"select * from tbl_lndn_screenshot");

if(isset($_POST['btnSave']))
  {

  //$r_id='1';
  foreach ($_FILES['upload']['name'] as $key => $name){
 
    $newFilename = time() . "_" . $name;
    move_uploaded_file($_FILES['upload']['tmp_name'][$key], 'screen_shot/' . $newFilename);
    $location = 'screen_shot/' . $newFilename;
 
    mysqli_query($conn,"insert into tbl_lndn_screenshot (img_path) values ('$location')");
  }
  header('location:landing-page-screen-shot');
}
if(isset($_POST['btnUpdate']))
  {
  for($i=0;$i<count($_POST['imgDes']);$i++)
      {
        mysqli_query($conn,"update tbl_lndn_screenshot set
                img_name='{$_POST['imgDes'][$i]}'
                where id='{$_POST['imgId'][$i]}'");
      }
  header('location:landing-page-screen-shot');
}


if(isset($_GET['sid']))
{
  $sid = $_GET['sid'];
  $insquery = "update core_users set status=0 where user_id={$sid}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:users");
  }
  else
  {
    echo "<script>alert(\"Something went wrong\");</script>";
  }
}

if(isset($_GET['did']))
{
  $did = $_GET['did'];
  $insquery = "delete from tbl_lndn_screenshot where id={$did}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:landing-page-screen-shot");
  }
  else
  {
    echo "<script>alert(\"Something went wrong\");</script>";
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

    <title>Landing Page Screen Shot</title>

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
                  
                  <h4 class="m-t-0 header-title"><b>Landing Page - Screen Shot</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Manage Screen shot.
                  </p>
                  <form action="landing-page-screen-shot.php" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <input type="file" name="upload[]" multiple class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <button class="btn btn-primary" type="submit" name="btnSave">
                                  <i class="fa fa-upload mr-xs"></i>
                                  Upload Files
                                </button>
                            </div>
                        </div>
                    </div>                    
                  </form>
                  <hr>

                  <form action="landing-page-screen-shot.php" method="post" enctype="multipart/form-data">
                    <?php while($row=mysqli_fetch_array($query)) { ?>
                      <div class="isotope-item document col-sm-6 col-md-4 col-lg-3">
                        <div class="thumbnail">
                          <div class="thumb-preview">
                            <a class="thumb-image" href="<?php echo $row['img_path']; ?>">
                              <img src="<?php echo $row['img_path']; ?>" class="img-rounded" alt="<?php echo $row['img_name']; ?>">
                            </a>
                          </div>
                          <h5 class="mg-title text-semibold"><input type="text" name="imgDes[]" value="<?php echo $row['img_name']; ?>" class="form-control" placeholder="alt tag"></h5>
                          <input type="text" name="imgId[]" hidden value="<?php echo $row['id']; ?>">
                          <a href="landing-page-screen-shot?did=<?php echo $row['id']; ?>">Remove</a>
                        </div>
                      </div>
                    <?php } ?>
                    <?php if(mysqli_num_rows($query)>=1) { ?>
                    <input type="submit" value="Save Changes" name="btnUpdate" class="btn btn-block btn-primary btn-md pt-sm pb-sm text-md">
                    <?php } ?>    
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