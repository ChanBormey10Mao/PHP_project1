<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sale</title>
    <link rel="stylesheet" href="ReturnedSale.css">
</head>

<body>
    <?php

    // use JetBrains\PhpStorm\ArrayShape;

    use JetBrains\PhpStorm\ArrayShape;

    include("DBconnect.php");



    if (isset($_GET["action1"]) == "edit") {
        session_start();
        include("DBconnect.php");
        $sale_ID =  $_GET["idEdit"];
        $_SESSION["Sale"] = RetriveDataBySaleID($sale_ID, $conn);
        $_SESSION["Product"] = SaleDataPrep($_SESSION["Sale"]);
    }
    ?>

    <?php

    if (isset($_GET["action2"]) == "delete") {
        include("DBconnect.php");
        $tobeDeleteID = $_GET["idDelete"];



        $UpdateInfoArr = UpdateInfo($tobeDeleteID, $conn);
        // include("DBconnect.php");

        // include("DBconnect.php");
        // UpdateSaleRecord($UpdateInfoArr, $conn);
        DeleteProductSaleRow($UpdateInfoArr);
    }

    ?>
    <form action="ReturnedSale.php" method="POST" name="EditSaleForm">
        <table style="width:100%;">
            <?php foreach ($_SESSION["Product"] as $row) { ?>
                <tr>
                    <td>Product Name:
                        <img src="images/<?php echo $row["images"]; ?>" alt="Product Picture">
                        <span>&nbsp;&nbsp;&nbsp;</span>
                        <input type="hidden" name="sale_ID" value="<?php echo $row["sale_ID"]; ?>">
                        <input type="hidden" name="ProductInSale_ID" value="<?php echo $row["ProductInSale_ID"]; ?>">
                        <input type="hidden" name="product_id" value="<?php echo $row["product_ID"]; ?>">
                        <div><?php echo $row["product_name"]; ?></div>

                    </td>
                    <td>Product Quantity:
                        <!-- <input type="text" name="product_Qty" value="<?php echo $row["product_Qty"]; ?>"> -->
                        <div><?php echo $row["product_Qty"]; ?></div>

                    </td>
                    <td>
                        <button><a href="ReturnedSale.php?action2=delete&idDelete=<?php echo $row["ProductInSale_ID"]; ?>">Remove</a></button>
                    </td>

                </tr>
            <?php } ?>
        </table>
        <section>

        </section>
        <?php

        ?>
    </form>
    <div>
        <button><a href="ReviewSale.php">Review Sales</a></button>
        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <button><a href="Menu.php">Back to Menu</a></button>
    </div>
    <?php


    function DeleteProductSaleRow($Arr)
    {
        include("DBconnect.php");
        $ID = $Arr["ProductInSale_ID"];
        $sale_ID = $Arr["sale_ID"];
        $minus = number_format($Arr["Product_price"] * $Arr["sale_PQuantity"], 2);
        $sale_price = $Arr["sale_price"] - $minus;
        $query = "UPDATE sale
        SET sale_price = $sale_price
        WHERE sale_ID = $sale_ID" . ";";
        $query .= "DELETE FROM sale_product
        WHERE  ProductInSale_ID = $ID";
        if (mysqli_multi_query($conn, $query)) {
            foreach ($_SESSION["Product"] as $keys => $values) {
                if ($values["ProductInSale_ID"] == $ID) {
                    unset($_SESSION["Product"][$keys]);
                }
            }

            echo " <script>alert(\"Record ProductInSale_ID is deleted successfully.\")</script>";
            echo "<script>window.location = \"ReturnedSale.php\"</script>";
            // return "Record ProductInSale_ID is deleted successfully.";
        } else {
            echo "ERROR: Could not able to execute $query. " . mysqli_error($conn) . "<hr>";
        }
    }
    function UpdateInfo($ID, $conn)
    {


        $query = "SELECT sale_product.ProductInSale_ID,sale_product.sale_ID,sale.sale_price,product.Product_price, sale_product.sale_PQuantity,product.images
        FROM sale_product 
        INNER JOIN product
        ON product.product_ID = sale_product.product_ID
        INNER JOIN sale
        ON sale.sale_ID = sale_product.sale_ID
        WHERE sale_product.ProductInSale_ID='$ID';";

        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $info_new[] = array(
                    "ProductInSale_ID" => @$row["ProductInSale_ID"],
                    "images" => @$row["images"],
                    "sale_ID" => @$row["sale_ID"],
                    "sale_price" => @$row["sale_price"],
                    "Product_price" => @$row["Product_price"],
                    "sale_PQuantity" => @$row["sale_PQuantity"],
                );
            }
        } else {
            echo "0 results UpdateInfo" . "<hr>";
            return null;
        }
        // print_r($sale_info);
        $conn->close();
        return $info_new[0];
    }




    function RetriveDataBySaleID($sale_ID, $conn)
    {
        $query = "SELECT sale_product.sale_ID, sale.sale_date, sale.sale_time,sale_product.ProductInSale_ID,sale_product.product_ID ,product.product_name,sale_product.sale_PQuantity ,sale.sale_price  ,product.images
        FROM sale_product 
        INNER JOIN sale
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        WHERE sale_product.sale_ID = $sale_ID;";

        $result = mysqli_query($conn, $query);
        $sale_info = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sale_info_new[] = array(
                    "sale_ID" => @$row["sale_ID"],
                    "sale_date" => @$row["sale_date"],
                    "sale_time" => @$row["sale_time"],
                    "images" => @$row["images"],
                    "ProductInSale_ID" => @$row["ProductInSale_ID"],
                    "product_ID" => @$row["product_ID"],
                    "product_name" => @$row["product_name"],
                    "qnty" => @$row["sale_PQuantity"],
                    "total" => @$row["sale_price"]
                );
                $sale_info = $sale_info + $sale_info_new;
            }
        } else {
            echo "0 results";
            return null;
        }
        // print_r($sale_info);
        $conn->close();
        return $sale_info;
    }
    function SaleDataPrep($sale_info)
    {
        $product_ID_arr = array();
        $temp = array();
        for ($i = 0; $i < count($sale_info); $i++) {
            for ($j = $i; $j < count($sale_info); $j++) {
                if (in_array($sale_info[$j]["sale_ID"], $sale_info[$i])) {
                    $temp = array(
                        "sale_ID" => @$sale_info[$i]["sale_ID"],
                        "images" => @$sale_info[$i]["images"],
                        "ProductInSale_ID" => @$sale_info[$i]["ProductInSale_ID"],
                        "product_ID" => @$sale_info[$i]["product_ID"],
                        "product_name" => @$sale_info[$i]["product_name"],
                        "product_Qty" => @$sale_info[$i]["qnty"]
                    );
                }
            }
            array_push($product_ID_arr, $temp);
        }
        return $product_ID_arr;
    }


    ?>
</body>

</html>