<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predict Sale Weekly</title>
</head>

<body>
    <?php include("DBconnect.php"); ?>
    <?php RetrieveDataDB(($conn)) ?>
    <?php
    function RetrieveDataDB($conn)
    {
        $query = "SELECT sale_product.product_ID, product.product_name, sale_product.sale_PQuantity,sale.sale_date,ROUND(sale_product.sale_PQuantity * product.Product_price,2) AS Price, WEEK(sale.sale_date) as Week_No 
    FROM sale  
    INNER JOIN sale_product
    ON sale.sale_ID = sale_product.sale_ID
    INNER JOIN product
    ON sale_product.product_ID = product.product_ID
    GROUP BY sale_product.product_ID
    ORDER BY WEEK(sale.sale_date);";
        $info_arr = array();

        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                print_r($row);
                echo  "<br>";
                $info_arr_new = array(
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
        echo "***************************" . "<br>";
        print_r($info_arr);
        return $info_arr;
    }
    ?>

</body>

</html>