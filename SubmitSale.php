<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Sale</title>
    <link rel="stylesheet" href="SubmitSale.css">
</head>

<body>
    <ul>

        <li><a href="Menu.php">Home</a></li>
        <!-- <li><a href="#news">News</a></li>
        <li><a href="#contact">Contact</a></li>
        <li style="float:right"><a class="active" href="#about">About</a></li> -->
    </ul>
    <?php require_once("DBconnect.php"); ?>
    <div>Sale ID: <?php echo Get_Last_SaleID($conn); ?></div>
    <?php


    if (isset($_POST["AddSaleRecord"])) {

        session_start();

        $sale_date = $_POST["date"];
        $sale_time = $_POST["time"];
        $sale_price = $_POST["total"];

        $sale_submit = InsertSaleTable($sale_date, $sale_time, $sale_price, $conn);
        echo $sale_submit . "<br>";

    ?>
        <table style="width:100%;">
            <tr>
                <td class="head_table product_search_textInput">Item Name</td>
                <td class="head_table">Quantity</td>
                <td class="head_table">Price</td>
                <td class="head_table">Total</td>
                <td></td>
            </tr>
            <?php
            if (!empty($_SESSION["shopping_cart"])) {
                $sale_ID = Get_Last_SaleID_Current($conn);
                $total = 0;
                // print_r($_SESSION["shopping_cart"]);
                foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            ?>


                    <tr>
                        <td> <?php echo $values["item_name"]; ?> </td>
                        <td> <?php echo $values["item_quantity"]; ?> </td>
                        <td>$<?php echo $values["item_price"]; ?> </td>
                        <td> <?php echo number_format($values["item_quantity"] * $values["item_price"], 2) ?> </td>
                        <!-- <div><input type="submit" name="delete" style="margin-top:5px;" value="Add To Cart"></div> -->
                        <td></td>

                    </tr>

            <?php
                    $total = $total + (number_format($values["item_quantity"] * $values["item_price"], 2));
                    echo InsertSale_ProductTable($conn, $values["item_id"], $sale_ID, $values["item_quantity"]) . "<br>";
                }
            }
            ?>
            <tr>
                <td></td>
                <td></td>
                <td>Total</td>
                <td>$<?php echo number_format($total, 2); ?></td>
                <td></td>
            </tr>
        </table>
        <?php



        ?>
        <form action="CreateSaleProcess.php?action=deletepreviousrecord" method="GET" form="deletepreviousrecord">
            <input type="submit" name="deletepreviousrecord" value="Confirm">
            <div>
                <br>
                <button class="button"><a href="ReviewSale.php">Review Sales</a></button>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <button class="button"><a href="Menu.php">Back to Menu</a></button>
            </div>
        </form>

    <?php
    } else if (isset($_POST["CancelSale"])) {
        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            unset($_SESSION["shopping_cart"][$keys]);
        }
        echo "<script>window.location = \"CreateSale.php\"</script>";
    }


    function InsertSaleTable($sale_date, $sale_time, $sale_price, $conn)
    {
        $query = "INSERT INTO sale (sale_date, sale_time, sale_price)
    VALUES ('$sale_date', '$sale_time', $sale_price)";
        if (mysqli_query($conn, $query)) {

            return  "Records added successfully.";
        } else {
            return "ERROR: Could not able to execute $query. " . mysqli_error($conn);
        }
    }
    function Get_Last_SaleID($conn)
    {
        $query = "SELECT sale_ID FROM sale ORDER BY sale_ID DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        $print_data = mysqli_fetch_row($result);
        return ($print_data[0] + 1);
    }

    function Get_Last_SaleID_Current($conn)
    {
        $query = "SELECT sale_ID FROM sale ORDER BY sale_ID DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        $print_data = mysqli_fetch_row($result);
        return ($print_data[0]);
    }

    function InsertSale_ProductTable($conn, $product_ID, $sale_ID, $sale_PQuantity)
    {
        $query = "INSERT INTO sale_product (product_ID, sale_ID, sale_PQuantity)
    VALUES ($product_ID, $sale_ID, $sale_PQuantity)";
        if (mysqli_query($conn, $query)) {
            return "Records added successfully.";
        } else {
            return "ERROR: Could not able to execute $query. " . mysqli_error($conn);
        }
    }

    ?>

</body>

</html>