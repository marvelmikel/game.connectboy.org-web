<?php 
if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.class.php'; 
     
    // Include database configuration file 
    require_once 'include/conn.php'; 
     
    // Set some useful configuration 
    $baseURL = 'getData.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 10; 
     
    // Set conditions for search 
    $whereSQL = $orderSQL = ''; 
    if(!empty($_POST['keywords'])){ 
        $whereSQL = "WHERE fname LIKE '%".$_POST['keywords']."%' or lname LIKE '%".$_POST['keywords']."%' or username LIKE '%".$_POST['keywords']."%' or email LIKE '%".$_POST['keywords']."%' or mobile LIKE '%".$_POST['keywords']."%' or id LIKE '%".$_POST['keywords']."%'"; 
    } 
    if(!empty($_POST['sortBy'])){ 
        $orderSQL = " ORDER BY fname ".$_POST['sortBy']; 
    }else{ 
        $orderSQL = " ORDER BY id DESC "; 
    } 
     
    // Count of all records 
    $query   = $conn->query("SELECT COUNT(*) as rowNum FROM user_details ".$whereSQL.$orderSQL); 
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
     
    // Initialize pagination class 
    $pagConfig = array( 
        'baseURL' => $baseURL, 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'postContent', 
        'link_func' => 'searchFilter' 
    ); 
    $pagination =  new Pagination($pagConfig); 
 
    // Fetch records based on the offset and limit 
    $query = $conn->query("SELECT * FROM user_details $whereSQL $orderSQL LIMIT $offset,$limit"); 
     
    if($query->num_rows > 0){ 
    ?> 
    <div class="table-responsive">
      <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
          <tr>
              <th>Full Name</th>
              <th>User Name</th>
              <th>User ID</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Tot Bal</th>
              <th>Won Bal</th>
              <th>Bonus Bal</th>
              <th>Status</th>
              <th>Block</th>
              <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Display posts list -->
          <div class="post-list">
          <?php while($selres = $query->fetch_assoc()){ ?>
              <tr>
                  <td><?php echo $selres['fname']." ".$selres['lname']; ?></td>
                  <td><?php echo $selres['username']; ?></td>
                  <td><?php echo $selres['id']; ?></td>
                  <td><?php echo $selres['email']; ?></td>
                  <td><?php echo $selres['mobile']; ?></td>
                  <td><?php echo $selres['cur_balance']; ?></td>
                  <td><?php echo $selres['won_balance']; ?></td>
                  <td><?php echo $selres['bonus_balance']; ?></td>
                  
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
                    
                    <a href="#" data-toggle="modal" data-id="<?php echo $selres['id']; ?>" data-uname="<?php echo $selres['username']; ?>" data-email="<?php echo $selres['email']; ?>" data-cbal="<?php echo $selres['cur_balance']; ?>" data-wbal="<?php echo $selres['won_balance']; ?>" data-bbal="<?php echo $selres['bonus_balance']; ?>" data-target="#myModal2" class="addBal" data-toggle="tooltip" data-placement="top" title="" data-original-title="Load Money"><i class="fa fa-money"></i> </a>&nbsp;&nbsp;

                    <a href="view-user-details.php?userId=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="View User Details" data-original-title="User details"><i class="md md-exit-to-app"></i></a>&nbsp;&nbsp;

                    <a href="referral-details.php?rcode=<?php echo $selres['refer'];?>&rid=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Track User Refer Activity"><i class="fa  fa-line-chart"></i></a>&nbsp;&nbsp;

                    <a href="participation-report.php?rid=<?php echo $selres['id'];?>" class="remove-row" style="color: #5FBEAA;" data-toggle="tooltip" data-placement="top" title="" data-original-title="User Participation"><i class="fa fa-list"></i></a>
                  </td>
              </tr>
          <?php } ?>
          </div>
        </tbody>
      </table>
    </div>
  <!-- Display pagination links -->
  <?php echo $pagination->createLinks(); ?>

<?php 
    }else{ 
        echo '<p>Post(s) not found...</p>'; 
    } 
} 
?>