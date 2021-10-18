<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CreateSaleProcess.css">
    <title>Create Sale Process</title>
</head>

<body>
    <ul>
        <li><a href="Menu.php">Home</a></li>
    </ul>
    <center>
        <?php
        require_once("DBconnect.php");
        ?>
        <div>Sale ID: <?php echo Get_Last_SaleID($conn); ?></div>
        <form action="CreateSaleProcess.php" method="POST" name="CreateSaleForm">
            <span>
                <input type="text" name="product" placeholder="product">
            </span>
            <span>
                <button type="submit" name="productSearch" class=button>Search</button>
            </span>
        </form>
        
        <?php
        $result = retriveDataFrDB_Product($conn);
        if (isset($_POST["productSearch"])) {
            if (isset($_POST["product"])) {
                $productName = $_POST["product"];
                $_SESSION["result"] = SearchByProductName($productName, $result);
            } else {
                if (!isset($styleDisappear)) {
                    $product_error = "<p style=\"color:red;\">No Product Name Input</p>";
                }
            }
        }
        foreach ($_SESSION["result"] as $row) {
            // $row["product_name"] = str_replace(" ", "_", $row["product_name"]);

        ?>
            <fieldset>

                <form action="CreateSaleProcess.php" method="post" form="afterSearch">
                    <div>
                        <img src="images/<?php echo $row["images"]; ?>" alt="Product Picture">
                    </div>
                    <div> Product Name: <?php echo $row["product_name"]; ?></div>
                    <div><input type="hidden" name="hidden_name" placeholder="Product Name" value="<?php echo $row["product_name"]; ?>"> </div>
                    <div> Product ID: <?php echo $row["product_ID"]; ?></div>
                    <div><input type="hidden" name="hidden_id" placeholder="Product ID" value="<?php echo $row["product_ID"]; ?>"> </div>
                    <div> Product Price: <?php echo $row["Product_price"]; ?></div>
                    <div><input type="hidden" name="hidden_price" placeholder="Price" value="<?php echo $row["Product_price"]; ?>"> </div>
                    <div><input type=" number" name="quantity" placeholder="Quantity" value="1"> </div>
                    <div><input type="submit" name="add_to_cart" style="margin-top:5px;" value="Add To Cart"></div>

                </form>
            </fieldset>
        <?php

        }

        if (isset($_POST["add_to_cart"])) {
            session_start();
            // echo "<div>\$_POST[\"hidden_name\"] =" . $_POST["hidden_name"] . "</div>";
            if (isset($_SESSION["shopping_cart"])) {
                $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
                if (!in_array($_POST["hidden_id"], $item_array_id)) {
                    $count = count($_SESSION["shopping_cart"]);
                    $item_array = array(
                        "item_id" => @$_POST["hidden_id"],
                        "item_name"  => @$_POST["hidden_name"],
                        "item_price" => @$_POST["hidden_price"],
                        "item_quantity" => @$_POST["quantity"]
                    );
                    $_SESSION["shopping_cart"][$count] = $item_array;
                } else {
                    echo " <script>alert(\"Item Already Added\")</script>";
                    echo "<script>window.location = \"CreateSaleProcess.php\"</script>";
                }
            } else {
                $item_array = array(
                    "item_id" => @$_POST["hidden_id"],
                    "item_name"  => @$_POST["hidden_name"],
                    "item_price" => @$_POST["hidden_price"],
                    "item_quantity" => @$_POST["quantity"]
                );
                $_SESSION["shopping_cart"][0] = $item_array;
            }
        }

        if (isset($_GET["action"]) == "delete") {

            foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                if ($values["item_id"] == $_GET["id"]) {
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo " <script>alert(\"Item Removed\")</script>";
                    echo "<script>window.location = \"CreateSaleProcess.php\"</script>";
                }
            }
        }

        if (isset($_GET["deletepreviousrecord"])) {
            // $submit_re = InsertSaleTable($date, $timeToday, $total, $conn);
            foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                unset($_SESSION["shopping_cart"][$keys]);
            }
            echo "<script>window.location = \"CreateSale.php\"</script>";
        }





        ?>
        <table style="width:100%;">
            <tr>
                <td class="head_table product_search_textInput">Item Name</td>
                <td class="head_table">Quantity</td>
                <td class="head_table">Price</td>
                <td class="head_table">Total</td>
                <td class="head_table">Delete</td>
            </tr>
            <?php
            if (!empty($_SESSION["shopping_cart"])) {
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
                        <td><a href="CreateSaleProcess.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span>Remove</span></a></td>
                    </tr>
            <?php
                    $total = $total + (number_format($values["item_quantity"] * $values["item_price"], 2));
                }
            }
            date_default_timezone_set("Australia/Victoria");
            $date = date("Y-m-d");
            $timeToday = date("h:i a");


            ?>

            <tr>
                <td></td>
                <td></td>
                <td>Total</td>
                <td>$<?php echo number_format($total, 2); ?></td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <!-- <form action="CreateSaleProcess.php?action=SubmitSale" method="post" form="SubmitSale"> -->
                    <form action="SubmitSale.php" method="post" form="SubmitSale">
                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                        <input type="hidden" name="time" value="<?php echo $timeToday; ?>">
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <!-- <a href="CreateSaleProcess.php?action=SubmitSale"><span>Remove</span></a> -->
                        <input type="submit" name="AddSaleRecord" style="margin-top:5px;" value="Record Sale">
                        <input type="submit" name="CancelSale" style="margin-top:5px;" value="Cancal Sale">
                        <!-- <div><input type="submit" name="SubmitSale" style="margin-top:5px;" value="Submit Sale or Remove All Selected Products"></div> -->
                    </form>
                </td>
            </tr>

        </table>

        <?php



        function SearchByProductName($Input, $result)
        {

            $Input = explode(" ", $Input);
            $product_info = array();
            foreach ($result as $row) {
                $WordsinPrName = explode(" ",  $row["product_name"]);
                foreach ($WordsinPrName as $row2) {
                    if (in_array($row2, $Input)) {
                        $product_info_new[] = array(
                            "images" => @$row["images"],
                            "product_name" => @$row["product_name"],
                            "product_ID" => @$row["product_ID"],
                            "Product_price" => @$row["Product_price"]
                        );
                        $product_info = $product_info + $product_info_new;
                    } else {
                        $product_info = $product_info;
                    }
                }
            }
            return $product_info;
        }

        function retriveDataFrDB_Product($conn)
        {
            $query = "SELECT product_ID, product_name,Product_price,product_desc,images FROM product";
            $result = mysqli_query($conn, $query);
            $product_info = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_info_new[] = array(
                        "images" => @$row["images"],
                        "product_name" => @$row["product_name"],
                        "product_ID" => @$row["product_ID"],
                        "Product_price" => @$row["Product_price"]
                    );
                    $product_info = $product_info + $product_info_new;
                    // $product_info_arrPrint = DataforPrintArr($product_info);
                }
            } else {
                echo "0 results";
                return null;
            }
            $conn->close();
            return $product_info;
        }

        function Get_Last_SaleID($conn)
        {
            $query = "SELECT sale_ID FROM sale ORDER BY sale_ID DESC LIMIT 1";
            $result = mysqli_query($conn, $query);
            $print_data = mysqli_fetch_row($result);
            return ($print_data[0] + 1);
        }

        ?>
    </center>
</body>

</html>