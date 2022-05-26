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

/*$selquery = "select t.*, u.fname, u.lname, u.email, u.mobile, u.cur_balance, u.won_balance, u.id as usrID from transaction_details as t
left join user_details as u on u.id=t.user_id
where t.type=0
order by t.id desc";
$selresult = mysqli_query($conn,$selquery);*/

/*$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];*/

if(isset($_GET['withdrawId']))
{
  $withdrawId = $_GET['withdrawId'];
  $insquery = "update transaction_details set status=1 where id={$withdrawId}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:withdrawal-list.php");
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


if(isset($_POST['btnReject']))
{
  $txtRid=mysqli_real_escape_string($conn,$_POST['txtRid']);
  $txtUid=mysqli_real_escape_string($conn,$_POST['txtUid']);
  $txtUcoin=mysqli_real_escape_string($conn,$_POST['txtUcoin']);
  $txtRemail=mysqli_real_escape_string($conn,$_POST['txtRemail']);
  $txtRejReason=mysqli_real_escape_string($conn,$_POST['txtRejReason']);
  $txtRname=mysqli_real_escape_string($conn,$_POST['txtRname']);

  $insquery = "update transaction_details set status=2, remark='$txtRejReason' where id=$txtRid";
    if(mysqli_query($conn,$insquery))
    {
      $seluDet = "select * from transaction_details where id=$txtRid";
      $selres4 = mysqli_query($conn,$seluDet);
      if($selres48 = mysqli_fetch_array($selres4))
      {
        $upUsrBal = "update user_details set cur_balance=cur_balance+".$selres48['coins_used'].", won_balance=won_balance+".$selres48['coins_used']." where id=".$selres48['user_id'];
        mysqli_query($conn,$upUsrBal);       

        $txtEmail = $txtRemail;
        $mailSubject = "Withdraw Money Request Decline - $appName";
        $message="<center><h2>Dear, $txtRname</h2></center>
        <p>Your request for withdrawal has been rejected due to following reason, $txtRejReason.</p>";
        
        include("include/verify_mail.php");

        header("Location:withdrawal-list");
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

if(isset($_POST['btnWithReject']))
{
  $txtFrom = mysqli_real_escape_string($conn,$_POST['txtFrom']);
  $txtTo = mysqli_real_escape_string($conn,$_POST['txtTo']);

  $rejId = range($txtFrom,$txtTo);
  //print_r($rejId);
  
  $chkQry = "select count(*) from transaction_details where (id BETWEEN $txtFrom and $txtTo) and type=0 and status=0";
  $chkqryres = mysqli_query($conn,$chkQry);
  $chkres = mysqli_fetch_array($chkqryres);

  if($chkres['0']!=0)
  {
    for($i=0;$i<count($rejId);$i++)
    {
        //echo"<script>alert(\"$rejId[$i]\");</script>";
        $verifyTran = "select id from transaction_details where id=$rejId[$i] and type=0 and status=0";
        $verifyTran1 = mysqli_query($conn,$verifyTran);
        if($verifyTran2 = mysqli_fetch_array($verifyTran1))
        {
          $insquery = "update transaction_details set status=2 where id=$rejId[$i] and type='0' and status=0";
          if(mysqli_query($conn,$insquery))
          {
            $seluDet = "select t.coins_used, t.user_id, u.email, u.fname, u.lname from transaction_details as t left join user_details as u on u.id=t.user_id where t.id=$rejId[$i]";
            $selres4 = mysqli_query($conn,$seluDet);
            if($selres48 = mysqli_fetch_array($selres4))
            {
              $upUsrBal = "update user_details set cur_balance=cur_balance+".$selres48['coins_used'].", won_balance=won_balance+".$selres48['coins_used']." where id=".$selres48['user_id'];
              mysqli_query($conn,$upUsrBal);
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
    }

    echo '<script>
          setTimeout(function() {
              swal({
                  title: "Wow!",
                  text: "Successfully reject withdraw request.",
                  type: "success"
              }, function() {
                  window.location = "withdrawal-list";
              });
          }, 1000);
      </script>';
  }
  else
  {
      echo '<script>
              setTimeout(function() {
                  swal({
                      title: "Alert!",
                      text: "No record found between given range!",
                      type: "warning"
                  }, function() {
                      window.location = "withdrawal-list";
                  });
              }, 1000);
          </script>';
  }
  //header("Location:withdrawal-list");

}

if(isset($_POST['btnWithAccept']))
{
  $txtFrom = mysqli_real_escape_string($conn,$_POST['txtFrom']);
  $txtTo = mysqli_real_escape_string($conn,$_POST['txtTo']);

  $chkQry = "select count(*) from transaction_details where (id BETWEEN $txtFrom and $txtTo) and type=0 and status=0";
  $chkqryres = mysqli_query($conn,$chkQry);
  $chkres = mysqli_fetch_array($chkqryres);

  if($chkres['0']!=0)
  {
      $upquery = "update transaction_details set status=1 where status=0 and (id BETWEEN $txtFrom and $txtTo) and type = 0";
        if(mysqli_query($conn,$upquery))
        {
          //header("Location:withdrawal-list");
          echo '<script>
              setTimeout(function() {
                  swal({
                      title: "Wow!",
                      text: "Successfully accept withdraw request.",
                      type: "success"
                  }, function() {
                      window.location = "withdrawal-list";
                  });
              }, 1000);
          </script>';
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
  else
  {
      echo '<script>
              setTimeout(function() {
                  swal({
                      title: "Alert!",
                      text: "No record found between given range!",
                      type: "warning"
                  }, function() {
                      window.location = "withdrawal-list";
                  });
              }, 1000);
          </script>';
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

    <title>Withdrawal list</title>

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
      function checkbraction(){
          return confirm('Are you sure you want to reject request of entered ids?');
      }
      function checkbaaction(){
          return confirm('Are you sure you want to accept request of entered ids?');
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
    <style type="text/css">
      .loading-overlay {
        display: none;
      }
      post-wrapper{
        position: relative;
      }
      .loading-overlay{
        display: none;
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        background: rgba(255,255,255,0.7);
      }
      .overlay-content {
          position: absolute;
          transform: translateY(-50%);
           -webkit-transform: translateY(-50%);
           -ms-transform: translateY(-50%);
          top: 50%;
          left: 0;
          right: 0;
          text-align: center;
          color: #555;
      }

      /* For Pagination Links by CodexWorld */
      div.pagination {
        font-family: Verdana, sans-serif;
        padding:20px;
        margin:7px;
      }
      div.pagination a {
        margin: 2px;
        padding: 0.3em 0.64em 0.43em 0.64em !important;
        background-color: #ff3547 !important;
        text-decoration: none;
        color: #fff;
          -webkit-box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12) !important;
          box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12) !important;
          -webkit-transition: all .2s linear !important;
          -o-transition: all .2s linear !important;
          transition: all .2s linear !important;
          -webkit-border-radius: .125rem !important;
          border-radius: .125rem !important;
        font-size: 16px !important;
      }
      div.pagination a:hover, div.pagination a:active {
        padding: 0.3em 0.64em 0.43em 0.64em;
        margin: 2px;
        background-color: #de1818 !important;
        color: #fff;
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
                    <?php if($selres4Access['user_role']=='admin') { ?>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="m-t-0 header-title"><b>Withdraw List</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Proceed withdraw request here.
                                </p>
                            </div>
                            <div class="col-sm-4 text-right">
                              <button class="btn btn-info" data-toggle="modal" data-target="#myModal1">Bulk Action</button>
                            </div>
                        </div>
                        <div class="post-search-panel">
                            <div class="row">
                              <div class="col-md-3">
                                  <input type="text" id="keywords" class="form-control" placeholder="Type keywords..." onkeyup="searchFilter();"/>    
                              </div>
                              <div class="col-md-3">
                                  <select id="sortBy" class="form-control" onchange="searchFilter();">
                                      <option value="">Sort by First Name</option>
                                      <option value="asc">Ascending</option>
                                      <option value="desc">Descending</option>
                                  </select>    
                              </div>
                            </div>
                        </div>
                        <br>
                        <div class="post-wrapper">
                          <div class="loading-overlay"><div class="overlay-content">Loading...</div></div>
                          <!-- Post list container -->
                          <div id="postContent">
                            <?php 
                            // Include pagination library file 
                            include_once 'Pagination.class.php'; 
                             
                            // Include database configuration file 
                            require_once 'include/conn.php'; 
                             
                            // Set some useful configuration 
                            $baseURL = 'getData_with.php'; 
                            $limit = 10; 
                             
                            // Count of all records 
                            $query   = $conn->query("SELECT COUNT(*) as rowNum FROM transaction_details where type=0");
                            $result  = $query->fetch_assoc(); 
                            $rowCount= $result['rowNum']; 
                             
                            // Initialize pagination class 
                            $pagConfig = array( 
                                'baseURL' => $baseURL, 
                                'totalRows' => $rowCount, 
                                'perPage' => $limit, 
                                'contentDiv' => 'postContent', 
                                'link_func' => 'searchFilter' 
                            ); 
                            $pagination =  new Pagination($pagConfig); 
                             
                            // Fetch records based on the limit 
                            $query = $conn->query("select t.*, u.fname, u.lname, u.email, u.mobile, u.cur_balance, u.won_balance, u.id as usrID from transaction_details as t
                              left join user_details as u on u.id=t.user_id
                              where t.type=0
                              order by t.id desc LIMIT $limit"); 
                             
                            if($query->num_rows > 0){ 
                            ?>
                            <div class="table-responsive">
                              <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Order Id</th>
                                        <th>Register Name</th>
                                        <!-- <th>Email</th> -->
                                        <th>Coin</th>
                                        <!-- <th>Winning Prize</th> -->
                                        <th>Amount</th>
                                        <th>Wallet</th>
                                        <th>Holder Name</th>
                                        <th>User's Mobile Number/Email</th>
                                        <th>Comment</th>
                                        <th>Req. Date</th>
                                        <th style="text-align: center;">Status</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php while($selres = $query->fetch_assoc()){ ?>
                                      <tr>
                                          <td><?php echo $selres['id']; ?></td>
                                          <td><?php echo $selres['order_id']; ?></td>
                                          <td>
                                            <?php echo $selres['fname']." ".$selres['lname']; ?>                                      
                                          </td>
                                          <!-- <td><?php //echo $selres['email']; ?></td> -->
                                          <td><?php echo $selres['coins_used']; ?></td>
                                          <!-- <td><?php //echo $selres['winPrize']; ?></td> -->
                                          <td><?php echo $selres['req_amount']; ?></td>
                                          <td><?php echo $selres['getway_name']; ?></td>
                                          <td><?php echo $selres['request_name']; ?></td>
                                          <td><?php echo $selres['req_from']; ?></td>
                                          <td><?php echo $selres['remark']; ?></td>
                                          <td><?php echo date('d-m-Y H:i:s', $selres['date']); ?></td>
                                          <!-- <td><?php //echo date('d-m-Y H:i:s', $selres['date']); ?></td> -->

                                          <?php if ($selres['status'] == 0){ ?>
                                            <td>
                                              <div class="flip-card">
                                                <div class="flip-card-inner">
                                                  <div class="flip-card-front">
                                                    Pending
                                                  </div>
                                                  <div class="flip-card-back">
                                                    <a class="btn btn-success" href="withdrawal-list.php?withdrawId=<?php echo $selres['id'];?>" data-toggle="tooltip" data-placement="top" title="Accept" data-original-title="Accept"><i class="fa fa-check"></i></a>
                                                    
                                                    <a href="#" data-rid="<?php echo $selres['id'];?>" data-uid="<?php echo $selres['user_id'];?>" data-withCoin="<?php echo $selres['coins_used'];?>" data-remail="<?php echo $selres['email']; ?>" data-rname="<?php echo $selres['fname']." ".$selres['lname']; ?>" class="btn btn-danger rejectreq" data-toggle="modal" data-target="#myModal"><i class="fa fa-times"></i></a>
                                                  </div>
                                                </div>
                                              </div>
                                            </td>
                                          <?php } else { ?>
                                            <?php if ($selres['status']==1){ ?>
                                              <td style="text-align: center; color: green;"> Completed</td>
                                            <?php } else if ($selres['status']==2) { ?>
                                              <td style="text-align: center; color: red;"> Rejected</td>
                                            <?php } ?>
                                          <?php } ?>
                                          
                                          <td>
                                            <a class="btn btn-xs btn-primary" href="withdrawal-detail.php?withdrawId=<?php echo $selres['id'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="View Details" data-original-title="View Details"><i class="fa fa-external-link"></i></a>&nbsp;&nbsp;
                                            <a class="btn btn-xs btn-primary" href="user-statistic?userStatId=<?php echo $selres['usrID'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="View User Statistic" data-original-title="ViewView User Statistic"><i class="fa fa-list"></i></a>
                                          </td>
                                      </tr>
                                    <?php } ?>
                                  </tbody>
                              </table>
                            </div>
                            <!-- Display pagination links -->
                            <?php echo $pagination->createLinks(); ?>
                            <?php 
                            }else{ 
                                echo '<p>Post(s) not found...</p>'; 
                            } 
                            ?>
                          </div>
                        </div>

                    </div>
                    <?php } else { ?>
                        <h3 class="text-danger">Access Denied</h3>
                    <?php } ?>
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

    <div id="myModal1" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Accept/Reject Withdraw Request</h4>
          </div>
          <div class="modal-body">
            <form method="post" action="withdrawal-list">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <label>From</label>
                  <input type="number" id="txtFrom" required name="txtFrom" class="form-control">      
                </div>
                <div class="col-md-6">
                  <label>To</label>
                  <input type="number" id="txtTo" required name="txtTo" class="form-control">      
                </div>
              </div>
            </div>            
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-default" name="btnWithAccept" onclick="return checkbaaction()">Accept</button>
            <button type="submit" class="btn btn-danger" name="btnWithReject" onclick="return checkbraction()">Reject</button>
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
                            columns: [ 1, 7, 3, 8]
                        }
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 1, 7, 3, 8]
                        }
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 1, 7, 3, 8]
                        }
                    } )
                ]
            } );
        });
        TableManageButtons.init();

    </script>
    <script type="text/javascript">
        $(document).on("click", ".rejectreq", function () {
             var myrecordId = $(this).data('rid');
             var myuserId = $(this).data('uid');
             var myuserCoin = $(this).data('withCoin');
             var myReemailId = $(this).data('remail');
             var myRname = $(this).data('rname');
             $(".modal-body #txtRid").val( myrecordId );
             $(".modal-body #txtUid").val( myuserId );
             $(".modal-body #txtUcoin").val( myuserCoin );
             $(".modal-body #txtRemail").val( myReemailId );
             $(".modal-body #txtRname").val( myRname );
        });
    </script>
    <script>
    // Show loading overlay when ajax request starts
    $( document ).ajaxStart(function() {
        $('.loading-overlay').show();
    });

    // Hide loading overlay when ajax request completes
    $( document ).ajaxStop(function() {
        $('.loading-overlay').hide();
    });
    </script>
    <script>
    function searchFilter(page_num) {
        page_num = page_num?page_num:0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        $.ajax({
            type: 'POST',
            url: 'getData_with.php',
            data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
            beforeSend: function () {
                $('.loading-overlay').show();
            },
            success: function (html) {
                $('#postContent').html(html);
                $('.loading-overlay').fadeOut("slow");
            }
        });
    }
    </script>
  </body>
</html>