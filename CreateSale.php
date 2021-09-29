<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP-Create New Sale</title>
    <link rel="stylesheet" href="createsale.css">
</head>

<body>

    <ul>
        <li><a href="Menu.php">Menu</a></li>
        <!--<li><a href="#news">News</a></li>-->
        <!--<li><a href="#contact">Contact</a></li>-->
        <!--<li style="float:right"><a class="active" href="#about">About</a></li>-->
    </ul>
    <?php
    require_once "DBconnect.php";
    ?>
    <center>
        <div>Sale ID: <?php echo Get_Last_SaleID($conn); ?></div>

        <div>
            <form action="CreateSaleProcess.php" method="POST" name="CreateSaleForm">
                <span>
                    <input type="text" name="product" placeholder="product">
                </span>
                <span>
                    <button class="button" type="submit" name="productSearch">Search</button>
                </span>
                <div id="RevSale">
                    <button class="button"> <a href="ReviewSale.php">Review Sales</a></button>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <button class="button"><a href="Menu.php">Back to Menu</a></button>
                </div>



            </form>
        </div>
    </center>
    <?php
    function Get_Last_SaleID($conn)
    {
        $query = "SELECT sale_ID FROM sale ORDER BY sale_ID DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        $print_data = mysqli_fetch_row($result);
        return ($print_data[0] + 1);
    }
    ?>
</body>

</html>