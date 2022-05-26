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
$selquery = "select * from match_details where match_status=2 order by id";
$selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['matchId']))
{
  $matchId = $_GET['matchId'];
  $selquery1 = "select p.*, u.fname, u.lname, u.email, u.mobile from participant_details as p
  left join user_details as u on u.id=p.user_id
  where match_id={$matchId} order by id desc";
  $getresult1 = mysqli_query($conn,$selquery1);
  
  $selquery1 = "select m.*, r.total_joined from match_details as m
  left join room_details as r on r.match_id=$matchId
  where m.id={$matchId}";
  $getresult4 = mysqli_query($conn,$selquery1);
  $getres4 = mysqli_fetch_array($getresult4);

  /*disable button if winner calculation*/
  
  // $selquery12 = "select * from participant_details where match_id={$matchId} and win=10";
  // $getresult41 = mysqli_query($conn,$selquery12);
  // $getres41 = mysqli_fetch_array($getresult41);
  // $selres41 = mysqli_num_rows($getresult41);

  /*end disable button if winner calculation*/

  if(isset($_POST['btnAddNote']))
  {
    $txtNote = mysqli_real_escape_string($conn,$_POST['txtNote']);

      $upquery4 = "update match_details set match_notes='$txtNote' where id={$matchId}";
      if(mysqli_query($conn,$upquery4))
      {
        header("Location:create-result.php?matchId=".$matchId);
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
  
  

/* new logic prize pool */

if(isset($_POST['btnSubmit1'])){

    $txtUserId = $_POST['txtUserId'];
    //echo"<script>alert(\"$txtUserId\");</script>";
    $txtPartiId = $_POST['txtPartiId'];

    $txtTotKill = $_POST['txtTotKill'];
    $txtWprize = $_POST['txtWprize'];

    $txtPosition = $_POST['txtPosition'];

    /*result announced*/
    $upResAnnounced = "update match_details set match_status=3 where id={$matchId}";
    mysqli_query($conn,$upResAnnounced);
    /*result announced end*/
    
    if($_POST['txtPdistri'])
    {
      $txtPdistri = 1;
    }
    else
    {
      $txtPdistri = 0; 
    }

    $finalUserId1 = array_unique($txtUserId);
    //echo"<script>alert(\"$finalUserId\");</script>";
    // $finalUserId = array(183,184,185);
    // foreach ($finalUserId as $val) {
    //   echo"<script>alert(\"$val\");</script>";
    // }
    $finalUserId = array_values($finalUserId1);

    if($getres4['prize_dstrbn_other_mtch']==1)
    {
      for($i=0;$i<count($finalUserId);$i++)
        {
            $u_id = $finalUserId[$i];
            
            $getPartiDetUsr = mysqli_query($conn,"SELECT user_id, sum(prize) FROM participant_details WHERE match_id=".$matchId." and user_id=".$u_id);

            if($getPartiDetUsr)
            {
              $getPartiresU = mysqli_fetch_array($getPartiDetUsr);
              $resUserUpUser = $getPartiresU['0'];
              $resPrizeUpUser = $getPartiresU['1'];

                // deduct from user table
                mysqli_query($conn,"update user_details set cur_balance=cur_balance-$resPrizeUpUser, won_balance=won_balance-$resPrizeUpUser WHERE id=".$resUserUpUser);
                
            }
        }
    }
    else
    {
      for($i=0;$i<count($finalUserId);$i++)
        {
            $u_id = $finalUserId[$i];
            
            $getPartiDetUsr = mysqli_query($conn,"SELECT user_id, sum(prize) FROM participant_details WHERE match_id=".$matchId." and user_id=".$u_id);

            if($getPartiDetUsr)
            {
              $getPartiresU = mysqli_fetch_array($getPartiDetUsr);
              $resUserUpUser = $getPartiresU['0'];
              $resPrizeUpUser = $getPartiresU['1'];

                // deduct from user table
                mysqli_query($conn,"update user_details set cur_balance=cur_balance-$resPrizeUpUser WHERE id=".$resUserUpUser);
                
            }
        } 
    }  


    if($txtPdistri==1)
    {
      for($i=0;$i<count($txtPartiId);$i++)
        {
            $parti_id = $txtPartiId[$i];
            $u_id = $txtUserId[$i];
            $totWprz = ($txtTotKill[$i] * $getres4['per_kill']) + $txtWprize[$i];
            
            $getPartiDet = mysqli_query($conn,"select kills, prize from participant_details WHERE id=".$parti_id);
            if($getPartiDet)
            {
              $getPartires = mysqli_fetch_array($getPartiDet);
              // $resUserUp = $getPartires['user_id'];
              $resKillsUp = $getPartires['kills'];
              $resPrizeUp = $getPartires['prize'];

              if(mysqli_query($conn,"update participant_details set kills=kills-$resKillsUp, prize=prize-$resPrizeUp WHERE id=".$parti_id))
              {
                  mysqli_query($conn,"update participant_details set kills = kills + $txtTotKill[$i], prize = prize + $totWprz, position=$txtPosition[$i] WHERE id=".$parti_id);
                  // add in user table
                  mysqli_query($conn,"update user_details set cur_balance = cur_balance + $totWprz, won_balance = won_balance + $totWprz WHERE id=".$u_id);
                  
              }
            }
            else
            {
              //echo"<script>alert(\"$getPartiDet\");</script>";
              echo"<script>alert(\"Something went wrong\");</script>";
            }
        }
    }
    else
    {
      for($i=0;$i<count($txtPartiId);$i++)
        {
            $parti_id = $txtPartiId[$i];
            $u_id = $txtUserId[$i];
            $totWprz = ($txtTotKill[$i] * $getres4['per_kill']) + $txtWprize[$i];
            
            $getPartiDet = mysqli_query($conn,"select kills, prize from participant_details WHERE id=".$parti_id);
            if($getPartiDet)
            {
              $getPartires = mysqli_fetch_array($getPartiDet);
              // $resUserUp = $getPartires['user_id'];
              $resKillsUp = $getPartires['kills'];
              $resPrizeUp = $getPartires['prize'];

              if(mysqli_query($conn,"update participant_details set kills=kills-$resKillsUp, prize=prize-$resPrizeUp WHERE id=".$parti_id))
              {
                  mysqli_query($conn,"update participant_details set kills = kills + $txtTotKill[$i], prize = prize + $totWprz, position=$txtPosition[$i] WHERE id=".$parti_id);
                  // add in user table
                  mysqli_query($conn,"update user_details set cur_balance = cur_balance + $totWprz WHERE id=".$u_id);
              }
            }
            else
            {
              //echo"<script>alert(\"$getPartiDet\");</script>";
              echo"<script>alert(\"Something went wrong\");</script>";
            }
        }
      mysqli_query($conn,"update match_details set prize_dstrbn_other_mtch = $txtPdistri WHERE id=".$matchId);   
    }  

    header("Location:create-result.php?matchId=".$matchId);
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

    <title>Create Result</title>

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
    <link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
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
    <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>
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

            <section>
                <?php if(isset($_GET['matchId'])) { ?>

                <?php 
                
                $selres4 = mysqli_num_rows($getresult1);
                if ($selres4 == 0) {
              
                  //echo"<script>alert(\"You have entered a wrong url\");</script>";
                  
                ?>
                <div class="wrapper-page">
                  <div class="ex-page-content text-center">
                    <div class="text-error">
                      <span class="text-primary">4</span><i class="ti-face-sad text-pink"></i><span class="text-info">4</span>
                    </div>
                    <h2>Whoo0ps! Page not found</h2>
                    <br>
                    <p class="text-muted">
                      This page cannot found or is missing.
                    </p>
                    <p class="text-muted">
                      Use the navigation above or the button below to get back and track.
                    </p>
                    <br>
                    <a class="btn btn-default waves-effect waves-light" href="index.php"> Return Home</a>

                  </div>
                </div>

            </section>
            <?php } else { ?>
            <section>
              <!-- Page Content -->
              <div class="row">
                  <div class="col-sm-12">
                      <div class="card-box table-responsive">
                          <div class="row">
                              <div class="col-sm-10">
                                  <h4 class="m-t-0 header-title"><b><?php echo $getres4['title']; ?></b></h4>
                                  <p class="text-muted font-13">
                                      Add score to particular participant, generate result and prize.
                                  </p>
                              </div>
                              <div class="col-sm-2">
                                  <div class="m-t-0 text-right">
                                    <p><a href="javascript:void(0)" data-toggle="modal" data-target="#myModal2P"><i class="fa fa-info-circle"></i></a> Overall Prize Pool: 
                                      <?php 
                                        if($getres4['pool_type']=='1' and $getres4['entry_type']=='Paid')
                                          {
                                            $uShare = 100 - $getres4['admin_share'];
                                            $totprize_pool = $getres4['entry_fee'] * $getres4['total_joined'] * $uShare / 100;

                                            if($totprize_pool > $getres4['prize_pool'])
                                            {
                                                echo $totprize_pool;
                                            }
                                            else
                                            {
                                                echo $getres4['prize_pool'];
                                            }

                                          }
                                          else
                                          {
                                            echo $getres4['prize_pool'];
                                          }

                                      ?>
                                    </p>
                                    <p>Per Kill Amount: <?php echo $getres4['per_kill']; ?></p>    
                                  </div>
                              </div>
                              <!-- <div class="col-sm-2">
                                  <div class="m-t-0 text-right">
                                      <a href="match-detail.php" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                  </div>
                              </div> -->
                          </div>
                          <hr>
                          <form name=form1 method="post" action="create-result.php?matchId=<?php echo $_GET['matchId'];?>">
                          <div class="row">
                            <div class="col-md-6">
                              
                              <button class="btn btn-primary" name="btnSubmit1" type="submit">Submit Winner</button>

                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal2A">Add Note</a>

                              Prize added to both deposit and winning
                              <input type="checkbox" <?php if($getres4['prize_dstrbn_other_mtch']==1) { echo 'checked'; } ?> data-plugin="switchery" name="txtPdistri" data-color="#81c868"/>
                            </div>
                            <div class="col-md-6">
                                  <input type="text" id="myInput" placeholder="Search.." class="form-control">
                                </div>
                          </div><br>
                          
                          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                    <th>Slot</th>
                                    <th>Name</th>
                                    <th>Pubg Id</th>
                                    <th>Total Kill</th>
                                    <th>Pool Prize</th>
                                    <th>Position</th>
                                    <th>Winning Amt</th>
                                </tr>
                              </thead>
                              <tbody>

                                <?php while ($selres = mysqli_fetch_array($getresult1)){ ?>
                                  <tr>
                                      <input type="hidden" value="<?php echo $selres['user_id']; ?>" name="txtUserId[]">
                                      <input type="hidden" value="<?php echo $selres['id']; ?>" name="txtPartiId[]">
                                      <td><?php echo 'slot #'.$selres['slot']; ?></td>
                                      <td><span data-toggle="tooltip" data-html="true" title="<h4 style='color:#fff;'>Other Details :</h4><ul style='text-align:left; padding-left:0px; list-style:none;'><li><i class='fa fa-user'></i> <?php echo $selres['fname']." ".$selres['lname']; ?></li><li><i class='fa fa-envelope'></i> <?php echo $selres['email']; ?></li><li><i class='fa fa-phone'></i> <?php echo $selres['mobile']; ?></li></ul>" ><?php echo $selres['name']; ?></span></td>
                                      <!-- <td><?php //echo $selres['user_id']; ?></td> -->
                                      <td><?php echo $selres['pubg_id']; ?></td>
                                      <!-- <td><?php //echo $selres['access_key']; ?></td> -->
                                      <td>
                                        <input type="number" name="txtTotKill[]" value="<?php echo $selres['kills']; ?>" class="form-control" style="width: 100px;">
                                      </td>
                                      
                                      <td>
                                        <input type="number" name="txtWprize[]" value="<?php echo $selres['prize']-($selres['kills']*$getres4['per_kill']); ?>" class="form-control" style="width: 100px;">
                                      </td>

                                      <td>
                                        <select class="form-control" name="txtPosition[]">
                                          <option value="0">-- Select --</option>
                                          <option value="1" <?php if($selres['position']==1) { echo "selected";} ?> >Winner</option>
                                          <option value="2" <?php if($selres['position']==2) { echo "selected";} ?> >1st Runner Up</option>
                                          <option value="3" <?php if($selres['position']==3) { echo "selected";} ?> >2nd Runner Up</option>
                                          <option value="4" <?php if($selres['position']==4) { echo "selected";} ?> >3rd Runner Up</option>
                                          <option value="5" <?php if($selres['position']==5) { echo "selected";} ?> >4th Runner Up</option>
                                          <option value="6" <?php if($selres['position']==6) { echo "selected";} ?> >5th Runner Up</option>
                                          <option value="7" <?php if($selres['position']==7) { echo "selected";} ?> >6th Runner Up</option>
                                          <option value="8" <?php if($selres['position']==8) { echo "selected";} ?> >7th Runner Up</option>
                                          <option value="9" <?php if($selres['position']==9) { echo "selected";} ?> >8th Runner Up</option>
                                          <option value="10" <?php if($selres['position']==10) { echo "selected";} ?> >9th Runner Up</option>
                                        </select>
                                      </td>

                                      <td><?php echo $selres['prize']; ?></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                          </table>
                          </form>
                      </div>
                  </div>
              </div>
              <!-- /Page Content -->
            </section>
            <!-- Modal -->
            <div class="modal right fade" id="myModal2P" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">

                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel2">Prize Pool Details</h4>
                    </div>

                    <div class="modal-body">
                      <p><?php echo $getres4['match_desc']; ?></p>    
                    </div>

                </div><!-- modal-content -->
              </div><!-- modal-dialog -->
            </div><!-- modal -->
            <?php } } ?>
          </div> <!-- container -->
                               
        </div> <!-- content -->

        <?php include_once("include/footer.php"); ?>

      </div>

      <!-- Modal -->
        <div class="modal right fade" id="myModal2A" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Add Note</h4>
              </div>

              <div class="modal-body">
                <form role="form" action="create-result.php?matchId=<?php echo $_GET['matchId'];?>" method="post" data-parsley-validate novalidate>
                      <div class="form-group">
                          <label for="txtNote">Note (Any notice to users of this match)</label>
                          <textarea class="form-control" id="txtNote" name="txtNote"><?php echo $getres4['matchNotes']; ?></textarea>
                      </div>
                      <button type="submit" name="btnAddNote" class="btn btn-default waves-effect waves-light">Save</button>
                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                </form>    
              </div>

          </div><!-- modal-content -->
        </div><!-- modal-dialog -->
      </div><!-- modal -->
      <!-- ============================================================== -->
      <!-- End Right content here -->
      <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Modal -->
    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel2">Update Information</h4>
          </div>

          <div class="modal-body">
              <form action="create-result.php?matchId=<?php echo $_GET['matchId'];?>" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="hidden" name="txtUserId1" id="txtUserId1" value=""/>
                      <input type="text" name="recordId" id="recordId" hidden value=""/>
                      <label for="txtTkills">Total Kills *</label>
                      <input type="number" max="100" name="txtTkills" placeholder="Enter Number of Kills" class="form-control" id="txtTkills">
                      <!-- for update -->
                      <input type="hidden" name="txtUserId1U" id="txtUserId1U">
                      <input type="hidden" name="recordIdU" id="recordIdU">
                      <input type="hidden" name="txtTkillsU" id="txtTkillsU">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group text-right m-b-0">
                      <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdateKill"> Update</button>
                    </div>
                  </div>
                </div>  
              </form>
          </div>

        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <?php include_once("include/common_js.php"); ?>

    <script src="assets/plugins/switchery/js/switchery.min.js"></script>
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
      $(document).ready(function(){
          $("#btnSubmit").click(function(){
            if ($('input:checkbox').filter(':checked').length < 1){
                alert("Select at least one winner before submitting result!");
               return false;
            }
          });
      });

      <?php if($getres4['matchType']=='Solo')
        {
          $selLimit=1;
        }
        elseif($getres4['matchType']=='Duo') 
        {
          $selLimit=2;
        }
        elseif($getres4['matchType']=='Squad') 
        {
          $selLimit=4;
        }
        else
        {
          $selLimit=1;
        }
      ?>
      var limit = <?php echo $selLimit; ?>;
      $('input[type=checkbox]').on('change', function (e) {
          if ($('input[type=checkbox]:checked').length > limit) {
              $(this).prop('checked', false);
              if (limit==1) {
                alert("Match type is SOLO, There is only 1 winner. ");
              }
              else if (limit==2) {
                alert("Match type is DUO, There are maximum 2 winner. ");
              }
              else if (limit==4) {
                alert("Match type is SQUAD, There are maximum 4 winner. ");
              }
              else{
                alert("Select at least One Winner. ");
              }
          }
      });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({keys: true});
            // $('#datatable-responsive').DataTable();
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
                "order": [[ 0, "desc" ]],
                paging: false, 
                searching: false
            });
        });
        TableManageButtons.init();

    </script>
    <script>
    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#datatable-responsive tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
    </script>
     <script type="text/javascript">
        $(document).on("click", ".updateKill", function () {
             var myrecordId = $(this).data('id');
             var myrecordKills = $(this).data('kills');
             var myUserId = $(this).data('userid1');
             $(".modal-body #recordId").val( myrecordId );
             $(".modal-body #txtTkills").val( myrecordKills );
             $(".modal-body #txtUserId1").val( myUserId );
             /*for update*/
             $(".modal-body #recordIdU").val( myrecordId );
             $(".modal-body #txtTkillsU").val( myrecordKills );
             $(".modal-body #txtUserId1U").val( myUserId );
             // As pointed out in comments, 
             // it is unnecessary to have to manually call the modal.
             // $('#addBookDialog').modal('show');
        });
    </script>
  </body>
</html>