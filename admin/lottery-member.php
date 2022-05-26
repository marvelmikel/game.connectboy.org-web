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
if(isset($_GET['lotteryId']))
{
  $lotteryId = $_GET['lotteryId'];
  
  $selquery = "select r.*, u.email, u.mobile from result_details as r 
  left join user_details as u on u.id= r.user_id
  where r.lottery_id=$lotteryId order by r.id desc";
  $selres1 = mysqli_query($conn,$selquery);

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

    <title>Lottery Member List</title>

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
          return confirm('Are you sure you want to Inactive this Lottery?');
      }
      function checkDelete1(){
          return confirm('Are you sure you want to Active this Lottery?');
      }
      function checkDelete2(){
          return confirm('Are you sure you want to Delete this Lottery?');
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
                  <div class="row">
                    <div class="col-md-9">
                      <h4 class="m-t-0 header-title"><b>Lottery Member List</b></h4>
                    </div>
                    <div class="col-md-3">
                        <a href="lottery-list" class="btn btn-success" style="float: right;">Back to Lottery List</a>    
                    </div>
                  </div><br>

                  <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-loader" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>Id</th>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Lottery Number</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($selres = mysqli_fetch_array($selres1)){ ?>
                          <tr class="font-13">
                              <td><?php echo $selres['id']; ?></td>
                              <td><?php echo $selres['user_id']; ?></td>
                              <td><?php echo $selres['name']; ?></td>
                              <td><?php echo $selres['email']; ?></td>
                              <td><?php echo $selres['mobile']; ?></td>
                              <td><?php echo $selres['lottery_no']; ?></td>
                              <?php if($selres['win']==0) { ?>
                                <td>-</td>
                              <?php } else { ?>
                                <td>
                                    Winner
                                </td>
                              <?php } ?>
                              <td><?php echo $selres['created']; ?></td>
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