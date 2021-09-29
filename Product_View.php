<?php
include_once 'DBconnect.php';
?>

<!DOCTYPE html>
<html>

<head>
	<center>
		<meta charset="utf-8">


		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<!--<link rel="stylesheet" href="Style/Addproducts.css">-->

		<title></title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				$('[data-toggle="tooltip"]').tooltip();
			});
		</script>
		<style>
			table,
			th,
			td {
				border: 1px solid black;
			}

			body {
				font-size: 2em;
				line-height: 2em;
			}
		</style>
		<style>
			p.thick {
				font-weight: bold;
			}
		</style>
		<!--style the navigation bar-->
		<style>
			ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				background-color: #333;
			}

			li {
				float: left;
			}

			li a {
				display: block;
				color: white;
				text-align: center;
				padding: 14px 16px;
				text-decoration: none;
			}

			li a:hover:not(.active) {
				background-color: #111;
			}

			.active {
				background-color: #04AA6D;
			}

			.edit_sign {
				font-size: 3rem;
			}

			.delete_sign {
				font-size: 3rem;
			}
		</style>
		<!--End of styling-->

		<!--Footer styling-->
		<style>
			.all-browsers {
				margin: 0;
				padding: 5px;
				background-color: lightgray;
			}

			.all-browsers>h1,
			.browser {
				margin: 10px;
				padding: 5px;
			}

			.browser {
				background: white;
			}

			.browser>h2,
			p {
				margin: 4px;
				font-size: 90%;
			}

			footer {
				text-align: center;
				padding: 3px;
				background-color: #333;
				color: white;
			}
		</style>
		<!--end of footer styling-->
</head>

<body>
	<ul>
		<li><a href="Menu.php">Menu</a></li>

	</ul>
	<form method="post" action="#">

		<fieldset>
			<legend>
				<h3>SEARCH ENGINE</h3>
			</legend>
			<p>NOTE: Enter the product name to search in our database</p>
			<label for="input">Product name:</label>
			<input type="text" name="input" id="input"><br><br>
			<p> <input type="submit" value="Search" name="seachbar" /></p>
		</fieldset>
	</form>
	<h1>Product Table</h1>
	<?php
	$sql = "SELECT * FROM product;";
	$result = mysqli_query($conn, $sql);
	$resultCheck = mysqli_num_rows($result);
	if (isset($_POST["seachbar"])) {
		//echo "successful";
		//get data from HTML
		$input = trim($_POST["input"]);
		$input = htmlspecialchars($_POST["input"]);
		//upon successful connect
		//$sql_table="orders";
		//set up the SQL command to query or adddata into the table
		$query = "select * from product where product_name like '$input'";
		//execute the query and store result into the result pointer
		$result = mysqli_query($conn, $query);

		if (!$result) {
			echo "<p class=\"wrong\">Something is wrong with ", $query, "</p>";
		}
		if (mysqli_num_rows($result) == 0) {
			echo "<div style='background-color:red;'><p>There is no data</p></div>";
		} else {
			echo "<p><b>This is the table </b></p>";
			echo "<table border=\"1\">\n";
			echo "<tr>\n" .
				"<th scope=\"col\">product_ID</th>\n" .
				"<th scope=\"col\">product_name</th>\n" .
				"<th scope=\"col\">product_desc</th>\n" .
				"<th scope=\"col\">Product_price</th>\n" .
				"<th scope=\"col\">images</th>\n" .
				"</tr>\n";

			//retrieved current report pointed by the result pointer
			// get all the info until it null
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>\n";
				// align the text in the table cell
				echo "<td align=center>", $row["product_ID"], "</td>\n";
				echo "<td>", $row["product_name"], "</td>\n";
				echo "<td>", $row["product_desc"], "</td>\n";
				echo "<td align=center>", $row["Product_price"], "($)</td>\n";
				echo "<td>", "<img width=\"200\" src='images/" . $row['images'] . "' >", "</td>\n";
				echo "<td>";

				//Click to update
				echo "<a href='editing.php?product_ID=" . $row['product_ID'] . "' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
				/*  //Click to delete
												echo "<a href='delete.php?product_ID=". $row['product_ID'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
											echo "</td>";*/
				echo "</tr>\n";
			}
			echo "</table>\n";
			mysqli_free_result($result);
		} //if successful database connection
		//close the database connection 
		//mysqli_close($conn);	
	}
	if (!$result) {
		echo "<p class=\"wrong\">Something is wrong with ", $sql, "</p>";
	}
	if (mysqli_num_rows($result) == 0) {
		echo "<div style='background-color:red;'><p>There is no data</p></div>";
	} else {
		echo "<table border=\"1\">\n";
		echo "<tr>\n" .
			"<th scope=\"col\">product_ID</th>\n" .
			"<th scope=\"col\">product_name</th>\n" .
			"<th scope=\"col\">product_desc</th>\n" .
			"<th scope=\"col\">Product_price</th>\n" .
			"<th scope=\"col\">images</th>\n" .

			"</tr>\n";

		//retrieved current report pointed by the result pointer
		// get all the info until it null
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<tr>\n";
			// align the text in the table cell
			echo "<td align=center>", $row["product_ID"], "</td>\n";
			echo "<td>", $row["product_name"], "</td>\n";
			echo "<td>", $row["product_desc"], "</td>\n";
			echo "<td align=center>", $row["Product_price"], "($)</td>\n";
			echo "<td>", "<img width=\"200\" src='images/" . $row['images'] . "' >", "</td>\n";
			echo "<td>";

			//Click to update
			echo "<a href='editing_Product.php?product_ID=" . $row['product_ID'] . "' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil edit_sign'></span></a>";
			echo "<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
			//Click to delete
			// echo "<a href='delete.php?product_ID=" . $row['product_ID'] . "' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash delete_sign'></span></a>";
			echo "</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";
		mysqli_free_result($result);
	} //if successful database connection
	//close the database connection 
	mysqli_close($conn);



	?>
	<br>
	<footer>
		Copyright 2021<br>
		Author:group 13<br>
		Email:102579094@student.swin.edu.au
	</footer>
	</center>
</body>

</html>