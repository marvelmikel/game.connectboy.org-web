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
// $selquery = "select T.*,U.fname,U.lname from transaction_details as T left join user_details as U on U.id=T.user_id order by T.id desc";
// $selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['withdrawId']))
{
  $withdrawId = $_GET['withdrawId'];
  $insquery = "update withdraw_details set payment_status=2 where id={$withdrawId}";
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

if(isset($_GET['matchIdFn']))
{
  $matchIdFn = $_GET['matchIdFn'];
  $insquery = "update match_details set match_status='2' where id={$matchIdFn}";
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
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Transaction list</title>

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
                                <h4 class="m-t-0 header-title"><b>Transaction List</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    View transaction request here.
                                </p>
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
                            $baseURL = 'getData_tran.php'; 
                            $limit = 10; 
                             
                            // Count of all records 
                            $query   = $conn->query("SELECT COUNT(*) as rowNum FROM transaction_details"); 
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
                            $query = $conn->query("select T.*,U.fname,U.lname from transaction_details as T left join user_details as U on U.id=T.user_id order by T.id desc LIMIT $limit"); 
                             
                            if($query->num_rows > 0){ 
                            ?>
                            <div class="table-responsive">
                              <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>User Name</th>
                                        <th>Order Id</th>
                                        <th>Coins</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Wallet</th>
                                        <th>Remark</th>
                                        <th>Date</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php while($selres = $query->fetch_assoc()){ ?>
                                      <tr>
                                          <td><?php echo $selres['id']; ?></td>
                                          <td><?php echo $selres['fname']." ".$selres['lname']; ?></td>
                                          <td><?php echo $selres['order_id']; ?></td>
                                          <td><?php echo $selres['coins_used']; ?></td>
                                          <td><?php echo $selres['req_amount']; ?></td>
                                          <td><?php echo $selres['type']; ?></td>
                                          <td><?php echo $selres['getway_name']; ?></td>
                                          <td><?php echo $selres['remark']; ?></td>
                                          <td><?php echo date('d-m-Y H:i:s', $selres['date']); ?></td>
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
      <!-- ============================================================== -->
      <!-- End Right content here -->
      <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- ./wrapper -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Spectate Video</h4>
          </div>
          <div class="modal-body">
            <iframe id="video" width="550" height="315" src="" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                "order": [[ 0, "desc" ]]
            } );
        });
        TableManageButtons.init();

    </script>
	   <script type="text/javascript">
        function open_modal(code)
        {
        
          var array = code.split('=');
        
          var substring=array[1].substring(0,11);
        
            $("#video")[0].src = "//www.youtube.com/embed/"+substring;
            $("#myModal").modal("show");
        }
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
            url: 'getData_tran.php',
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