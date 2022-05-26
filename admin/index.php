<?php 
include("include/security.php");
include("include/conn.php");

if(isset($_POST['btnChngPass']))
{

  $opass=$_POST['oldpass'];
  $oldpass=sha1($opass);

  //$oldpass1 =$_POST['oldpass'];

//  $oldpass=sha1($oldpass1);
 // $opass = sha1($oldpass);

  $password2 = $_POST['password2'];
//  $npass = sha1($password2);
  $enpassnew = sha1($password2);
  $pass = "select password from tbl_user_master where uname='{$user}'";
  $passresult = mysqli_query($conn,$pass);
  $passres = mysqli_fetch_array($passresult);
  $password = $passres['0'];
  
  if($oldpass==$password)
  {
      $chngquery = "update tbl_user_master set password='{$enpassnew}' where uname='{$user}' and password='{$oldpass}'";

      if(mysqli_query($conn,$chngquery))
      {
        header("Location:logout.php");
      }
      else
      {
        //echo $chngquery;
        echo"<script>alert(\"Something went wrong\");</script>";
      }
  }
  else
  {
      //echo "Password is Incorrect";
      echo"<script>alert(\"Password is Incorrect\");</script>";
      //header("Location:index");
  }
  
}

$getqueryUser = "select count(id) from user_details";
$getresultuser = mysqli_query($conn,$getqueryUser);
$getresuser = mysqli_fetch_array($getresultuser);

$getqueryUserA = "select count(user_id) from tbl_user_master";
$getresultuserA = mysqli_query($conn,$getqueryUserA);
$getresuserA = mysqli_fetch_array($getresultuserA);

$getqueryUserW = "select count(user_id) from transaction_details where type=0";
$getresultuserW = mysqli_query($conn,$getqueryUserW);
$getresuserW = mysqli_fetch_array($getresultuserW);

$getquery42 = "select count(id) from match_details";
$getresult42 = mysqli_query($conn,$getquery42);
$getres42 = mysqli_fetch_array($getresult42);

$getquery43 = "select count(id) from match_details where match_status='1'";
$getresult43 = mysqli_query($conn,$getquery43);
$getres43 = mysqli_fetch_array($getresult43);

$getquery44 = "select count(id) from match_details where match_status='0'";
$getresult44 = mysqli_query($conn,$getquery44);
$getres44 = mysqli_fetch_array($getresult44);

$getquery45 = "select count(id) from match_details where match_status in (2,3)";
$getresult45 = mysqli_query($conn,$getquery45);
$getres45 = mysqli_fetch_array($getresult45);

$sumTotIncome = "select sum(entry_fee) as totIn from match_details where is_cancel='0'";
$getresSumIn = mysqli_query($conn,$sumTotIncome);
$resSumInc = mysqli_fetch_array($getresSumIn);

$sumTotex = "select sum(p.prize) as totEx from participant_details as p 
left join match_details m on m.id=p.match_id 
where m.is_cancel='0'";
$getresSumEx = mysqli_query($conn,$sumTotex);
$resSumEx = mysqli_fetch_array($getresSumEx);

$gamewisecounter = "select count(m.id) as gcount, g.title, g.id from game_details as g
left join match_details as m on g.id=m.game_id
GROUP by g.id";
$getresgconter = mysqli_query($conn,$gamewisecounter);

$BarChart = "SELECT MONTHNAME(date) as m, count(case when status in (1,2) THEN coins END) AS total, count(case when status = 1 THEN coins else 0 END) AS total_success, count(case when status = 2 THEN coins else 0 END) AS total_reject from transaction_details where type='0' GROUP by m LIMIT 12";
$QryBarChart = mysqli_query($conn,$BarChart);

$userQry = "select fname, lname, email, mobile, created_date from user_details order by id desc limit 5";
$getresUqry = mysqli_query($conn,$userQry);

$selqueryWlst = "select t.coins_used, t.date, u.fname, u.lname, u.mobile from transaction_details as t
left join user_details as u on u.id=t.user_id
where t.type=0
order by t.id desc limit 5";
$selresultwlst = mysqli_query($conn,$selqueryWlst);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title></title>
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="assets/plugins/morris/morris.css">

        <?php include_once("include/head-section.php"); ?>

    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <?php include_once("include/navbar.php"); ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- <div class="btn-group pull-right m-t-15">
                                    <button type="button" class="btn btn-default dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                                    <ul class="dropdown-menu drop-menu-right" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div> -->

                                <h4 class="page-title">Dashboard</h4>
                                <p class="text-muted page-title-alt">Welcome to <?php echo $selres4Nav['app_name']; ?> admin panel !</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card-box">
                                    <div class="bar-widget">
                                        <div class="table-box">
                                            <div class="table-detail">
                                                <div class="iconbox bg-info">
                                                    <i class="icon-layers"></i>
                                                </div>
                                            </div>

                                            <div class="table-detail">
                                               <h4 class="m-t-0 m-b-5"><b><?php echo $getresuser['0']; ?></b></h4>
                                               <p class="text-muted m-b-0 m-t-0">Total Reg User</p>
                                            </div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-bar" data-colors="#34d3eb,#ebeff2" data-width="120" data-height="45">5,3,9,6,5,9,7,3,5,2,9,7,2,1</span>
                                            </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card-box">
                                    <div class="bar-widget">
                                        <div class="table-box">
                                            <div class="table-detail">
                                                <div class="iconbox bg-custom">
                                                    <i class="icon-layers"></i>
                                                </div>
                                            </div>

                                            <div class="table-detail">
                                               <h4 class="m-t-0 m-b-5"><b><?php echo $getresuserA['0']; ?></b></h4>
                                               <p class="text-muted m-b-0 m-t-0">Total Admin User</p>
                                            </div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-pie" data-colors="#5fbeaa,#ebeff2" data-width="50" data-height="45">1/5</span>
                                            </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card-box">
                                    <div class="bar-widget">
                                        <div class="table-box">
                                            <div class="table-detail">
                                                <div class="iconbox bg-warning">
                                                    <i class="icon-layers"></i>
                                                </div>
                                            </div>

                                            <div class="table-detail">
                                               <h4 class="m-t-0 m-b-5"><b><?php echo $getresuserW['0']; ?></b></h4>
                                               <p class="text-muted m-b-0 m-t-0">Total Withdraw Req.</p>
                                            </div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-pie" data-colors="#5fbeaa,#ebeff2" data-width="50" data-height="45">1/5</span>
                                            </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card-box">
                                    <h4 class="text-dark header-title m-t-0 m-b-30">Tournament Summery</h4>

                                    <div class="widget-chart text-center">
                                        <div id="sparkline3"></div>
                                        <h5 class="text-muted m-t-20">Total Match</h5>
                                        <h2 class="font-600"><?php echo $getres42['0']; ?></h2>
                                        <ul class="list-inline m-t-15">
                                            <li>
                                                <h5 class="text-muted m-t-20">Ongoing</h5>
                                                <h4 class="m-b-0"><?php echo $getres43['0']; ?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">Upcoming</h5>
                                                <h4 class="m-b-0"><?php echo $getres44['0']; ?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">Completed</h5>
                                                <h4 class="m-b-0"><?php echo $getres45['0']; ?></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card-box" style="height: 430px; overflow: auto;">
                                    <h4 class="text-dark header-title m-t-0 m-b-30">Total Revenue</h4>

                                    <div class="widget-chart text-center">
                                        <div id="sparkline4"></div>
                                        <?php $netRes = $resSumInc['totIn']-$resSumEx['totEx']; ?>
                                        <h5 class="text-muted m-t-20"><?php if($netRes>=1) { ?>Net Profit<?php } else { ?>Loss<?php } ?></h5>
                                        <h2 class="font-600"><?php echo abs($netRes); ?></h2>
                                        <ul class="list-inline m-t-15">
                                            <li>
                                                <h5 class="text-muted m-t-20">Total Expense</h5>
                                                <h4 class="m-b-0"><?php echo $resSumEx['totEx']; ?></h4>
                                            </li>
                                            <li>
                                                <h5 class="text-muted m-t-20">Total Income</h5>
                                                <h4 class="m-b-0"><?php echo $resSumInc['totIn']; ?></h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card-box" style="height: 430px; overflow: auto;">
                                    <h4 class="text-dark header-title m-t-0 m-b-30">Match Counter</h4>
                                    <?php $progColorArray = array("progress-bar-primary", "progress-bar-pink", "progress-bar-info", "progress-bar-warning", "progress-bar-success"); 
                                        $i=0;
                                        $arrsize = count($progColorArray);
                                    ?>
                                    <?php while($resgcounter = mysqli_fetch_array($getresgconter)) { 
                                        if($i > $arrsize - 1) $i = 0;
                                    ?>
                                        <p class="font-600"><?php echo $resgcounter['title']; ?> <span class="text-primary pull-right"><?php echo $resgcounter['gcount']; ?></span></p>
                                        <div class="progress m-b-30">
                                          <div class="progress-bar <?php echo $progColorArray[$i]; ?> progress-animated wow animated" role="progressbar" aria-valuenow="<?php echo $resgcounter['gcount']*100/$getres42['0']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $resgcounter['gcount']*100/$getres42['0']; ?>%">
                                          </div><!-- /.progress-bar .progress-bar-danger -->
                                        </div><!-- /.progress .no-rounded -->
                                    <?php $i++; } ?>
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <a href="reg-user-list" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>
                                    <h4 class="text-dark header-title m-t-0">New Registration</h4>
                                    <p class="text-muted m-b-30 font-13">
                                        Mange register user here. 
                                    </p>

                                    <div class="table-responsive">
                                        <table class="table table-actions-bar m-b-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($resQuser = mysqli_fetch_array($getresUqry)) { ?>
                                                <tr>
                                                    <td><?php echo $resQuser['fname'].' '.$resQuser['lname']; ?></td>
                                                    <td><?php echo $resQuser['mobile']; ?></td>
                                                    <td><?php echo $resQuser['email']; ?></td>
                                                    <td><?php echo $resQuser['created_date']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card-box">
                                    <a href="withdrawal-list" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>
                                    <h4 class="text-dark header-title m-t-0">Recent Redeem Request</h4>
                                    <p class="text-muted m-b-30 font-13">
                                        Mange withdrawal request here. 
                                    </p>

                                    <div class="table-responsive">
                                        <table class="table table-actions-bar m-b-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Mobile</th>
                                                    <th>Coin</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($resQuser = mysqli_fetch_array($selresultwlst)) { ?>
                                                <tr>
                                                    <td><?php echo $resQuser['fname'].' '.$resQuser['lname']; ?></td>
                                                    <td><?php echo $resQuser['mobile']; ?></td>
                                                    <td><?php echo $resQuser['coins_used']; ?></td>
                                                    <td><?php echo date('d-m-Y H:i:s', $resQuser['date']); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <?php include_once("include/footer.php"); ?>

            </div>

        </div>
        <!-- END wrapper -->


    
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <?php include_once("include/common_js.php"); ?>

        <script src="assets/plugins/peity/jquery.peity.min.js"></script>

        <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
                
        <!-- <script src="assets/pages/jquery.dashboard_3.js"></script> -->
        <script type="text/javascript">
            /**
            * Theme: Ubold Admin Template
            * Author: Coderthemes
            * Component: Widget
            * 
            */
            ongoing = '<?php echo $getres43['0']; ?>';
            upcoming = '<?php echo $getres44['0']; ?>';
            completed = '<?php echo $getres45['0']; ?>';

            totEx = '<?php echo $resSumEx['totEx']; ?>';
            totIn = '<?php echo $resSumInc['totIn']; ?>';
            netProfit = totIn - totEx;

            $( document ).ready(function() {
                
                var DrawSparkline = function() {
                    
                    /*$('#sparkline3').sparkline([20, 40, 30, 10], {
                        type: 'pie',
                        width: '165',
                        height: '165',
                        sliceColors: ['#dcdcdc', '#5d9cec', '#36404a', '#5fbeaa']
                    });*/
                    
                    $('#sparkline3').sparkline([ongoing, upcoming, completed], {
                        type: 'pie',
                        width: '165',
                        height: '165',
                        sliceColors: ['#dcdcdc', '#5d9cec', '#36404a']
                    });
                    
                    $('#sparkline4').sparkline([totEx, totIn], {
                        type: 'pie',
                        width: '165',
                        height: '165',
                        sliceColors: ['#9AE9F5', '#5FBEAA']
                    });
                    
                };

                
                DrawSparkline();
                
                var resizeChart;

                $(window).resize(function(e) {
                    clearTimeout(resizeChart);
                    resizeChart = setTimeout(function() {
                        DrawSparkline();
                    }, 300);
                });
            });
        </script>
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>       

        <!--Morris Chart-->
        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>
        <!-- <script src="assets/pages/morris.init.js"></script> -->
        <script type="text/javascript">
            
/**
* Theme: Ubold Admin Template
* Author: Coderthemes
* Morris Chart
*/

!function($) {
    "use strict";

    var MorrisCharts = function() {};

    //creates area chart
    MorrisCharts.prototype.createAreaChart = function(element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
        Morris.Area({
            element: element,
            pointSize: 0,
            lineWidth: 0,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            hideHover: 'auto',
            resize: true,
            gridLineColor: '#eef0f2',
            lineColors: lineColors
        });
    },
    MorrisCharts.prototype.init = function() {

        //creating area chart
        var $areaData = [
                { y: '2009', a: 10 },
                { y: '2010', a: 75 },
                { y: '2011', a: 50 },
                { y: '2012', a: 75 },
                { y: '2013', a: 50 },
                { y: '2014', a: 75 },
                { y: '2015', a: 90 }
            ];
        this.createAreaChart('morris-area-example', 0, 0, $areaData, 'y', ['a'], ['Series A'], ['#5fbeaa']);

        
    },
    //init
    $.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.MorrisCharts.init();
}(window.jQuery);
        </script>
    </body>
</html>