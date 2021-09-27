<?php
include_once 'DBconnect.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
<?php
$sql="SELECT * FROM product;";
$result=mysqli_query($conn,$sql);
$resultCheck=mysqli_num_rows($result);
if(!$result)
	{
		echo "<p class=\"wrong\">Something is wrong with ",$sql, "</p>";
	}
	if (mysqli_num_rows($result)==0) {
		echo "<div style='background-color:red;'><p>There is no data</p></div>";
	}
	else
	{
		echo"<p><b>Product table </b></p>";
		echo "<table border=\"1\">\n";
		echo "<tr>\n".
			"<th scope=\"col\">product_ID</th>\n".
			 "<th scope=\"col\">product_name</th>\n".
			 "<th scope=\"col\">product_desc</th>\n".
			 "<th scope=\"col\">Product_price</th>\n".
			 "<th scope=\"col\">images</th>\n".

			 "</tr>\n";

		//retrieved current report pointed by the result pointer
			 // get all the info until it null
			 while ($row=mysqli_fetch_assoc($result))
			 {
			 	echo "<tr>\n";
			 	// align the text in the table cell
			 	echo "<td align=center>",$row["product_ID"],"</td>\n";
			 	echo "<td>",$row["product_name"],"</td>\n";
			 	echo "<td>",$row["product_desc"],"</td>\n";
			 	echo "<td align=center>",$row["Product_price"],"($)</td>\n";
				echo "<td>","<img width=\"200\" src='images/".$row['images']."' >","</td>\n";
				echo "<td>";

                                        //Click to update
                                            echo "<a href='editing.php?product_ID=". $row['product_ID'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                        //Click to delete
                                            echo "<a href='delete.php?product_ID=". $row['product_ID'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
			 	echo "</tr>\n";

			 }
			 echo "</table>\n";
			 mysqli_free_result($result);
	}//if successful database connection
	//close the database connection 
	mysqli_close($conn);	
	
	

?>
</body>
</html>