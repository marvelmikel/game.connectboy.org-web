<?php 
if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.class.php'; 
     
    // Include database configuration file 
    require_once 'include/conn.php'; 
     
    // Set some useful configuration 
    $baseURL = 'getData_tran.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 10; 
     
    // Set conditions for search 
    $whereSQL = $orderSQL = ''; 
    if(!empty($_POST['keywords'])){ 
        $whereSQL = "WHERE U.fname LIKE '%".$_POST['keywords']."%' or U.lname LIKE '%".$_POST['keywords']."%' or T.remark LIKE '%".$_POST['keywords']."%' or T.id LIKE '%".$_POST['keywords']."%'"; 
    } 
    if(!empty($_POST['sortBy'])){ 
        $orderSQL = " ORDER BY U.fname ".$_POST['sortBy']; 
    }else{ 
        $orderSQL = " ORDER BY T.id DESC "; 
    } 
     
    // Count of all records 
    $query   = $conn->query("SELECT COUNT(*) as rowNum FROM transaction_details as T left join user_details as U on U.id=T.user_id ".$whereSQL); 
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
    $query = $conn->query("select T.*,U.fname,U.lname from transaction_details as T left join user_details as U on U.id=T.user_id $whereSQL $orderSQL LIMIT $offset,$limit"); 
     
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
  <?php if($pagination){ echo $pagination->createLinks();} else {'erro';} ?>

<?php 
    }else{ 
        echo '<p>Data not found...</p>'; 
    } 
} 
?>