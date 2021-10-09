<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ReviewSale1.css">
    <title>Review Sale</title>

</head>

<body>
    <ul>
        <li><a href="Menu.php">Menu</a></li>

    </ul>
    <form action="ReviewSale.php" method="POST" name="ReviewSale">
        <div>
            <span>
                <input type="text" name="sale_ID" placeholder="Sale ID">
            </span>
            <span>
                <button type="submit" name="SaleIDSearch" class="button">Search</button>
            </span>
        </div>
        <div>
            <span>
                <input type="date" name="date" placeholder="Date">
            </span>
            <span>
                <button type="submit" name="DateSearch" class="button">Search</button>
            </span>
        </div>
    </form>
    <?php
    include("DBconnect.php");
    session_start();
    ?>
    <table style="width:100%;">
        <tr>
            <td>Sale ID</td>
            <td>Date</td>
            <td>Time</td>
            <td>Product</td>
            <td>Total</td>
            <td>Edit</td>

        </tr>
        <?php
        if (isset($_POST["SaleIDSearch"])) {
            if (isset($_POST["sale_ID"])) {
                include("DBconnect.php");
                $sale_ID = trim($_POST["sale_ID"]);
                $SESSION["sale_record_by_saleID"] = RetriveDataBySaleID($sale_ID, $conn);
                $SESSION["Searched_Product_Info"] =  SaleDataPrep($SESSION["sale_record_by_saleID"]);
                PrintDataBySaleID($SESSION["sale_record_by_saleID"],  $SESSION["Searched_Product_Info"]);
            } else {
                $product_error = "<p style=\"color:red;\">No Product Name Input</p>";
            }
        } else if (isset($_POST["DateSearch"])) {
            if (isset($_POST["date"])) {
                include("DBconnect.php");
                $date = trim($_POST["date"]);
                $date = date_create($date);
                $date = date_format($date, "d-m-Y");
                $saleID_dateSearch = RetriveDataByDate($date, $conn);

                for ($i = 0; $i < count($saleID_dateSearch); $i++) {
                    include("DBconnect.php");
                    $allSaleUDate = RetriveDataBySaleID($saleID_dateSearch[$i], $conn);
                    $allProductSaleUDate = SaleDataPrep($allSaleUDate);
                    PrintDataBySaleID($allSaleUDate,  $allProductSaleUDate);
                }
            } else {
                $product_error = "<p style=\"color:red;\">No Product Name Input</p>";
            }
        }
        ?>


    </table>
    <div><button class=button><a href="Menu.php">Back to Menu</a></button></div>


    <?php
    function RetriveDataBySaleID($sale_ID, $conn)
    {
        $query = "SELECT sale_product.sale_ID, sale.sale_date, sale.sale_time,sale_product.product_ID ,product.product_name,sale_product.sale_PQuantity ,sale.sale_price,product.images  
        FROM sale_product 
        INNER JOIN sale
        ON sale.sale_ID = sale_product.sale_ID
        INNER JOIN product
        ON sale_product.product_ID = product.product_ID
        WHERE sale_product.sale_ID = $sale_ID";
        $result = mysqli_query($conn, $query);
        $sale_info = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sale_info_new[] = array(

                    "sale_ID" => @$row["sale_ID"],
                    "sale_date" => @$row["sale_date"],
                    "sale_time" => @$row["sale_time"],
                    "product_ID" => @$row["product_ID"],
                    "product_name" => @$row["product_name"],
                    "images" => @$row["images"],
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
                        "images" => @$sale_info[$i]["images"],
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
    function PrintDataBySaleID($arrSale, $arrProduct)
    {
    ?>
        <tr>
            <td><?php echo $arrSale[0]["sale_ID"]; ?></td>
            <td><?php echo $arrSale[0]["sale_date"]; ?></td>
            <td><?php echo $arrSale[0]["sale_time"]; ?></td>
            <td>
                <table style="width:80%;">
                    <!-- <tr>
                        <td>Picture</td>
                        <td>Name</td>
                        <td>Quantity</td>
                    </tr> -->
                    <?php foreach ($arrProduct as $row) { ?>
                        <tr>
                            <td><img src="images/<?php echo $row["images"]; ?>" alt="Product Picture"></td>
                            <td><?php echo $row["product_name"]; ?></td>
                            <td><?php echo $row["product_Qty"]; ?></td>
                        </tr>
                    <?php } ?>
                    <?php ?>
                </table>
            </td>
            <td><?php echo $arrSale[0]["total"]; ?></td>
            <td><button class="button"><a href="ReturnedSale.php?action1=edit&idEdit=<?php echo $arrSale[0]["sale_ID"]; ?>" value="Edit">Edit</a></button></td>
        </tr>

        <?php
        // }
        ?>
    <?php
    }
    function RetriveDataByDate($date, $conn)
    {
        $query = "SELECT sale_ID FROM sale WHERE sale_date = '$date'";
        $result = mysqli_query($conn, $query);
        $Xa = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sale_id_new[] = array(
                    "sale_ID" => @$row["sale_ID"]
                );
            }
        } else {
            echo "No Match";
            return null;
        }
        foreach ($sale_id_new as $x) {
            array_push($Xa, $x["sale_ID"]);
        }
        return ($Xa);
    }
    function RetriveDataByDateTime()
    {
        # code...
    }
    ?>

</body>

</html>