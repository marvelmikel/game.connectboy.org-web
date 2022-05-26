<?php
include("include/security.php");
include("include/conn.php");

//$selquery = "select * from tbl_user_master where uname='$user'";
//$selres = mysqli_query($conn,$selquery);
//$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
//$userid = $selres1['user_id'];
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
$selqueryI = "select * from tbl_image where img_type=0";
$selresI = mysqli_query($conn,$selqueryI);

$selqueryR = "select * from tbl_rules where rule_type=0";
$selresR = mysqli_query($conn,$selqueryR);

$selqueryG = "select * from game_details";
$selresG = mysqli_query($conn,$selqueryG);

$selquery4 = "select * from tbl_push_notification where id=1";
$selresult4 = mysqli_query($conn,$selquery4);
$selres4 = mysqli_fetch_array($selresult4);

if(isset($_POST['btnSave']))
{

  $txtGame = mysqli_real_escape_string($conn,$_POST['txtGame']);
  $txtMtitle = mysqli_real_escape_string($conn,$_POST['txtMtitle']);
  $txtMtime = mysqli_real_escape_string($conn,$_POST['txtMtime']);
  $txtMtime = strtotime($txtMtime);
  $txtMtype = mysqli_real_escape_string($conn,$_POST['txtMtype']);
  $txtVersion = mysqli_real_escape_string($conn,$_POST['txtVersion']);
  $txtEfees = mysqli_real_escape_string($conn,$_POST['txtEfees']);
  $txtKpoints = mysqli_real_escape_string($conn,$_POST['txtKpoints']);
  $txtWprize = mysqli_real_escape_string($conn,$_POST['txtWprize']);
  $txtEtype = mysqli_real_escape_string($conn,$_POST['txtEtype']);
  $txtMap = mysqli_real_escape_string($conn,$_POST['txtMap']);
  $txtSponsoredBy = mysqli_real_escape_string($conn,$_POST['txtSponsoredBy']);
  $txtSurl = mysqli_real_escape_string($conn,$_POST['txtSurl']);
  $txtMdesc = mysqli_real_escape_string($conn,$_POST['txtMdesc']);
  $txtMrule = mysqli_real_escape_string($conn,$_POST['txtMrule']);
  $txtCimg = mysqli_real_escape_string($conn,$_POST['txtCimg']);
  // $chk =  mysqli_real_escape_string($conn,$_POST['checkPmatch']);
  $txtPoolType =  mysqli_real_escape_string($conn,$_POST['txtPoolType']);
  $txtPlatform =  mysqli_real_escape_string($conn,$_POST['txtPlatform']);
  $txtAdminShare =  mysqli_real_escape_string($conn,$_POST['txtAdminShare']);
  
  if(isset($_POST['checkPmatch']))
  {
    $chk="yes";
    $privateMcode = mt_rand(100000, 999999);
  }
  else
  {
    $chk="no";
    $privateMcode = 'NULL';
  }

  $accessToken = md5(uniqid(mt_rand()));
  /*room details*/
  $txtRoomId = mysqli_real_escape_string($conn,$_POST['txtRoomId']);
  $txtRoomPass = mysqli_real_escape_string($conn,$_POST['txtRoomPass']);
  //$txtRstatus = mysqli_real_escape_string($conn,$_POST['txtRstatus']);
  $txtRsize = mysqli_real_escape_string($conn,$_POST['txtRsize']);
  /*end room details*/
  $txtDate = date("Y-m-d H:i:s");

    $insquery = "insert into match_details (game_id, title, banner, time, platform, pool_type, admin_share, match_desc, prize_pool, per_kill, entry_fee, match_type, version, map, is_private, private_match_code, entry_type, sponsored_by, match_rules, match_status, is_cancel, created, is_del, spectate_url, access_token, level_cap, max_loses) values('{$txtGame}', '{$txtMtitle}', '{$txtCimg}', '{$txtMtime}', '{$txtPlatform}', '{$txtPoolType}', {$txtAdminShare}, '{$txtMdesc}', '{$txtWprize}', '{$txtKpoints}', '{$txtEfees}', '{$txtMtype}', '{$txtVersion}', '{$txtMap}', '{$chk}', {$privateMcode}, '{$txtEtype}', '{$txtSponsoredBy}', '{$txtMrule}', 0, '0', '$txtDate', 0, '$txtSurl', '$accessToken', 0, 0)";

    if(mysqli_query($conn,$insquery))
    {
      $matchId_last = mysqli_insert_id($conn);
      $insquery1 = "insert into room_details (id,match_id,room_id,room_pass,room_size,created) values(null,'$matchId_last','{$txtRoomId}','{$txtRoomPass}',{$txtRsize},'{$txtDate}')";
      
      if(mysqli_query($conn,$insquery1))
      {
        header("Location:match-list.php");
      }
      else
      {
        // echo $insquery1;
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

if(isset($_GET['matchId']))
{
  $matchId = $_GET['matchId'];
  
  $getquery1 = "select MD.*,RD.* from match_details as MD 
  left join room_details as RD on RD.match_id=MD.id
  where MD.id={$matchId}";
  $getresult1 = mysqli_query($conn,$getquery1);
  
}

if(isset($_POST['btnUpdate']))
{
  $txtGame = mysqli_real_escape_string($conn,$_POST['txtGame']);
  $txtMtitle = mysqli_real_escape_string($conn,$_POST['txtMtitle']);
  $txtMtime = mysqli_real_escape_string($conn,$_POST['txtMtime']);
  $txtMtime = strtotime($txtMtime);
  $txtMtype = mysqli_real_escape_string($conn,$_POST['txtMtype']);
  $txtVersion = mysqli_real_escape_string($conn,$_POST['txtVersion']);
  $txtEfees = mysqli_real_escape_string($conn,$_POST['txtEfees']);
  $txtKpoints = mysqli_real_escape_string($conn,$_POST['txtKpoints']);
  $txtWprize = mysqli_real_escape_string($conn,$_POST['txtWprize']);
  $txtEtype = mysqli_real_escape_string($conn,$_POST['txtEtype']);
  $txtMap = mysqli_real_escape_string($conn,$_POST['txtMap']);
  $txtSponsoredBy = mysqli_real_escape_string($conn,$_POST['txtSponsoredBy']);
  $txtSurl = mysqli_real_escape_string($conn,$_POST['txtSurl']);
  $txtMdesc = mysqli_real_escape_string($conn,$_POST['txtMdesc']);
  $txtMrule = mysqli_real_escape_string($conn,$_POST['txtMrule']);
  $txtCimg = mysqli_real_escape_string($conn,$_POST['txtCimg']);
  // $chk =  mysqli_real_escape_string($conn,$_POST['checkPmatch']);
  $txtPoolType =  mysqli_real_escape_string($conn,$_POST['txtPoolType']);
  $txtPlatform =  mysqli_real_escape_string($conn,$_POST['txtPlatform']);
  $txtAdminShare =  mysqli_real_escape_string($conn,$_POST['txtAdminShare']);

  if(isset($_POST['checkPmatch']))
  {
    $chk="yes";
    $privateMcode = mt_rand(100000, 999999);
  }
  else
  {
    $chk="no";
    $privateMcode = 'NULL';
  }

  /*room details*/
  $txtRoomId = mysqli_real_escape_string($conn,$_POST['txtRoomId']);
  $txtRoomPass = mysqli_real_escape_string($conn,$_POST['txtRoomPass']);
  //$txtRstatus = mysqli_real_escape_string($conn,$_POST['txtRstatus']);
  $txtRsize = mysqli_real_escape_string($conn,$_POST['txtRsize']);
  /*end room details*/

  $txtDate = date("Y-m-d H:i:s");

    $updateqry = "update match_details set game_id='{$txtGame}', title='{$txtMtitle}', banner=$txtCimg, time='{$txtMtime}', platform='$txtPlatform', pool_type='$txtPoolType', admin_share=$txtAdminShare, match_desc='$txtMdesc',  prize_pool={$txtWprize}, per_kill={$txtKpoints}, entry_fee={$txtEfees}, match_type='{$txtMtype}', version='{$txtVersion}', map='{$txtMap}', is_private='{$chk}', private_match_code=$privateMcode, entry_type='{$txtEtype}', sponsored_by='{$txtSponsoredBy}', match_rules='{$txtMrule}', spectate_url='$txtSurl' where id=$matchId";

    if(mysqli_query($conn,$updateqry))
    {
      if($txtRoomId!='' && $txtRoomPass!='')
      {
          $selEmail = "select u.username FROM participant_details as p
                      left join user_details as u on u.id=p.user_id
                      WHERE match_id=$matchId";
          $selResEmail = mysqli_query($conn,$selEmail);
          while($resMail = mysqli_fetch_array($selResEmail))
          {
              $noti_address[] = $resMail['username'];
          }
          $to_noti = implode(", ", $noti_address);
          //echo"<script>alert(\"Message Send to $to_noti\");</script>";

          // send notification to participants
          $content = array(
                           "en" => 'Room id and password are available now. Room Id: '.$txtRoomId.' | Password: '.$txtRoomPass);
          $fields = array(
                          'app_id' => $selres4['appid'],
                          //'included_segments' => array('All'),
                          'include_external_user_ids' => array($noti_address),
                          'data' => array("foo" => "bar"),
                          'headings'=> array("en" => $txtMtitle.' is live now..' ),
                          'contents' => $content
                          );

          $fields = json_encode($fields);
          // print("\nJSON sent:\n");
          // print($fields);

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                     'Authorization: Basic '.$selres4['auth_key']));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_POST, TRUE);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

          $response = curl_exec($ch);
          
          curl_close($ch);
      }
      $updateqry1 = "update room_details set room_id='{$txtRoomId}',room_pass='{$txtRoomPass}',room_size={$txtRsize} where match_id='$matchId'";

      if(mysqli_query($conn,$updateqry1))
      {
        header("Location:match-list.php");
      }
      else
      {
          //echo $updateqry;
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
        // echo $updateqry;
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

    <title>Create Match</title>

    <?php include_once("include/head-section.php"); ?>
    <script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
    
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
                      <!-- <a class="btn btn-default waves-effect waves-light" href="dashboard.php"> Return Home</a> -->

                    </div>
                  </div>

                  <?php } else { 
                    $getres1 = mysqli_fetch_array($getresult1); 
                  ?>

                  <h4 class="m-t-0 header-title"><b>Edit Match Details</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Update match details here.
                  </p>
                  <form action="match-detail.php?matchId=<?php echo $_GET['matchId'];?>" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="txtCimg">Select Game</label>
                          <select name="txtGame" class="select2a form-control" required data-placeholder="Choose ..." id="txtGame">
                          <option value="">--- Select ---</option>
                          <?php while ($selres5 = mysqli_fetch_array($selresG)){ ?>
                              <option <?php if ($getres1['game_id']==$selres5['id']) { echo "selected"; } ?> value="<?php echo $selres5['id']; ?>"><?php echo $selres5['title']; ?></option>
                          <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="txtMtitle">Match Title*</label>
                          <input type="text" value="<?php echo $getres1['title']?>" name="txtMtitle" parsley-trigger="change" required placeholder="Enter match title" class="form-control" id="txtMtitle">
                        </div>
                      </div>
                    </div>
                    <div class="row">  
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="txtPoolType">Pool-Prize type*</label>
                          <select class="form-control" name="txtPoolType" required id="txtPoolType">
                            <option value="">--Select--</option>
                            <option <?php if($getres1['pool_type']=='0') { echo 'selected'; } ?> value="0">Static</option>
                            <option <?php if($getres1['pool_type']=='1') { echo 'selected'; } ?> value="1">Dynamic</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group hidden" id="adminSharDiv">
                          <label for="txtAdminShare">Admin Share (%) *</label>
                          <input type="number" value="<?php echo $getres1['admin_share']?>" class="form-control" name="txtAdminShare" id="txtAdminShare" parsley-trigger="change">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtMdesc">Prize Pool Description*</label>
                          <textarea name="txtMdesc" parsley-trigger="change" required placeholder="Enter match description" class="form-control" id="txtMdesc"><?php echo $getres1['match_desc']?></textarea>
                          <script>
                                  CKEDITOR.replace( 'txtMdesc' );
                          </script>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtMtime">Match Time*</label>
                          <input id="txtMtime" name="txtMtime" value="<?php echo date('Y-m-d\TH:i', $getres1['time']); ?>" type="datetime-local" required class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtMtype">Match Type*</label>
                          <select name="txtMtype" class="form-control" id="txtMtype" required>
                            <option value="">--Select--</option>
                            <option <?php if($getres1['match_type']=='Solo'){ echo "selected"; } ?> value="Solo">Solo</option>
                            <option <?php if($getres1['match_type']=='Duo'){ echo "selected"; } ?> value="Duo">Duo</option>
                            <option <?php if($getres1['match_type']=='Squad'){ echo "selected"; } ?> value="Squad">Squad</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtVersion">Version*</label>
                          <select name="txtVersion" parsley-trigger="change" required class="form-control" id="txtVersion">
                            <option value="">--Select--</option>
                            <option <?php if($getres1['version']=='TPP') { echo 'selected'; } ?> value="TPP">TPP</option>
                            <option <?php if($getres1['version']=='FPP') { echo 'selected'; } ?> value="FPT">FPT</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label for="txtCimg">Cover Image*</label>
                          <!-- <input type="file" name="txtCimg" parsley-trigger="change" class="form-control" id="txtCimg"> -->
                          <select name="txtCimg" class="select2a form-control" required data-placeholder="Choose ..." id="txtCimg">
                          <option value="">--- Select ---</option>
                          <?php while ($selres4 = mysqli_fetch_array($selresI)){ ?>
                              <option <?php if ($getres1['banner']==$selres4['img_id']) { echo "selected"; } ?> value="<?php echo $selres4['img_id']; ?>"><?php echo $selres4['image_name']; ?></option>
                          <?php } ?>
                          </select>
                          <!-- <small><a href="<?php //echo $getres1['banner']?>" target="_blank">Current Cover Image</a></small> -->
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtPlatform">Platform</label>
                          <select class="form-control" name="txtPlatform" id="txtPlatform">
                            <option value="">-- Select --</option>
                            <option <?php if($getres1['platform']=='Mobile') { echo 'selected'; } ?> value="Mobile">Mobile</option>
                            <option <?php if($getres1['platform']=='Desktop') { echo 'selected'; } ?> value="Desktop">Desktop</option>
                            <option <?php if($getres1['platform']=='Lite') { echo 'selected'; } ?> value="Lite">Lite</option>
                            <option <?php if($getres1['platform']=='Playstation') { echo 'selected'; } ?> value="Playstation">Playstation</option>
                            <option <?php if($getres1['platform']=='Xbox') { echo 'selected'; } ?> value="Xbox">Xbox</option>
                          </select>                          
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtEtype">Entry Type*</label>
                          <select name="txtEtype" class="form-control" id="txtEtype" required>
                            <option value="">--Select--</option>
                            <option <?php if($getres1['entry_type']=='Free'){ echo "selected"; } ?> value="Free">Free</option>
                            <option <?php if($getres1['entry_type']=='Paid'){ echo "selected"; } ?> value="Paid">Paid</option>
                            <option <?php if($getres1['entry_type']=='Giveaway'){ echo "selected"; } ?> value="Giveaway">Giveaway</option>
                            <option <?php if($getres1['entry_type']=='Sponsored'){ echo "selected"; } ?> value="Sponsored">Sponsored</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtEfees">Entry Fee*</label>
                          <input type="number" value="<?php echo $getres1['entry_fee']?>" name="txtEfees" parsley-trigger="change" required class="form-control" id="txtEfees">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtKpoints">Points/Kill*</label>
                          <input type="number" value="<?php echo $getres1['per_kill']?>" name="txtKpoints" parsley-trigger="change" required class="form-control" id="txtKpoints">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtWprize">Total Prize Pool*</label>
                          <input type="number" value="<?php echo $getres1['prize_pool']?>" name="txtWprize" parsley-trigger="change" required class="form-control" id="txtWprize">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtMap">Map*</label>
                          <input type="text" value="<?php echo $getres1['map']?>" name="txtMap" parsley-trigger="change" required class="form-control" id="txtMap">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtSponsoredBy">Sponsored By</label>
                          <input type="text" value="<?php echo $getres1['sponsored_by']?>" name="txtSponsoredBy" parsley-trigger="change" class="form-control" id="txtSponsoredBy">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtSurl">Spectate URL</label>
                          <input type="text" value="<?php echo $getres1['spectate_url']?>" name="txtSurl" parsley-trigger="change" class="form-control" id="txtSurl">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtCimg">Match Rules</label>
                          <!-- <input type="file" name="txtCimg" parsley-trigger="change" class="form-control" id="txtCimg"> -->
                          <select name="txtMrule" class="select2a form-control" required data-placeholder="Choose ..." id="txtMrule">
                          <option value="">--- Select ---</option>
                          <?php while ($selres4 = mysqli_fetch_array($selresR)){ ?>
                              <option <?php if ($getres1['match_rules']==$selres4['rule_id']) { echo "selected"; } ?> value="<?php echo $selres4['rule_id']; ?>"><?php echo $selres4['rule_title']; ?></option>
                          <?php } ?>
                          </select>
                          <!-- <small><a href="<?php //echo $getres1['banner']?>" target="_blank">Current Cover Image</a></small> -->
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="checkbox checkbox-pink">
                            <input id="checkPmatch" <?php if($getres1['is_private']=='yes') { echo "checked"; } ?> name="checkPmatch" type="checkbox" value="yes">
                            <label for="checkPmatch"> Private Match </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <h4><u>Room Details</u></h4>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtRoomId">Room Id</label>
                          <input id="txtRoomId" value="<?php echo $getres1['room_id']?>" name="txtRoomId" type="text" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtRoomPass">Room Password</label>
                          <input type="text" value="<?php echo $getres1['room_pass']?>" name="txtRoomPass" class="form-control" id="txtRoomPass">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtRsize">Room Size*</label>
                          <input type="number" value="<?php echo $getres1['room_size']; ?>" name="txtRsize" parsley-trigger="change" required class="form-control" max="1000" min="2" id="txtRsize">
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdate" id="btnUpdate" > Update</button>
                          <!-- <a href="user-list.php" class="btn btn-default waves-effect waves-light m-l-5"> Cancel</a> -->
                          <a href="javascript:void(0);" class="btn btn-default waves-effect waves-light" onclick="history.back();"> Cancel</a>
                        </div>
                      </div>

                    </div>
                  </form>

                  <?php } ?> <!-- else part completed here (retrieving details) -->

                  <?php } else { ?>

                  <h4 class="m-t-0 header-title"><b>Create New Match</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Fill all necessary data to create new match.
                  </p>
                  <form action="match-detail.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="txtCimg">Select Game</label>
                          <select name="txtGame" class="select2a form-control" required data-placeholder="Choose ..." id="txtGame">
                          <option value="">--- Select ---</option>
                          <?php while ($selres5 = mysqli_fetch_array($selresG)){ ?>
                              <option value="<?php echo $selres5['id']; ?>"><?php echo $selres5['title']; ?></option>
                          <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="txtMtitle">Match Title*</label>
                          <input type="text" name="txtMtitle" parsley-trigger="change" required placeholder="Enter match title" class="form-control" id="txtMtitle">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="txtPoolType">Pool-Prize type*</label>
                          <select class="form-control" name="txtPoolType" id="txtPoolType" required>
                            <option value="">--Select--</option>
                            <option value="0">Static</option>
                            <option value="1">Dynamic</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group hidden" id="adminSharDiv">
                          <label for="txtAdminShare">Admin Share (%) *</label>
                          <input type="number" class="form-control" parsley-trigger="change" name="txtAdminShare" id="txtAdminShare">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtMdesc">Prize Pool Description*</label>
                          <textarea name="txtMdesc" parsley-trigger="change" required placeholder="Enter match description" class="form-control" id="txtMdesc"></textarea>
                          <script>
                                  CKEDITOR.replace( 'txtMdesc' );
                          </script>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtMtime">Match Time*</label>
                          <input id="txtMtime" name="txtMtime" type="datetime-local" required class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtMtype">Match Type*</label>
                          <select name="txtMtype" class="form-control" id="txtMtype" required>
                            <option value="">--Select--</option>
                            <option value="Solo">Solo</option>
                            <option value="Duo">Duo</option>
                            <option value="Squad">Squad</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtVersion">Version*</label>
                          <select name="txtVersion" parsley-trigger="change" required class="form-control" id="txtVersion">
                            <option value="">--Select--</option>
                            <option value="TPP">TPP</option>
                            <option value="FPT">FPP</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label for="txtCimg">Cover Image*</label>
                          <!-- <input type="file" name="txtCimg" parsley-trigger="change" required class="form-control" id="txtCimg"> -->
                          <select name="txtCimg" class="select2a form-control" data-placeholder="Choose ..." id="txtCimg">
                          <option value="">--- Select ---</option>
                          <?php while ($selres4 = mysqli_fetch_array($selresI)){ ?>
                              <option value="<?php echo $selres4['img_id']; ?>"><?php echo $selres4['image_name']; ?></option>
                          <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtPlatform">Platform</label>
                          <select class="form-control" name="txtPlatform" id="txtPlatform">
                            <option value="">-- Select --</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Desktop">Desktop</option>
                            <option value="Lite">Lite</option>
                            <option value="Playstation">Playstation</option>
                            <option value="Xbox">Xbox</option>
                          </select>                          
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtEtype">Entry Type*</label>
                          <select name="txtEtype" class="form-control" id="txtEtype" required>
                            <option value="">--Select--</option>
                            <option value="Free">Free</option>
                            <option value="Paid">Paid</option>
                            <option value="Giveaway">Giveaway</option>
                            <option value="Sponsored">Sponsored</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtEfees">Entry Fee*</label>
                          <input type="number" name="txtEfees" parsley-trigger="change" required class="form-control" id="txtEfees">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtKpoints">Points/Kill*</label>
                          <input type="number" name="txtKpoints" parsley-trigger="change" required class="form-control" id="txtKpoints">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtWprize">Total Prize Pool*</label>
                          <input type="number" name="txtWprize" required parsley-trigger="change" class="form-control" id="txtWprize">
                        </div>
                      </div>  
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtMap">Map*</label>
                          <input type="text" name="txtMap" parsley-trigger="change" required class="form-control" id="txtMap">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtSponsoredBy">Sponsored By</label>
                          <input type="text" name="txtSponsoredBy" class="form-control" id="txtSponsoredBy">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtSurl">Spectate URL</label>
                          <input type="text" name="txtSurl" class="form-control" id="txtSurl">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtCimg">Match Rules</label>
                          <!-- <input type="file" name="txtCimg" parsley-trigger="change" class="form-control" id="txtCimg"> -->
                          <select name="txtMrule" class="select2a form-control" data-placeholder="Choose ..." id="txtMrule">
                          <option value="">--- Select ---</option>
                          <?php while ($selres4 = mysqli_fetch_array($selresR)){ ?>
                              <option value="<?php echo $selres4['rule_id']; ?>"><?php echo $selres4['rule_title']; ?></option>
                          <?php } ?>
                          </select>
                          <!-- <small><a href="<?php //echo $getres1['banner']?>" target="_blank">Current Cover Image</a></small> -->
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="checkbox checkbox-pink">
                            <input id="checkPmatch" name="checkPmatch" type="checkbox" value="yes">
                            <label for="checkPmatch"> Private Match </label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <h4><u>Room Details</u></h4>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtRoomId">Room Id</label>
                          <input id="txtRoomId" name="txtRoomId" type="text" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtRoomPass">Room Password</label>
                          <input type="text" name="txtRoomPass" class="form-control" id="txtRoomPass">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="txtRsize">Room Size*</label>
                          <input type="number" name="txtRsize" parsley-trigger="change" required class="form-control" max="1000" min="2" value="100" id="txtRsize">
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnSave" id="btnSave" > Save</button>
                          <!-- <a href="user-list.php" class="btn btn-default waves-effect waves-light m-l-5"> Cancel</a> -->
                          <a href="javascript:void(0);" class="btn btn-default waves-effect waves-light" onclick="history.back();"> Cancel</a>
                        </div>
                      </div>

                    </div>
                  </form>

                  <?php } ?>

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

    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>
    <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>
        
    <script type="text/javascript">
      $(document).ready(function() {
        $('form').parsley();
      });

      $(document).ready(function() {
        $("#wizard-picture").change(function() {
          readURL(this);
        });
      });

      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      $(document).ready(function(){
          $('#txtUname').keyup(function()
          {
              $.post("check_username.php",
              { 
                txtUname : $('#txtUname').val()
              },
              function(response)
              {
                $('#usernameRes').fadeOut(); 
                setTimeout("Userresult('usernameRes','"+escape(response)+"')",350);
              });
              return false;
          });
          
      });

      function Userresult(id,response)
      {
        $('#usernameLoading').hide();
        $('#'+id).html(unescape(response));
        $('#'+id).fadeIn();
      }
      
    </script>
    <script>
    $(document).ready(function(){
        var txtPoolType = $('#txtPoolType').val();
        if (txtPoolType==1) 
        {
          $("#adminSharDiv").removeClass("hidden");
          $("#txtAdminShare").attr("required");
        }
        $('#txtPoolType').on('change', function() {
          if(this.value == 1) {
            // $('#cusDate').show();
            $("#adminSharDiv").removeClass("hidden");
            $("#txtAdminShare").attr("required");
          } else {
            $("#adminSharDiv").addClass("hidden");
            $("#txtAdminShare").val(0);
            $("#txtAdminShare").removeAttr("required");
          }
        });
        var txtEtype = $('#txtEtype').val();
        if (txtEtype == 'Free') 
        {
          $('#txtEfees').val(0);
        }
        $('#txtEtype').on('change', function() {
          if(this.value == 'Free') {
            $('#txtEfees').val(0);
            $("#txtEfees").attr("readonly");
          } else {
            $('#txtEfees').val('');
            $("#txtEfees").removeAttr("readonly");
          }
        });

        if ($('#checkBet').is(":checked")) {
            $("#betDetails").show();
            $("#txtBetTitle").removeAttr("disabled");
            $("#txtBetImg").removeAttr("disabled");
            $("#txtBetRules").removeAttr("disabled");
            $("#txtBetFee").removeAttr("disabled");
            $("#txtBetWin").removeAttr("disabled");
            $("#txtBetSize").removeAttr("disabled");
            $("#txtBetTitle").attr("required","required");
            $("#txtBetImg").attr("required","required");
            $("#txtBetRules").attr("required","required");
            $("#txtBetFee").attr("required","required");
            $("#txtBetWin").attr("required","required");
            $("#txtBetSize").attr("required","required");
        } else {
            $("#betDetails").hide();
            $("#txtBetTitle").attr("disabled","disabled");
            $("#txtBetImg").attr("disabled","disabled");
            $("#txtBetRules").attr("disabled","disabled");
            $("#txtBetFee").attr("disabled","disabled");
            $("#txtBetWin").attr("disabled","disabled");
            $("#txtBetSize").attr("disabled","disabled");
            $("#txtBetTitle").removeAttr("required");
            $("#txtBetImg").removeAttr("required");
            $("#txtBetRules").removeAttr("required");
            $("#txtBetFee").removeAttr("required");
            $("#txtBetWin").removeAttr("required");
            $("#txtBetSize").removeAttr("required");
        }
    });

    $(function () {
        $("#checkBet").click(function () {
            if ($(this).is(":checked")) {
                $("#betDetails").show();
                $("#txtBetTitle").removeAttr("disabled");
                $("#txtBetImg").removeAttr("disabled");
                $("#txtBetRules").removeAttr("disabled");
                $("#txtBetFee").removeAttr("disabled");
                $("#txtBetWin").removeAttr("disabled");
                $("#txtBetSize").removeAttr("disabled");
                $("#txtBetTitle").attr("required","required");
                $("#txtBetImg").attr("required","required");
                $("#txtBetRules").attr("required","required");
                $("#txtBetFee").attr("required","required");
                $("#txtBetWin").attr("required","required");
                $("#txtBetSize").attr("required","required");
            } else {
                $("#betDetails").hide();
                $("#txtBetTitle").attr("disabled","disabled");
                $("#txtBetImg").attr("disabled","disabled");
                $("#txtBetRules").attr("disabled","disabled");
                $("#txtBetFee").attr("disabled","disabled");
                $("#txtBetWin").attr("disabled","disabled");
                $("#txtBetSize").attr("disabled","disabled");
                $("#txtBetTitle").removeAttr("required");
                $("#txtBetImg").removeAttr("required");
                $("#txtBetRules").removeAttr("required");
                $("#txtBetFee").removeAttr("required");
                $("#txtBetWin").removeAttr("required");
                $("#txtBetSize").removeAttr("required");
            }
        });
    });
    </script>
  </body>
</html>