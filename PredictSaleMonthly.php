<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predict Sale Weekly</title>
    <link rel="stylesheet" href="PredictSaleWeekly.css">
</head>

<body onunload="func()">
    <?php include("DBconnect.php");
    session_start();

    ?>

    <?php
    $_SESSION["SalePredict"] = RetrieveDataDB(($conn));
    if (isset($_POST["submitSearch"])) {

        if (isset($_POST["productID"]) && trim($_POST["productID"]) != "") {
            $productIDSearch = $_POST["productID"];
            $_SESSION["SalePredict"] = SearchProductID($conn, $productIDSearch);
            // print_r($_SESSION["SalePredict"]);
            $past3monthdate = date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-d'))));
            // echo "<p>\$past3monthdate is $past3monthdate </p>";
            // echo "today is" . date('Y-m-d') . "<br>";
            for ($i = 0; $i < count($_SESSION["SalePredict"]); $i++) {
                // echo $_SESSION["SalePredict"][$i]["sale_date"] . "<br>";
                if ($_SESSION["SalePredict"][$i]["sale_date"] > $past3monthdate) {
                    $Weekqty3 = $Weekqty3 +  $_SESSION["SalePredict"][$i]["sale_PQuantity"];
                    $WeekPricePerRow3 = $WeekPricePerRow3 +  $_SESSION["SalePredict"][$i]["Price_Per_Product"];
                }
            }
            $_SESSION["avg_qty_productID"] =  number_format($Weekqty3 / 3, 2);
            $_SESSION["price_avg_productID"] = number_format($WeekPricePerRow3 / 3, 2);
        }
        if (isset($_POST["productName"]) && trim($_POST["productName"]) != "") {
            $productNameSearch = $_POST["productName"];
            $_SESSION["SalePredict"] = SearchByProductName($conn, $productNameSearch);
            $Ride = "";
        }
    }
    if (isset($_GET["action"]) == "lookname") {
        $idlooked = $_GET["idlook"];
        // echo $idlooked . "<br>";
        $_SESSION["SalePredict"] = SearchProductID($conn, $idlooked);

        $past3monthdate = date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-d'))));
        // echo "<p>\$past3monthdate is $past3monthdate </p>";
        // echo "today is" . date('Y-m-d') . "<br>";
        for ($i = 0; $i < count($_SESSION["SalePredict"]); $i++) {
            // echo $_SESSION["SalePredict"][$i]["sale_date"] . "<br>";
            if ($_SESSION["SalePredict"][$i]["sale_date"] > $past3monthdate) {
                $Weekqty3 = $Weekqty3 +  $_SESSION["SalePredict"][$i]["sale_PQuantity"];
                $WeekPricePerRow3 = $WeekPricePerRow3 +  $_SESSION["SalePredict"][$i]["Price_Per_Product"];
            }
        }
        $_SESSION["avg_qty_productID"] =  number_format($Weekqty3 / 3, 2);
        $_SESSION["price_avg_productID"] = number_format($WeekPricePerRow3 / 3, 2);
    }

    ?>

    <!-- <a href="PredictSaleMonthly.php?action=lookname&idlook=<?php echo $row["SalePredict"]["product_ID"]; ?>">View Weekly Average</a> -->
    <form action="PredictSaleMonthly.php" method="POST">
        <section>
            <label for="productID">Product ID</label>
            <input type="text" name="productID">
        </section>
        <section>
            <label for="productName">Product Name</label>
            <input type="text" name="productName">
        </section>
        <!-- <label for="date">Date</label>
        <input type="text" name="date"> -->
        <input type="submit" name="submitSearch" value="View Prediction">
    </form>

    <table style="width: 100%;" class="content-table">
        <tr>

            <td class="test">Product ID</td>
            <td class="test"> Product Name</td>
            <td class="test">Sale Date</td>
            <td class="test">Month</td>

            <!-- <td class="test">Week No</td> -->

            <td class="test">Sale Quantity</td>
            <td class="test">Price</td>
            <?php if (isset($Ride)) { ?>
                <td>View Average Sale</td>
            <?php } ?>
        </tr>
        <?php
        foreach ($_SESSION["SalePredict"] as $row) {
        ?>
            <tr>
                <td><?php echo $row["product_ID"]; ?></td>
                <td><?php echo $row["product_name"]; ?></td>
                <td><?php echo $row["sale_date"]; ?></td>
                <td><?php echo $row["month"]; ?></td>

                <td><?php echo $row["sale_PQuantity"]; ?></td>
                <td><?php echo $row["Price_Per_Product"]; ?></td>

                <?php if (isset($Ride)) { ?>
                    <td><a href="PredictSaleMonthly.php?action=lookname&idlook=<?php echo $row["product_ID"]; ?>">View Weekly Average</a></td>
                <?php } ?>

            </tr>
        <?php
        }
        ?>
    </table>
    <?php
    if (isset($_SESSION["avg_qty_productID"])) {
    ?>
        <div>
            <p>The Average Quantity sold out quantity in the past 3 months for <?php echo $_SESSION["SalePredict"][0]["product_name"]; ?> is <span style="color:green;"><?php echo $_SESSION["avg_qty_productID"]; ?></span> </p>
            <p>The avergae sale price made from <?php echo $_SESSION["SalePredict"][0]["product_name"]; ?> is <span style="color:green;"><?php echo $_SESSION["price_avg_productID"]; ?></span> </p>

        </div>
        <div>
            <button><a href="PredictSaleMonthly.php">Back</a></button>
            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <button><a href="Menu.php">Return to Menu Page</a></button>
        </div>
    <?php
        unset($_SESSION["avg_qty_productID"]);
    }
    ?>


    <?php
    function SearchByProductName($conn, $product_name)
    {
        $query = "SELECT sale_product.product_ID, product.product_name, sale_product.sale_PQuantity,sale.sale_date,ROUND(sale_product.sale_PQuantity * product.Product_price,2) AS Price,        
        DATE_ADD(NOW(),INTERVAL -90 DAY) , MONTH(sale_date) as month
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        WHERE product.product_name LIKE '%$product_name%'
        GROUP BY sale_product.product_ID,DATE_ADD(NOW(),INTERVAL -90 DAY) 
        ORDER BY DATE_ADD(NOW(),INTERVAL -90 DAY)  DESC;  ";
        $info_arr = array();

        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                // print_r($row);
                // echo  "<br>";
                // $row["start_date"] = date($row["sale_date"], strtotime("this week"));
                // $row["end_date"] =  date('Y-m-d', strtotime('sunday this week', strtotime($row["sale_date"])));
                $info_arr_new[] = array(
                    "product_ID" => @$row["product_ID"],
                    "product_name" => @$row["product_name"],
                    "sale_PQuantity" => @$row["sale_PQuantity"],
                    "Price_Per_Product" => @$row["Price"],
                    "sale_date" => @$row["sale_date"],
                    "month" => @$row["month"]
                );
                $info_arr = $info_arr + $info_arr_new;
            }
        } else {
            echo "0 results" . "<hr>";
            return null;
        }
        // echo "***************************" . "<br>";
        // print_r($info_arr);
        return $info_arr;
    }
    function SearchProductID($conn, $product_ID)
    {
        $query = "SELECT sale_product.product_ID, product.product_name, SUM(sale_product.sale_PQuantity) as sale_PQuantity,sale.sale_date,ROUND(SUM(sale_product.sale_PQuantity) * product.Product_price,2) AS Price,        
        DATE_ADD(NOW(),INTERVAL -90 DAY)  , MONTH(sale_date) as month
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        WHERE sale_product.product_ID = $product_ID 
        GROUP BY sale_product.product_ID,DATE_ADD(NOW(),INTERVAL -90 DAY) 
        ORDER BY sale_date >= DATE_ADD(NOW(),INTERVAL -90 DAY)  DESC LIMIT 100;  ";
        $info_arr = array();

        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                // print_r($row);
                // echo  "<br>";

                $info_arr_new[] = array(
                    "product_ID" => @$row["product_ID"],
                    "product_name" => @$row["product_name"],
                    "sale_PQuantity" => @$row["sale_PQuantity"],
                    "Price_Per_Product" => @$row["Price"],
                    "Week_No" => @$row["Week_No"],
                    "sale_date" => @$row["sale_date"],
                    "month" => @$row["month"]

                );
                $info_arr = $info_arr + $info_arr_new;
            }
        } else {
            // echo "0 results UpdateInfo" . "<hr>";
            return null;
        }
        // echo "***************************" . "<br>";
        // print_r($info_arr);
        return $info_arr;
    }
    function RetrieveDataDB($conn)
    {
        $query = "SELECT sale_product.product_ID, product.product_name, SUM(sale_product.sale_PQuantity) as sale_PQuantity,sale.sale_date,ROUND(SUM(sale_product.sale_PQuantity) * product.Product_price,2) AS Price, 
       
        DATE_ADD(NOW(),INTERVAL -90 DAY)  as Week_No 
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        GROUP BY sale_product.product_ID,DATE_ADD(NOW(),INTERVAL -90 DAY) 
        ORDER BY DATE_ADD(NOW(),INTERVAL -90 DAY)  DESC;";
        $info_arr = array();

        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                // print_r($row);
                // echo  "<br>";
                $row["start_date"] = date($row["sale_date"], strtotime("this week"));
                $row["end_date"] =  date('Y-m-d', strtotime('sunday this week', strtotime($row["sale_date"])));
                $info_arr_new[] = array(
                    "product_ID" => @$row["product_ID"],
                    "product_name" => @$row["product_name"],
                    "sale_PQuantity" => @$row["sale_PQuantity"],
                    "Price_Per_Product" => @$row["Price"],
                    "Week_No" => @$row["Week_No"],
                    "sale_date" => @$row["sale_date"]
                );
                $info_arr = $info_arr + $info_arr_new;
            }
        } else {
            echo "0 results UpdateInfo" . "<hr>";
            return null;
        }
        // echo "***************************" . "<br>";
        // print_r($info_arr);
        return $info_arr;
    }

    ?>
    <script type="text/javascript">
        function func() {
            if (window.history.previous) {
                window.navigate('PredictSaleMonthly.php');
            }
        }
    </script>
</body>

</html>