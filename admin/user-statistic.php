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
$selqueryApp = "select * from tbl_app_details where id=1";
$selresultApp = mysqli_query($conn,$selqueryApp);
$selresApp = mysqli_fetch_array($selresultApp);
$appName = $selresApp['app_name'];

$selquery = "select t.*, u.fname, u.lname, u.email, u.mobile, u.cur_balance, u.won_balance from transaction_details as t
left join user_details as u on u.id=t.user_id
where t.type=0
order by t.id desc";
$selresult = mysqli_query($conn,$selquery);

if(isset($_GET['userStatId']))
{
  $userStatId = $_GET['userStatId'];

  $getUdetQry = 'select fname, lname, username from user_details where id='.$userStatId;
  $udetQryRes = mysqli_query($conn,$getUdetQry);
  $selresUserDet = mysqli_fetch_array($udetQryRes);

  $tranQry = "select req_amount, coins_used, getway_name, remark, type, date from transaction_details where user_id='$userStatId' order by id desc";
  $tranQryRes = mysqli_query($conn,$tranQry);
  
  $partiQry = "select match_id, title, pubg_id, prize, p.created  FROM participant_details as p
              left join match_details as m on m.id=p.match_id 
              WHERE user_id='$userStatId' order by p.id desc";
  $partiQryRes = mysqli_query($conn,$partiQry);

  $refQry = "select refer_points,refer_code, refer_status, refer_date 
              FROM referral_details as r
              left join user_details as u on u.username=r.username
              WHERE r.id='$userStatId' order by r.id desc";
  $refQryRes = mysqli_query($conn,$refQry);

  $rewQry = "select r.* FROM rewarded_details as r 
              left join user_details as u on r.username=u.username
              where u.id='$userStatId' order by r.id desc";
  $rewQryRes = mysqli_query($conn,$rewQry);

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

    <title>User Statistic</title>

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
          return confirm('Are you sure you want to delete this User?');
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
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b><?php echo $selresUserDet['fname'].' '.$selresUserDet['lname']; ?>'s Statistic</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Particular user overall history.
                                </p>
                            </div>
                        </div>
                        <h3>Transaction History</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Date</th>
                                  <th>Amount</th>
                                  <th>PlayCoins</th>
                                  <th>Payment gateway</th>
                                  <th>Remark</th>
                                  <th>Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($tranQryRes)){ ?>
                                <tr>
                                    <td><?php echo date('d-m-Y H:i:s', $selres['date']); ?></td>
                                    <td><?php echo $selres['req_amount']; ?></td>
                                    <td><?php echo $selres['coins_used']; ?></td>
                                    <td><?php echo $selres['getway_name']; ?></td>
                                    <td><?php echo $selres['remark']; ?></td>
                                    <td><?php if($selres['type']=='0') {echo 'Debit'; } else { echo 'Credit'; } ?></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                        </table><br>
                        <h3>Tournament Participation</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Match Id</th>
                                  <th>Match Title</th>
                                  <th>Pubg ID</th>
                                  <th>Win PlayCoins</th>
                                  <th>Date</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($partiQryRes)){ ?>
                                <tr>
                                    <td><?php echo $selres['match_id']; ?></td>
                                    <td><?php echo $selres['title']; ?></td>
                                    <td><?php echo $selres['pubg_id']; ?></td>
                                    <td><?php echo $selres['prize']; ?></td>
                                    <td><?php echo $selres['created']; ?></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                        </table><br>
                        <h3>Referral History</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Refer Code</th>
                                  <th>Refer PlayCoins</th>
                                  <th>Status</th>
                                  <th>Date</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($refQryRes)){ ?>
                                <tr>
                                    <td><?php echo $selres['refer_code']; ?></td>
                                    <td><?php echo $selres['refer_points']; ?></td>
                                    <td><?php echo $selres['refer_status']; ?></td>
                                    <td><?php echo date('d-m-Y', $selres['refer_date']); ?></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                        </table><br>
                        <h3>Rewarded History</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>User Name</th>
                                  <th>Rewarded PlayCoins</th>
                                  <th>Date</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($rewQryRes)){ ?>
                                <tr>
                                    <td><?php echo $selres['username']; ?></td>
                                    <td><?php echo $selres['reward_points']; ?></td>
                                    <td><?php echo date('d-m-Y', $selres['reward_date']); ?></td>
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

    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Reject Withdraw Request</h4>
          </div>
          <div class="modal-body">
            <form method="post" action="withdrawal-list">
            <input type="hidden" id="txtRid" value="" name="txtRid" >
            <input type="hidden" id="txtUid" value="" name="txtUid" >
            <input type="hidden" id="txtUcoin" value="" name="txtUcoin" >
            <input type="hidden" id="txtRemail" value="" name="txtRemail">
            <input type="hidden" id="txtRname" value="" name="txtRname">
            <label>Reject Reason</label>
            <textarea class="form-control" name="txtRejReason" placeholder="e.g Insufficient Fund" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="btnReject">Reject</button>
            </form>
          </div>
        </div>

      </div>
    </div>

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
                        extend: 'copyHtml5'
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'excelHtml5'
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'pdfHtml5'
                    } )
                ]
            } );
        });
        TableManageButtons.init();

    </script>
  </body>
</html>