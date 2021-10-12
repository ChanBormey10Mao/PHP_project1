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
    <?php $_SESSION["AllSale"] = RetrieveDataDB(($conn)) ?>
    <table style="width: 100%;">
        <tr>
            <td>Product ID</td>
            <td>Product Name</td>
            <td>Sale Date</td>
            <td>Week No</td>
            <td>Week Start Date</td>
            <td>Week End Date</td>
            <td>Sale Quantity</td>
            <td>Price</td>
        </tr>
        <?php
        foreach ($_SESSION["AllSale"] as $row) {
        ?>
            <tr>
                <td><?php echo $row["product_ID"]; ?></td>
                <td><?php echo $row["product_name"]; ?></td>
                <td><?php echo $row["sale_date"]; ?></td>
                <td><?php echo $row["Week_No"]; ?></td>
                <td><?php echo $row["start_date"]; ?></td>
                <td><?php echo $row["end_date"]; ?></td>
                <td><?php echo $row["sale_PQuantity"]; ?></td>
                <td><?php echo $row["Price_Per_Product"]; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>

    <?php
    function RetrieveDataDB($conn)
    {
        $query = "SELECT sale_product.product_ID, product.product_name, sale_product.sale_PQuantity,sale.sale_date,ROUND(sale_product.sale_PQuantity * product.Product_price,3) AS Price, 
        DATE_ADD(sale.sale_date, INTERVAL(2-DAYOFWEEK(sale.sale_date)) DAY) as start_date,
        DATE_ADD(sale.sale_date, INTERVAL(8-DAYOFWEEK(sale.sale_date)) DAY) as end_date, 
        WEEK(sale.sale_date,3) as Week_No 
        FROM sale  
        INNER JOIN sale_product
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        GROUP BY sale_product.product_ID,WEEK(sale.sale_date,3)
        ORDER BY WEEK(sale.sale_date) DESC;";
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
                    "start_date" => @$row["start_date"],
                    "end_date" => @$row["end_date"]
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