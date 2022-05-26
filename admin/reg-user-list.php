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
// $selquery = "select * from user_details order by id desc";
// $selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

$selqueryApp = "select * from tbl_app_details where id=1";
$selresultApp = mysqli_query($conn,$selqueryApp);
$selresApp = mysqli_fetch_array($selresultApp);
$appName = $selresApp['app_name'];

if(isset($_GET['did']))
{
  $did = $_GET['did'];
  $insquery = "delete from user_details where id={$did}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_ia']))
{
  $uid_ia = $_GET['uid_ia'];
  $insquery = "update user_details set status='0' where id={$uid_ia}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_a']))
{
  $uid_a = $_GET['uid_a'];
  $insquery = "update user_details set status='1' where id={$uid_a}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_POST['btnBremark']))
{
  $txtBUid=mysqli_real_escape_string($conn,$_POST['txtBUid']);
  $txtBremark=mysqli_real_escape_string($conn,$_POST['txtBremark']);
  $insquery = "update user_details set block_reason='$txtBremark', status='0', is_block=1 where id={$txtBUid}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_ub']))
{
  $uid_ub = $_GET['uid_ub'];
  $insquery = "update user_details set status='1', is_block=0 where id={$uid_ub}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_POST['btnAddMoney']))
{
  $txtUid=mysqli_real_escape_string($conn,$_POST['txtUid']);
  $txtAmoney=mysqli_real_escape_string($conn,$_POST['txtAmoney']);
  $txtEmail=mysqli_real_escape_string($conn,$_POST['txtEmail']);
  $txtUname=mysqli_real_escape_string($conn,$_POST['txtUname']);
  $txtCdate=date("Y-m-d H:m:s");
  $orderid=time();
  
  $insquery = "insert into transaction_details (user_id, order_id, req_amount, coins_used, remark, type, date, getway_name, status) values($txtUid, '$orderid', '{$txtAmoney}', '{$txtAmoney}', 'Add Money to Wallet', 1, '{$orderid}', 'System', 1)";
  if(mysqli_query($conn,$insquery))
  {
    $upquery = "update user_details set cur_balance=cur_balance+$txtAmoney where id={$txtUid}";
    if(mysqli_query($conn,$upquery))
    {
      $txtEmail = $txtEmail;
      $mailSubject = "Transaction Successful - $appName";
      $message="<h2>Hi, $txtUname</h2>
      <p>Thank you for transaction with us. Your payment was successfully completed and your wallet is credited with $txtAmoney.<br>
        $appName Transaction Id: $orderid <br>
        If you have query regarding this contact admin immediately.</p>";
      
      include("include/verify_mail.php");

      header("Location:reg-user-list.php");
    }
    else
    {
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

if(isset($_POST['btnWithMoney']))
{
  $txtUidW=mysqli_real_escape_string($conn,$_POST['txtUidW']);
  $txtWmoney=mysqli_real_escape_string($conn,$_POST['txtWmoney']);
  $txtUnameW=mysqli_real_escape_string($conn,$_POST['txtUnameW']);
  $txtEmailW=mysqli_real_escape_string($conn,$_POST['txtEmailW']);
  $txtCdate=date("Y-m-d H:m:s");
  $orderidW=time();
  
  $selqueryWB = "select cur_balance from user_details where id=$txtUidW";
  $selresWB = mysqli_query($conn,$selqueryWB);
  $selres1WB = mysqli_fetch_array($selresWB);

  if($selres1WB['cur_balance'] >= $txtWmoney)
  {
      $insquery = "insert into transaction_details (user_id, order_id, req_amount, remark, type, date, getway_name, coins_used, status) values($txtUidW,'$orderidW','{$txtWmoney}','Withdraw Money from Wallet',0,'{$orderidW}','offline','$txtWmoney',1)";
      if(mysqli_query($conn,$insquery))
      {
        $upquery = "update user_details set cur_balance=cur_balance-$txtWmoney where id={$txtUidW}";
        if(mysqli_query($conn,$upquery))
        {
            $txtEmail = $txtEmailW;
            $mailSubject = "Transaction Successful - $appName";
            $message="<h2>Hi, $txtUname</h2>
            <p>Thank you for transaction with us. Your payment was successfully completed and your wallet is debited with $txtWmoney.<br>
              $appName Transaction Id: $orderidW <br>
              If you have query regarding this contact admin immediately.</p>";
            
            include("include/verify_mail.php");

            header("Location:reg-user-list.php");
        }
        else
        {
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
        // echo $insquery;
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
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal(
                                            "Oops...",
                                            "Insufficient Fund!",
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

    <title>User list</title>

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
                    <div class="card-box">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b>Manage User</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    List of register user. here you can manage User.
                                </p>
                            </div>
                            <!-- <div class="col-sm-2">
                                <div class="m-t-0 text-right">
                                    <a href="user.php" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                </div>
                            </div> -->
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
                            $baseURL = 'getData.php'; 
                            $limit = 10; 
                             
                            // Count of all records 
                            $query   = $conn->query("SELECT COUNT(*) as rowNum FROM user_details"); 
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
                            $query = $conn->query("SELECT * FROM user_details ORDER BY id DESC LIMIT $limit"); 
                             
                            if($query->num_rows > 0){ 
                            ?>
                            <div class="table-responsive">
                              <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Won Bal</th>
                                        <th>Bonus Bal</th>
                                        <th>Tot Bal</th>
                                        <th>Status</th>
                                        <th>Block</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php while($selres = $query->fetch_assoc()){ ?>
                                      <tr>
                                          <td><?php echo $selres['fname']." ".$selres['lname']; ?></td>
                                          <td><?php echo $selres['username']; ?></td>
                                          <td><?php echo $selres['email']; ?></td>
                                          <td><?php echo $selres['mobile']; ?></td>
                                          <td><?php echo $selres['won_balance']; ?></td>
                                          <td><?php echo $selres['bonus_balance']; ?></td>
                                          <td><?php echo $selres['cur_balance']; ?></td>
                                          
                                          <?php if ($selres['status'] == 1){ ?>
                                            <td><a href="reg-user-list.php?uid_ia=<?php echo $selres['id'];?>" class="label label-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Inactive">Active</a></td>
                                          <?php } else { ?>
                                            <td><a href="reg-user-list.php?uid_a=<?php echo $selres['id'];?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Active" class="label label-danger">Inactive</a></td>
                                          <?php } ?>

                                          <?php if ($selres['is_block'] != 1){ ?>
                                            <td>
                                              <a href="#" class="label label-success addRemark" data-toggle="modal" data-id="<?php echo $selres['id']; ?>" data-target="#myModalBlock">Active </a>
                                            </td>
                                          <?php } else { ?>
                                            <td><a href="reg-user-list.php?uid_ub=<?php echo $selres['id'];?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Unblock" class="label label-danger">Blocked</a></td>
                                          <?php } ?>
                                          
                                          <td>
                                            <a href="reg-user-list.php?did=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Permanently" onclick="return checkDelete()"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp; 
                                            
                                            <a href="#" data-toggle="modal" data-id="<?php echo $selres['id']; ?>" data-uname="<?php echo $selres['username']; ?>" data-email="<?php echo $selres['email']; ?>" data-cbal="<?php echo $selres['cur_balance']; ?>" data-target="#myModal2" class="addBal" data-toggle="tooltip" data-placement="top" title="" data-original-title="Load Money"><i class="fa fa-money"></i> </a>&nbsp;&nbsp;

                                            <a href="view-user-details.php?userId=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="View User Details" data-original-title="User details"><i class="md md-exit-to-app"></i></a>&nbsp;&nbsp;

                                            <a href="referral-details.php?rcode=<?php echo $selres['refer'];?>&rid=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Track User Refer Activity"><i class="fa  fa-line-chart"></i></a>&nbsp;&nbsp;

                                            <a href="participation-report.php?rid=<?php echo $selres['id'];?>" class="remove-row" style="color: #5FBEAA;" data-toggle="tooltip" data-placement="top" title="" data-original-title="User Participation"><i class="fa fa-list"></i></a>
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
                </div>
            </div>
            <!-- /Page Content -->

          </div> <!-- container -->
        </div> <!-- content -->

        <?php include_once("include/footer.php"); ?>

      </div>

      <!-- Modal -->
      <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel2">Add/Withdraw Money</h4>
            </div>

            <div class="modal-body">

              <div class="row">
                  <div class="col-md-12">
                      <ul class="nav nav-tabs tabs">
                          <li class="tab">
                              <a href="#add-Money" data-toggle="tab" aria-expanded="false">
                                  <span class="visible-xs"><i class="fa fa-plus"></i></span>
                                  <span class="hidden-xs">Add Money</span>
                              </a>
                          </li>
                          <li class="tab">
                              <a href="#withdraw-Money" data-toggle="tab" aria-expanded="false">
                                  <span class="visible-xs"><i class="fa fa-minus"></i></span>
                                  <span class="hidden-xs">Withdraw Money</span>
                              </a>
                          </li>
                      </ul>
                      <div class="tab-content"> 
                          <div class="tab-pane active" id="add-Money"> 
                              <form role="form" action="reg-user-list.php" method="post" data-parsley-validate novalidate>
                                  <input type="text" hidden name="txtUid" id="txtUid">
                                    <div class="form-group">
                                        <label for="txtUname">User Name</label>
                                        <input type="text" readonly class="form-control" id="txtUname" name="txtUname" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtEmail">Email</label>
                                        <input type="text" readonly class="form-control" id="txtEmail" name="txtEmail" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtTbal">Total Balance</label>
                                        <input type="text" readonly class="form-control" id="txtTbal" name="txtTbal" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtAmoney">Add Money to Wallet </label>
                                        <input type="number" class="form-control" id="txtAmoney" required parsley-trigger="change" name="txtAmoney">
                                    </div>
                                    
                                    <button type="submit" name="btnAddMoney" class="btn btn-default waves-effect waves-light">Save</button>
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                              </form>
                          </div>
                          <div class="tab-pane" id="withdraw-Money">
                              <form role="form" action="reg-user-list.php" method="post" data-parsley-validate novalidate>
                                  <input type="text" hidden name="txtUidW" id="txtUidW">
                                    <div class="form-group">
                                        <label for="txtUnameW">User Name</label>
                                        <input type="text" readonly class="form-control" id="txtUnameW" name="txtUnameW" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtEmail">Email</label>
                                        <input type="text" readonly class="form-control" id="txtEmailW" name="txtEmailW" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtTbal">Total Balance</label>
                                        <input type="text" readonly class="form-control" id="txtTbalW" name="txtTbalW" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtWmoney">Withdraw Money from Wallet </label>
                                        <input type="number" class="form-control" id="txtWmoney" required parsley-trigger="change" name="txtWmoney">
                                    </div>
                                    
                                    <button type="submit" name="btnWithMoney" class="btn btn-default waves-effect waves-light">Save</button>
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
              
            </div>

        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

      <div class="modal right fade" id="myModalBlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel23">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel23">Block User</h4>
              </div>

              <div class="modal-body">

                <div class="row">
                  <div class="col-md-12">
                    <form role="form" action="reg-user-list.php" method="post" data-parsley-validate novalidate>
                      <input type="text" hidden name="txtBUid" id="txtBUid">
                      <div class="form-group">
                          <label for="txtBremark">Reason</label>
                          <textarea class="form-control" id="txtBremark" required name="txtBremark"></textarea>
                      </div>
                      <button type="submit" name="btnBremark" class="btn btn-default waves-effect waves-light">Save</button>
                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                    </form>
                  </div>
                </div>

              </div>
            </div><!-- modal-content -->
          </div><!-- modal-dialog -->
      </div><!-- modal -->

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
                "order": []
            } );
        });
        TableManageButtons.init();

    </script>
    <script type="text/javascript">
        $(document).on("click", ".addBal", function () {
             var myrecordId = $(this).data('id');
             var myRuname = $(this).data('uname');
             var myREmail = $(this).data('email');
             var myRcbal = $(this).data('cbal');
             $(".modal-body #txtUid").val( myrecordId );
             $(".modal-body #txtUname").val( myRuname );
             $(".modal-body #txtEmail").val( myREmail );
             $(".modal-body #txtTbal").val( myRcbal );
             $(".modal-body #txtUidW").val( myrecordId );
             $(".modal-body #txtUnameW").val( myRuname );
             $(".modal-body #txtEmailW").val( myREmail );
             $(".modal-body #txtTbalW").val( myRcbal );
        });
        $(document).on("click", ".addRemark", function () {
             var myrecordId1 = $(this).data('id');
             $(".modal-body #txtBUid").val( myrecordId1 );
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
            url: 'getData.php',
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