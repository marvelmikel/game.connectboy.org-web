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
  if(isset($_POST['btnSave']))
  {
    $txtAmount = mysqli_real_escape_string($conn,$_POST['txtAmount']);
    $txtCoin = mysqli_real_escape_string($conn,$_POST['txtCoin']);
    $txtPmethod = mysqli_real_escape_string($conn,$_POST['txtPmethod']);
    $txtTranId = mysqli_real_escape_string($conn,$_POST['txtTranId']);
    $txtExDate = mysqli_real_escape_string($conn,$_POST['txtExDate']);
    $txtComment = mysqli_real_escape_string($conn,$_POST['txtComment']);

    function voucher_code() {
      $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
      $vcode = array(); //remember to declare $pass as an array
      $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
      for ($i = 0; $i < 10; $i++) {
        $n = rand(0, $alphaLength);
        $vcode[] = $alphabet[$n];
      }
      return implode($vcode); //turn the array into a string
    }

    $txtIdate = date("Y-m-d");
    $txtDate = date("Y-m-d H:i:s");

    $insquery = "INSERT INTO tbl_gift_voucher(voucher_code, amount, coin, payment_method, transaction_id, status, is_expired, comment, issued_date, valid_till, created_by, date_created, is_del) VALUES ('".voucher_code()."','$txtAmount', $txtCoin, '$txtPmethod', '$txtTranId', 1, 0, '$txtComment', '$txtIdate', '$txtExDate', $userId, '$txtDate', 0)";
    
    if ($conn->query($insquery) === TRUE)
      {
        header("Location:voucher-list");
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
      
    $query = $conn->query("select * from tbl_gift_voucher where id={$id}");
    $getres1 = $query->fetch_assoc();
  }

  if(isset($_POST['btnUpdate']))
  {
    $txtAmount = mysqli_real_escape_string($conn,$_POST['txtAmount']);
    $txtCoin = mysqli_real_escape_string($conn,$_POST['txtCoin']);
    $txtPmethod = mysqli_real_escape_string($conn,$_POST['txtPmethod']);
    $txtTranId = mysqli_real_escape_string($conn,$_POST['txtTranId']);
    $txtExDate = mysqli_real_escape_string($conn,$_POST['txtExDate']);
    $txtComment = mysqli_real_escape_string($conn,$_POST['txtComment']);

    $txtMdate = date("Y-m-d H:i:s");
    
    $insquery = "UPDATE tbl_gift_voucher SET amount='$txtAmount', coin='$txtCoin', payment_method='$txtPmethod', transaction_id='$txtTranId', comment='$txtComment', valid_till='$txtExDate', modify_by=$userId, modify_date='$txtMdate' WHERE id=$id and is_expired=0"; 
    
    if ($conn->query($insquery) === TRUE)
      {
        header("Location:voucher-list");
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

    <title>Voucher Code</title>

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
                  
                  <h4 class="m-t-0 header-title"><b>Voucher Code</b></h4>
                  <!-- <p class="text-muted font-13 m-b-30">
                      Update voucher code detail.
                  </p> -->
                  <?php if(isset($_GET['id'])) { ?>
                  <form action="voucher-code?id=<?php echo $_GET['id'];?>" data-parsley-validate novalidate method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtAmount">Amount *</label>
                                  <input type="text" name="txtAmount" id="txtAmount" class="form-control" placeholder="100 INR" required value="<?php echo $getres1['amount']?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtCoin">Coin *</label>
                                  <input id="txtCoin" name="txtCoin" type="number" required class="form-control" value="<?php echo $getres1['coin']?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtPmethod">Payment Method</label>
                                  <input id="txtPmethod" name="txtPmethod" type="text" class="form-control" value="<?php echo $getres1['payment_method']?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtTranId">Transaction Id</label>
                                  <input id="txtTranId" name="txtTranId" type="text" class="form-control" value="<?php echo $getres1['transaction_id']?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtExDate">Expiry Date *</label>
                                  <input type="date" name="txtExDate" id="txtExDate" class="form-control" required value="<?php echo $getres1['valid_till']?>">
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="txtComment">Comment</label>
                                  <input type="text" maxlength="250" name="txtComment" id="txtComment" class="form-control" value="<?php echo $getres1['comment']?>">
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
                          <a href="voucher-list" class="btn btn-default waves-effect waves-light"> Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php } else { ?>
                  <form action="voucher-code" data-parsley-validate novalidate method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtAmount">Amount *</label>
                                  <input type="text" name="txtAmount" id="txtAmount" class="form-control" placeholder="100 INR" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtCoin">Coin *</label>
                                  <input id="txtCoin" name="txtCoin" type="number" required class="form-control">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtPmethod">Payment Method</label>
                                  <input id="txtPmethod" name="txtPmethod" type="text" class="form-control">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtTranId">Transaction Id</label>
                                  <input id="txtTranId" name="txtTranId" type="text" class="form-control">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="txtExDate">Expiry Date *</label>
                                  <input type="date" name="txtExDate" id="txtExDate1" class="form-control" required>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="txtComment">Comment</label>
                                  <input type="text" maxlength="250" name="txtComment" id="txtComment" class="form-control">
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
                          <a href="voucher-list" class="btn btn-default waves-effect waves-light"> Cancel</a>
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