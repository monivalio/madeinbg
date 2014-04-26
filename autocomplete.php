<?php
   
  $con=mysqli_connect("localhost","root","Sime0n91","hackfmi");
  $con->set_charset('utf8');
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $result = mysqli_query($con,"SELECT item_name FROM wp_UPCP_Items");

  $items = [];
  $i=0;
  while($row = mysqli_fetch_array($result)) {
 //  $data = iconv('cp1251', 'utf-8', $data); 
    array_push($items, $row['item_name']);
    //array_push($items, "test".$i)
  }
  //echo count($items);
  echo json_encode($items);
  mysqli_close($con);
?>
