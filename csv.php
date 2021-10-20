<?php
include('DBconnect.php');

$query="select * from product order by product_ID desc;";
$result=mysqli_query($conn,$query);
?>

<!DOCTYPE html>  
 <html>  
      <head>  
           <title>Webslesson Tutorial | Export Mysql Table Data to CSV file in PHP</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>  
           <link rel="stylesheet" href="csv.css">
      </head>  
      <body>  
      <navbar>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="menu.css">Menu</a>
            </li>

                    </ul>

    </navbar>
           <br /><br />  
           <div class="container" style="width:900px;">  
                <h2 align="center">Export Mysql Table Data to CSV file in PHP</h2>  
                <h3 align="center">Employee Data</h3>                 
                <br />  
                <form method="post" action="export.php" align="center">  
                     <input type="submit" name="export" value="CSV Export" class="btn btn-success" />  
                </form>  
                <br />  
                <div class="table-responsive" id="employee_table">  
                     <table class="table-bordered" >  
                          <tr>  
                               <th width="5%">ID</th>  
                               <th width="25%">Product Name</th>  
                               <th width="20%">Product Description</th>  
                               <th width="20%">Product Price</th>  
                               <th width="40%">Image</th>  
                                
                          </tr>
                          <?php  
                     while($row = mysqli_fetch_array($result))  
                     {  
                     ?>  
                          <tr>  
                               <td><?php echo $row["product_ID"]; ?></td>  
                               <td><?php echo $row["product_name"]; ?></td>  
                               <td><?php echo $row["product_desc"]; ?></td>  
                               <td><?php echo $row["Product_price"]; ?></td>  
                               <td><?php echo $row["images"]; ?></td>    
                          </tr>  
                     <?php       
                     }  
                     ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>    