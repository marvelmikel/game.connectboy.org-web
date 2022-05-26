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
if(isset($_POST['btnFilter']))
{
  $txtFromDate = $_POST['txtFromDate'];
  $txtToDate = $_POST['txtToDate'];
  $todayDate = date("Y-m-d");

  $selquery = "select m.id, m.title, m.time, m.per_kill, m.entry_fee as mfee, COUNT(DISTINCT(p.slot)) as particount, SUM(p.prize) as totPrize, SUM(p.kills) as totKills from match_details as m
  left join participant_details as p on p.match_id=m.id
  where m.time between unix_timestamp('$txtFromDate') and unix_timestamp('$txtToDate')
  GROUP by m.id";
}
else
{
  $selquery = "select m.id, m.title, m.time, m.per_kill, m.entry_fee as mfee, COUNT(DISTINCT(p.slot)) as particount, SUM(p.prize) as totPrize, SUM(p.kills) as totKills from match_details as m
  left join participant_details as p on p.match_id=m.id
  GROUP by m.id";
}

$selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['matchDid']))
{
  $matchDid = $_GET['matchDid'];
  $insquery = "delete from match_details where id={$matchDid}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:match-list.php");
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
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Income Report</title>

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
          return confirm('Are you sure you want to delete this Match?');
      }
      function checkCancel(){
          return confirm('Are you sure you want to Cancel this Match?');
      }
    </script>
    <style type="text/css">
      .flip-card {
        background-color: transparent;
        width: 115px;
        height: 30px;
        perspective: 1000px;
      }

      .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        /*box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);*/
      }

      .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
      }

      .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
      }

      .flip-card-front {
        background-color: transparent;
        color: black;
        z-index: 2;
      }

      .flip-card-back {
        background-color: transparent;
        color: black;
        transform: rotateY(180deg);
        z-index: 1;
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
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="m-t-0 header-title"><b>Income Report</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Detail profit and loss report match wise.
                                </p>
                            </div>
                            <div class="col-sm-8">
                              <div class="row">
                                <form method="post" action="income-report">
                                  <div class="col-md-5">
                                    <label>From:</label>
                                    <input type="date" name="txtFromDate" class="form-control" <?php  if(isset($_POST['txtFromDate'])) { if($_POST['txtFromDate']) { echo 'value='.$_POST['txtFromDate']; } } ?>>    
                                  </div>
                                  <div class="col-md-5">
                                    <label>To:</label>
                                    <input type="date" name="txtToDate" class="form-control" <?php if(isset($_POST['txtToDate'])) { if($_POST['txtToDate']) { echo 'value='.$_POST['txtToDate']; } } ?>>    
                                  </div>
                                  <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" name="btnFilter" class="btn btn-primary form-control">Filter</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                        </div>
                        <!-- <div class="row text-right">
                            <div class="col-md-12">
                              <h5>Total Fees 100 | Total Cost 30 | Net Income 70</h5>
                            </div>
                        </div> -->
                        
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Match Id</th>
                                  <!-- <th>Title</th> -->
                                  <th>Match Date</th>
                                  <th>Match Time</th>
                                  <th>Match Fees</th>
                                  <th>Total Players</th>
                                  <th>Total Fees Colleted</th>
                                  <th>Total Win Prize</th>
                                  <th>Total Kill Prize</th>
                                  <th>Total Prize Distributed</th>
                                  <th>Net Income/Expense</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($selresult)){ ?>
                                <tr>
                                    <td><?php echo $selres['id']; ?></td>
                                    <!-- <td><?php //echo $selres['title']; ?></td> -->
                                    <td><?php echo date('d-m-Y', $selres['time']); ?></td>
                                    <td><?php echo date('H:i:s', $selres['time']); ?></td>
                                    <td><?php echo $selres['mfee']; ?></td>
                                    <td><?php echo $selres['particount']; ?></td>
                                    <td>
                                      <?php 
                                        $totMtchFees = $selres['mfee']*$selres['particount'];
                                        echo $totMtchFees;
                                      ?>
                                    </td>
                                    <td>
                                      <?php 
                                        $totalKillAmt = $selres['totKills']*$selres['per_kill'];
                                        $totWinningAmt = $selres['totPrize']-$totalKillAmt; 
                                        echo $totWinningAmt; 
                                      ?>
                                    </td>
                                    <td><?php echo $totalKillAmt; ?></td>
                                    <td>
                                      <?php
                                        $totCost = $totWinningAmt+$totalKillAmt;
                                        echo $totCost;
                                      ?>
                                    </td>
                                    <td><?php echo $totMtchFees-$totCost; ?></td>
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

    <!-- ./wrapper -->
    
    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <?php include_once("include/common_js.php"); ?>

    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>

    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>
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
          var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            // Strip $ from salary column to make it numeric
                            return column === 5 ?
                                data.replace( /[$,]/g, '' ) :
                                data;
                        }
                    }
                }
            };
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
                "order": [],
                dom: 'Bfrtip',
                buttons: [
                    $.extend( true, {}, buttonCommon, {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    } )
                ]
            } );
        });
        TableManageButtons.init();

    </script>
  </body>
</html>