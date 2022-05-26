<?php
include("include/security.php");
include("include/conn.php");

/*$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];*/
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
$selquery = "select * from tbl_lndn_testimonials order by testi_id desc";
$selres1 = mysqli_query($conn,$selquery);

if(isset($_POST['btnSave']))
{
  $txtUname = mysqli_real_escape_string($conn,$_POST['txtUname']);
  $txtComment = mysqli_real_escape_string($conn,$_POST['txtComment']);

  $txtDate = date("Y-m-d H:i:s");

  $insquery = "insert into tbl_lndn_testimonials (user_name,comment,date_created) values('{$txtUname}','{$txtComment}','{$txtDate}')";
  
  if(mysqli_query($conn,$insquery))
    {
      header("Location:landing-page-testimonials");
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
  
  $getquery1 = "select * from tbl_lndn_testimonials where testi_id={$id}";
  $getresult1 = mysqli_query($conn,$getquery1);
  $getres1 = mysqli_fetch_array($getresult1); 
}

if(isset($_POST['btnUpdate']))
{
  $txtUname = mysqli_real_escape_string($conn,$_POST['txtUname']);
  $txtComment = mysqli_real_escape_string($conn,$_POST['txtComment']);
  $txtMdate = date("Y-m-d H:i:s");

  $insquery = "update tbl_lndn_testimonials set user_name='{$txtUname}',comment='{$txtComment}',date_created='{$txtMdate}' where testi_id = $id";
  
  if(mysqli_query($conn,$insquery))
    {
      header("Location:landing-page-testimonials");
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

if(isset($_GET['did']))
{
  $did = $_GET['did'];
  
  $delquery = "delete from tbl_lndn_testimonials where testi_id=$did";
  if(mysqli_query($conn,$delquery))
    {
      header("Location:landing-page-testimonials");
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
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card-box">
                  
                  <h4 class="m-t-0 header-title"><b>Add New Testimonial</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Manage Testimonials.
                  </p>
                  <?php if(isset($_GET['id'])) { ?>
                  <form action="landing-page-testimonials.php?id=<?php echo $_GET['id'];?>" data-parsley-validate enctype="multipart/form-data" novalidate method="post">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="txtUname">User Name *</label>
                                  <input type="text" name="txtUname" value="<?php echo $getres1['user_name']?>" parsley-trigger="change" required placeholder="Enter Image Title" class="form-control" id="txtUname">
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="txtComment">Comment *</label>
                                  <textarea name="txtComment" parsley-trigger="change" class="form-control" id="txtComment" maxlength="400"><?php echo $getres1['comment']?></textarea>
                                  <script>
                                          CKEDITOR.replace( 'txtComment' );
                                  </script>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Date</label>
                                  <input readonly value="<?php echo date("Y-m-d"); ?>" type="date" class="form-control">
                                </div>
                              </div>
                            </div><br>
                        </div>
                    </div>
                     <!-- end row -->

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdate" id="btnUpdate" > Update</button>
                          <!-- <a href="user-list.php" class="btn btn-default waves-effect waves-light m-l-5"> Cancel</a> -->
                          <a href="landing-page-testimonials" class="btn btn-default waves-effect waves-light"> Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php } else { ?>
                  <form action="landing-page-testimonials.php" data-parsley-validate enctype="multipart/form-data" novalidate method="post">
                    
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="txtUname">User Name *</label>
                                  <input type="text" name="txtUname" parsley-trigger="change" required placeholder="Enter Image Title" class="form-control" id="txtUname">
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="txtComment">Comment *</label>
                                  <textarea name="txtComment" parsley-trigger="change" required class="form-control" id="txtComment" maxlength="400"></textarea>
                                  <script>
                                          CKEDITOR.replace( 'txtComment' );
                                  </script>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Date</label>
                                  <input readonly value="<?php echo date("Y-m-d"); ?>" type="date" class="form-control">
                                </div>
                              </div>
                            </div><br>
                        </div>
                    </div>
                     <!-- end row -->

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnSave" id="btnSave" > Save</button>
                          <!-- <a href="user-list.php" class="btn btn-default waves-effect waves-light m-l-5"> Cancel</a> -->
                          <a href="landing-page-testimonials" class="btn btn-default waves-effect waves-light"> Cancel</a>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php } ?>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card-box">
                  
                  <h4 class="m-t-0 header-title"><b>Testimonial List</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      You can manage Testimonial here.
                  </p>

                  <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-loader" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>Id</th>
                            <th>User Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($selres = mysqli_fetch_array($selres1)){ ?>
                          <tr class="font-13">
                              <td><?php echo $selres['testi_id']; ?></td>
                              <td><?php echo $selres['user_name']; ?></td>
                              <td><?php echo $selres['date_created']; ?></td>
                              
                              <td>
                                <a href="landing-page-testimonials.php?id=<?php echo $selres['testi_id'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update Record"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                <a href="landing-page-testimonials.php?did=<?php echo $selres['testi_id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Permanently" onclick="return checkDelete()"><i class="fa fa-trash-o"></i></a>
                              </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                  </table>
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