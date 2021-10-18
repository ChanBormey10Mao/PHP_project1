<?php 
if(isset($_POST['export']))
{
	include('DBconnect.php');
	header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=sale.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('sale_ID', 'sale_date', 'sale_time','product_ID','product_name',"Sale Quantity", 'sale_price', 'images'));  //array from the database //usign for column name
      $query = "SELECT sale_product.sale_ID, sale.sale_date, sale.sale_time,sale_product.product_ID ,product.product_name,sale_product.sale_PQuantity ,sale.sale_price,product.images  
      FROM sale_product 
      INNER JOIN sale
      ON sale.sale_ID = sale_product.sale_ID
      INNER JOIN product
      ON sale_product.product_ID = product.product_ID;";  
      $result = mysqli_query($conn, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
}

?>