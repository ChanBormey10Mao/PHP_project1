<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predict Sale Weekly</title>
</head>

<body>
    <?php include("DBconnect.php");
    session_start();

    ?>

    <?php
    $_SESSION["SalePredict"] = RetrieveDataDB(($conn));
    if (isset($_POST["submitSearch"])) {

        if (isset($_POST["productID"])) {
            $productIDSearch = $_POST["productID"];
            $_SESSION["SalePredict"] = SearchProductID($conn, $productIDSearch);
            $past3weekdate = date('Y-m-d', strtotime('-3 weeks', strtotime(date('Y-m-d'))));
            // echo "<p>\$past3weekdate is $past3weekdate </p>";
            // echo "today is" . date('Y-m-d') . "<br>";
            for ($i = 0; $i < count($_SESSION["SalePredict"]); $i++) {
                if ($_SESSION["SalePredict"][$i]["sale_date"] > $past3weekdate) {
                    $Weekqty3 = $Weekqty3 +  $_SESSION["SalePredict"][$i]["sale_PQuantity"];
                    $WeekPricePerRow3 = $WeekPricePerRow3 +  $_SESSION["SalePredict"][$i]["Price_Per_Product"];
                }
            }
            $_SESSION["avg_qty_productID"] =  $Weekqty3 / 3;
            $_SESSION["price_avg_productID"] = $WeekPricePerRow3 / 3;
        }
        // if (isset($_POST["productName"])) {
        //     $productNameSearch = $_POST["productName"];
        //     $_SESSION["SalePredict"] = SearchByProductName($conn, $productNameSearch);
        //     $past3weekdate = date('Y-m-d', strtotime('-3 weeks', strtotime(date('Y-m-d'))));
        //     $ArrAfterDiffID = array();
        //     $Weekqty3s = array();
        //     $WeekPricePerRow3s = array();
        //     for ($i = 0; $i < count($_SESSION["SalePredict"]); $i++) {
        //         //    for($j =i; $j<count($_SESSION["SalePredict"]) ; $j++)
        //         $ID = $_SESSION["SalePredict"][$i]["product_ID"];
        //         for($j =$i; $j<count($_SESSION["SalePredict"]) ; $j++){
        //             if($_SESSION["SalePredict"][$j]["product_ID"] ==  $ID ){

        //             }
        //         }
        //         // $ArrAfterDiffID[$data['product_ID']][$row] = $data;
        //         // print_r($ArrAfterDiffID[$data['product_ID']][$row]);
        //         // echo "<br>";
        //     }
        //     ksort($ArrAfterDiffID, SORT_NUMERIC);
        // }
    }
    ?>
    <form action="PredictSaleWeekly.php" method="POST">
        <section>
            <label for="productID">Product ID</label>
            <input type="text" name="productID">
        </section>
        <!-- <section>
            <label for="productName">Product Name</label>
            <input type="text" name="productName">
        </section> -->
        <!-- <label for="date">Date</label>
        <input type="text" name="date"> -->
        <input type="submit" name="submitSearch" value="View Prediction">
    </form>

    <table style="width: 100%;">
        <tr>
            <td>Product ID</td>
            <td>Product Name</td>
            <td>Sale Date</td>
            <td>Week No</td>

            <td>Sale Quantity</td>
            <td>Price</td>
        </tr>
        <?php
        foreach ($_SESSION["SalePredict"] as $row) {
        ?>
            <tr>
                <td><?php echo $row["product_ID"]; ?></td>
                <td><?php echo $row["product_name"]; ?></td>
                <td><?php echo $row["sale_date"]; ?></td>
                <td><?php echo $row["Week_No"]; ?></td>

                <td><?php echo $row["sale_PQuantity"]; ?></td>
                <td><?php echo $row["Price_Per_Product"]; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php
    if (isset($_SESSION["avg_qty_productID"])) {
    ?>
        <div>

            <p>The Average Quantity sold out quantity in the past 3 weeks for <?php echo $_SESSION["SalePredict"][0]["product_name"]; ?> is <span style="color:green;"><?php echo $_SESSION["avg_qty_productID"]; ?></span> </p>
            <p>The avergae sale price made from <?php echo $_SESSION["SalePredict"][0]["product_name"]; ?> is <span style="color:green;"><?php echo $_SESSION["price_avg_productID"]; ?></span> </p>

        </div>
    <?php
        unset($_SESSION["avg_qty_productID"]);
    }
    ?>


    <?php
    function SearchByProductName($conn, $product_name)
    {
        $query = "SELECT sale_product.product_ID, product.product_name, sale_product.sale_PQuantity,sale.sale_date,ROUND(sale_product.sale_PQuantity * product.Product_price,2) AS Price,        
        WEEK(sale.sale_date,3) as Week_No 
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        WHERE product.product_name LIKE '%$product_name%'
        GROUP BY sale_product.product_ID,WEEK(sale.sale_date,3)
        ORDER BY WEEK(sale.sale_date,3) DESC;  ";
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
                    "sale_date" => @$row["sale_date"],
                    "start_date" => @$row["start_date"],
                    "end_date" => @$row["end_date"]
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
        WEEK(sale.sale_date,3) as Week_No 
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        WHERE sale_product.product_ID = $product_ID 
        GROUP BY sale_product.product_ID,WEEK(sale.sale_date,3)
        ORDER BY WEEK(sale.sale_date,3) DESC LIMIT 100;  ";
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
                    "sale_date" => @$row["sale_date"],
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
        -- DATE_ADD(sale.sale_date, INTERVAL(-DAYOFWEEK(sale.sale_date)) DAY) as start_date,
        -- DATE_ADD(sale.sale_date, INTERVAL(-DAYOFWEEK(sale.sale_date)) DAY) as end_date, 
        WEEK(sale.sale_date,3) as Week_No 
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        GROUP BY sale_product.product_ID,WEEK(sale.sale_date,3)
        ORDER BY WEEK(sale.sale_date,3) DESC;";
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

</body>

</html>