<!--search engine-->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body>

    <?php
    //require_once("setting.php");//connected the database
    //check if the connection is successful
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


    ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Menu</title>

    <link href="menu.css" rel="stylesheet">
</head>

<body>
    <div class="box-area">
        <header>

            <nav>
                <a href="CreateSale.php">Create New Sale</a>
                <a href="ReviewSale.php">View Sales</a>
                <a href="ReturnedSale.php">Return Product</a>
                <a href="AddProducts.php">Create New Product</a>
                <a href="Product_View.php">View All Product</a>
                <a href="#">Hide a product</a>
                <a href="#">Stock Managment</a </nav>
    </div>
    </header>
    <div class="banner-area">

    </div>
    <div class="content-area">
        <div class="wrapper">
            <h2>About Us</h2>
            <p>Information</p>
        </div>
    </div>
</body>

</html>