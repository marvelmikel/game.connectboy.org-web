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
$selquery = "select * from lottery_details order by id desc";
$selres1 = mysqli_query($conn,$selquery);

if(isset($_GET['did']))
{
  $did = $_GET['did'];
  
  $delquery = "delete from lottery_details where id=$did";
  if(mysqli_query($conn,$delquery))
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

if(isset($_GET['iid']))
{
  $iid = $_GET['iid'];
  
  $upStatusquery = "update lottery_details set status=1 where id=$iid";
  if(mysqli_query($conn,$upStatusquery))
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

if(isset($_GET['rid']))
{
  $rid = $_GET['rid'];
  
  $upStatusquery = "update lottery_details set status='1' where id=$rid";
  if(mysqli_query($conn,$upStatusquery))
    {
      $selWinner = 'select id from result_details where lottery_id='.$rid.' order by rand() limit 1';
      $selresWinner = mysqli_query($conn,$selWinner);
      $resWinner = mysqli_fetch_array($selresWinner);

      $submitWinner = "update result_details set win='1' where id=".$resWinner['id'];
      if(mysqli_query($conn,$submitWinner))
      {
        header("Location:lottery-list");
      }
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

    <title>Lottery List</title>

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
          return confirm('Are you sure you want to Declare Result for this Lottery?');
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
                      <h4 class="m-t-0 header-title"><b>Lottery List</b></h4>
                      <p class="text-muted font-13 m-b-30">
                          Manage Lottery from here.
                      </p>    
                    </div>
                    <div class="col-md-3">
                        <a href="new-lottery" class="btn btn-success" style="float: right;">Add New Lottery</a>    
                    </div>
                  </div>                  
                  
                  <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-loader" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Time</th>
                            <th>Fee</th>
                            <th>Prize</th>
                            <th>Size</th>
                            <th>Total Joied</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($selres = mysqli_fetch_array($selres1)){ ?>
                          <tr class="font-13">
                              <td><?php echo $selres['id']; ?></td>
                              <td><?php echo $selres['title']; ?></td>
                              <td><?php echo date('d-m-Y H:i:s', $selres['time']); ?></td>
                              <td><?php echo $selres['fee']; ?></td>
                              <td><?php echo $selres['prize']; ?></td>
                              <td><?php echo $selres['size']; ?></td>
                              <td><?php echo $selres['joined']; ?></td>
                              <?php if($selres['status']==0) { ?>
                                <td>
                                    <a href="lottery-list.php?rid=<?php echo $selres['id'];?>" class="remove-row" style="color: green;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Declare Result" onclick="return checkDelete()">Active</a>
                                </td>
                              <?php } else { ?>
                                <td>
                                    Result Declared
                                </td>
                              <?php } ?>
                              <td>
                                <a href="lottery-member.php?lotteryId=<?php echo $selres['id'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Lottery Member"><i class="fa fa-user"></i></a>&nbsp;&nbsp;
                                <?php if($selres['status']==0) { ?>
                                <a href="new-lottery.php?id=<?php echo $selres['id'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update Record"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                <a href="lottery-list.php?did=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Permanently" onclick="return checkDelete2()"><i class="fa fa-trash-o"></i></a>
                                <?php } ?>
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