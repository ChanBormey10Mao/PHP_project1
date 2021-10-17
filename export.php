<?php 
if(isset($_POST['export']))
{
	include('setting.php');
	header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=sale.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('prodcut_ID', 'product_name', 'product_desc', 'Product_price', 'images'));  //array from the database //usign for column name
      $query = "SELECT * from product ORDER BY product_ID DESC";  
      $result = mysqli_query($conn, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
}

?>