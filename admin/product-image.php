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
$selquery = "select * from product_details order by id desc";
$selres1 = mysqli_query($conn,$selquery);

if(isset($_GET['gid']))
{
  $gid = $_GET['gid'];

  $query=mysqli_query($conn,"select * from tbl_product_img where prod_id='$gid'");

  if(isset($_POST['btnSave']))
    {
 
    //$r_id='1';
    foreach ($_FILES['upload']['name'] as $key => $name){
   
      $newFilename = time() . "_" . $name;
      move_uploaded_file($_FILES['upload']['tmp_name'][$key], 'upload/product_img/' . $newFilename);
      $location = 'upload/product_img/' . $newFilename;
   
      mysqli_query($conn,"insert into tbl_product_img (prod_id, prod_img) values ($gid,'$location')");
    }
    header('location:product-image.php?gid='.$gid);
  }
  if(isset($_POST['btnUpdate']))
    {
    for($i=0;$i<count($_POST['imgDes']);$i++)
        {
          mysqli_query($conn,"update tbl_product_img set
                  img_desc='{$_POST['imgDes'][$i]}'
                  where img_id='{$_POST['imgId'][$i]}' and prod_id='$gid'");
        }
    header('location:product-image.php?gid='.$gid);       
  }
}

if(isset($_GET['gid'])&isset($_GET['did']))
{
  $gid = $_GET['gid'];
  $did = $_GET['did'];

  $delquery = "delete from tbl_product_img where id=$did and prod_id=$gid";
  if(mysqli_query($conn,$delquery))
    {
      header("Location:product-image?gid=".$gid);
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

    <title>Product Image</title>

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
    <script language="JavaScript" type="text/javascript">
      function checkDelete(){
          return confirm('Are you sure you want to Inactive this Product?');
      }
      function checkDelete1(){
          return confirm('Are you sure you want to Active this Product?');
      }
      function checkDelete2(){
          return confirm('Are you sure you want to Delete this Product?');
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
                  <form action="product-image.php?gid=<?php echo $_GET['gid'];?>" method="post" class="form-horizontal" enctype="multipart/form-data">
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
                </div>
              </div>
            </div>

            <div class="card-box">
              <div class="row">
                <div class="col-md-12">
                  <?php while($row=mysqli_fetch_array($query)) { ?>
                    <div class="isotope-item document col-sm-6 col-md-4 col-lg-3">
                      <div class="thumbnail">
                        <div class="thumb-preview">
                          <a class="thumb-image" href="<?php echo $row['prod_img']; ?>">
                            <img src="<?php echo $row['prod_img']; ?>" class="img-responsive" alt="Image">
                          </a>
                        </div>
                        <h5 class="text-center mg-title text-semibold"><a href="product-image?gid=<?php echo $_GET['gid'];?>&did=<?php echo $row['id']; ?>" style="color: red;">Remove</a></h5>
                      </div>
                    </div>
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
              //$('#datatable-responsive').DataTable();
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
              $('#datatable-responsive').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
          });
          TableManageButtons.init();

      </script>
    
  </body>
</html>