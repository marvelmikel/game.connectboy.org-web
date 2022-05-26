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
  $selqueryI1 = "select * from tbl_image where img_type=1";
  $selresI1 = mysqli_query($conn,$selqueryI1);

  $selqueryR1 = "select * from tbl_rules where rule_type=1";
  $selresR1 = mysqli_query($conn,$selqueryR1);

  if(isset($_POST['btnSave']))
  {
    $txtLtitle = mysqli_real_escape_string($conn,$_POST['txtLtitle']);
    $txtCover = mysqli_real_escape_string($conn,$_POST['txtCover']);
    $txtTime = mysqli_real_escape_string($conn,$_POST['txtTime']);
    $txtTime = strtotime($txtTime);
    $txtRule = mysqli_real_escape_string($conn,$_POST['txtRule']);
    $txtFees = mysqli_real_escape_string($conn,$_POST['txtFees']);
    $txtPrize = mysqli_real_escape_string($conn,$_POST['txtPrize']);
    $txtSize = mysqli_real_escape_string($conn,$_POST['txtSize']);

    $txtDate = date("Y-m-d H:i:s");

    $selquery ="select * from lottery_details where title='$txtLtitle'";
    $selresult = mysqli_query($conn,$selquery);
    if($selres = mysqli_fetch_array($selresult))
    {
        echo "<script>alert(\"Already Added\");</script>";
    }
    else
    {
        $insquery = "insert into lottery_details(title, cover, time, rules, fee, prize, size, status) VALUES ('$txtLtitle', '$txtCover', '$txtTime', '$txtRule', '$txtFees', '$txtPrize', '$txtSize', '0')";
    }
    if(mysqli_query($conn,$insquery))
      {
        header("Location:lottery-list");
      }
      else
      {
          //echo $insquery;
          echo '<script type="text/javascript">';
          echo 'setTimeout(function () { swal(
                                                "Oops...",
                                                "Something went wrong !!",
                                                "error"
                                              );';
          echo '}, 1000);</script>';
      }

  }

  if(isset($_GET['id']))
  {
    $id = $_GET['id'];
    
    $getquery1 = "select * from lottery_details where id={$id}";
    $getresult1 = mysqli_query($conn,$getquery1);
    $getres1 = mysqli_fetch_array($getresult1); 
  }

  if(isset($_POST['btnUpdate']))
  {
    $txtLtitle = mysqli_real_escape_string($conn,$_POST['txtLtitle']);
    $txtCover = mysqli_real_escape_string($conn,$_POST['txtCover']);
    $txtTime = mysqli_real_escape_string($conn,$_POST['txtTime']);
    $txtTime = strtotime($txtTime);
    $txtRule = mysqli_real_escape_string($conn,$_POST['txtRule']);
    $txtFees = mysqli_real_escape_string($conn,$_POST['txtFees']);
    $txtPrize = mysqli_real_escape_string($conn,$_POST['txtPrize']);
    $txtSize = mysqli_real_escape_string($conn,$_POST['txtSize']);

    $txtMdate = date("Y-m-d H:i:s");

    
    $insquery = "update lottery_details SET title='$txtLtitle', cover='$txtCover', time='$txtTime', rules='$txtRule', fee='$txtFees', prize='$txtPrize', size='$txtSize' WHERE id=$id"; 
    
    if(mysqli_query($conn,$insquery))
      {
        header("Location:lottery-list");
      }
      else
      {
          //echo $insquery;
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

    <title>Add/Update Lottery</title>

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
          return confirm('Are you sure you want to delete this Lottery?');
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
                  
                  <h4 class="m-t-0 header-title"><b>Lottery Details</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Enter Lottery details.
                  </p>
                  <div class="col-md-12 col-sm-12">
                    <?php if(isset($_SESSION['msg'])){?> 
                     <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                      <?php echo $_SESSION['msg'] ; ?></a> </div>
                    <?php unset($_SESSION['msg']);}?> 
                  </div>
                  <?php if(isset($_GET['id'])) { ?>
                  <form action="new-lottery?id=<?php echo $_GET['id'];?>" data-parsley-validate novalidate method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtLtitle">Title *</label>
                                  <input type="text" name="txtLtitle" id="txtLtitle" class="form-control" value="<?php echo $getres1['title']?>" placeholder="" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtCover">Select Cover Image</label>
                                  <select name="txtCover" class="select2a form-control"  data-placeholder="Choose ..." id="txtCover" required>
                                  <option value="">--- Select ---</option>
                                  <?php while ($selres41 = mysqli_fetch_array($selresI1)){ ?>
                                      <option <?php if ($getres1['cover']==$selres41['img_id']) { echo "selected"; } ?> value="<?php echo $selres41['img_id']; ?>"><?php echo $selres41['image_name']; ?></option>
                                  <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtCover">Time *</label>
                                  <input id="txtTime" name="txtTime" value="<?php echo date('Y-m-d\TH:i', $getres1['time']); ?>" type="datetime-local" required class="form-control">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtRule">Select Rules</label>
                                  <select name="txtRule" class="select2a form-control" data-placeholder="Choose ..." id="txtRule" required>
                                  <option value="">--- Select ---</option>
                                  <?php while ($selres41 = mysqli_fetch_array($selresR1)){ ?>
                                      <option <?php if ($getres1['rules']==$selres41['rule_id']) { echo "selected"; } ?> value="<?php echo $selres41['rule_id']; ?>"><?php echo $selres41['rule_title']; ?></option>
                                  <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtFees">Fees *</label>
                                  <input type="number" name="txtFees" id="txtFees" class="form-control" value="<?php echo $getres1['fee']?>" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtPrize">Prize *</label>
                                  <input type="text" name="txtPrize" id="txtPrize" class="form-control" value="<?php echo $getres1['prize']?>" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtSize">Size *</label>
                                  <input type="number" name="txtSize" id="txtSize" class="form-control" value="<?php echo $getres1['size']?>" required>
                                </div>
                              </div>
                            </div><br>
                        </div>
                    </div>
                     <!-- end row -->

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdate"> Update</button>
                          <a href="lottery-list" class="btn btn-default waves-effect waves-light"> Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php } else { ?>
                  <form action="new-lottery" data-parsley-validate novalidate method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtLtitle">Title *</label>
                                  <input type="text" name="txtLtitle" id="txtLtitle" class="form-control" value="<?php echo $getres1['title']?>" placeholder="" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtCover">Select Cover Image</label>
                                  <select name="txtCover" class="select2a form-control" data-placeholder="Choose ..." id="txtCover" required>
                                  <option value="">--- Select ---</option>
                                  <?php while ($selres41 = mysqli_fetch_array($selresI1)){ ?>
                                      <option value="<?php echo $selres41['img_id']; ?>"><?php echo $selres41['image_name']; ?></option>
                                  <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtCover">Time *</label>
                                  <input id="txtTime" name="txtTime" type="datetime-local" class="form-control" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtRule">Select Rules</label>
                                  <select name="txtRule" class="select2a form-control" data-placeholder="Choose ..." id="txtRule" required>
                                  <option value="">--- Select ---</option>
                                  <?php while ($selres41 = mysqli_fetch_array($selresR1)){ ?>
                                      <option value="<?php echo $selres41['rule_id']; ?>"><?php echo $selres41['rule_title']; ?></option>
                                  <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtFees">Fees *</label>
                                  <input type="number" name="txtFees" id="txtFees" class="form-control" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtPrize">Prize *</label>
                                  <input type="text" name="txtPrize" id="txtPrize" class="form-control" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtSize">Size *</label>
                                  <input type="number" name="txtSize" id="txtSize" class="form-control" required>
                                </div>
                              </div>
                            </div><br>
                        </div>
                    </div>
                     <!-- end row -->

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnSave"> Save</button>
                          <a href="lottery-list" class="btn btn-default waves-effect waves-light"> Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php } ?>
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