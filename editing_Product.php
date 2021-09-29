<?php
require_once("DBconnect.php"); //connected the database



//check if the connection is successful
if (!$conn) {
  //display error message
  echo "<p>Database connection failure</p>";
} else {

  if (isset($_GET["product_ID"]) && !empty(trim($_GET["product_ID"]))) {
    // Get URL parameter
    $product_ID =  trim($_GET["product_ID"]);
    $sql = "SELECT * FROM product WHERE product_ID =$product_ID;";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
      /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      // Retrieve individual field value
      $product_name = $row["product_name"];
      $product_desc = $row["product_desc"];
      $Product_price = $row["Product_price"];
    }
    //echo $product_ID;

  }


  //upon successful connect
  if (isset($_POST["Changebar"])) {
    //sanitize the data of product_ID


    //sanitize the data of product_name
    $product_name = trim($_POST["product_name"]);
    $product_name = htmlspecialchars($_POST["product_name"]);

    //sanitize the data of product_desc
    $product_desc = trim($_POST["product_desc"]);
    $product_desc = htmlspecialchars($_POST["product_desc"]);

    //sanitize the data of Product_price
    $Product_price = trim($_POST["Product_price"]);
    $Product_price = htmlspecialchars($_POST["Product_price"]);

    //image 
    $images = $_POST['images']; //image1.png

    //echo "<script type='text/javascript'>alert('$images');</script>";

    //set up the SQL command to query or adddata into the table
    $query = "update product SET product_name='$product_name' where product_ID='$product_ID'";

    ////set up the SQL command to query or adddata into the table
    $query1 = "update product SET product_desc='$product_desc' where product_ID='$product_ID'";

    ////set up the SQL command to query or adddata into the table
    $query2 = "update product SET Product_price='$Product_price' where product_ID='$product_ID'";

    //set up the SQL command to query or adddata into the table
    $query3 = "update product SET images='$images' where product_ID='$product_ID'";
    //execute the query and store result into the result pointer
    $result = mysqli_query($conn, $query);
    $result1 = mysqli_query($conn, $query1);
    $result2 = mysqli_query($conn, $query2);
    $result3 = mysqli_query($conn, $query3);
    //check if the execution is successful

    if (!$result) {
      echo "<p>Something is wrong with ", $query, "</p>";
    } else {

      echo "<script>window.location = \"productVIew.php\"</script>";
    }
  }
}
?>

<!-- //html for change section -->
<!DOCTYPE html>
<html>

<head>
  <center>
    <title></title>
    <!--style the navigation bar-->
    <style>
      ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #333;

      }

      li {
        float: left;
      }

      li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
      }

      li a:hover:not(.active) {
        background-color: #111;
      }

      .active {
        background-color: #04AA6D;
      }
    </style>
    <!--End of styling-->
    <style>
      button {
        background-color: #32a887 border none;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
      }

      .btn {
        background-color: #32a887 border none;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
      }
    </style>


    <!--Footer styling-->
    <style>
      .all-browsers {
        margin: 0;
        padding: 5px;
        background-color: lightgray;
      }

      .all-browsers>h1,
      .browser {
        margin: 10px;
        padding: 5px;
      }

      .browser {
        background: white;
      }

      .browser>h2,
      p {
        margin: 4px;
        font-size: 90%;
      }

      footer {
        text-align: center;
        padding: 3px;
        background-color: #333;
        color: white;
      }
    </style>
    <!--end of footer styling-->
    <style>
      #content {
        width: 50%;
        margin: 20px auto;
        border: 1px solid #cbcbcb;
      }

      .image-preview {
        width: 300px;
        min-height: 100px;
        border: 3px solid #dddddd;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: lightblue;
      }

      .image-preview__image {
        display: none;
      }

      img {
        width: 300px;
      }

      #view {
        text-decoration: none;
      }

      .form_txt_input {
        width: 90%;
        height: 1.5em;
        border-radius: 5px;
        display: inline-block;
        border: grey;
        line-height: inherit;
        font-size: 1.5em;
        /* height: 1rem; */
      }

      .form_txt_input_2 {
        width: 90%;
        height: 8em;
        border-radius: 5px;
        display: inline-block;
        border: grey;
        line-height: inherit;
        font-size: 1em;
      }
    </style>
</head>

<body>
  <ul>
    <li><a href="Menu.php">Menu</a></li>

    <!-- <li style="float:right"><a class="active" href="#about">About</a></li> -->
  </ul>
  <form method="post" action="editing_Product.php">
    <fieldset>
      <legend>
        <h3>CHANGE DATA</h3>
      </legend>

      <label for="product_name">PRODUCT NAME:</label>
      <input type="text" class="form_txt_input" name="product_name" id="product_name" value="<?php echo $product_name; ?>"><br><br>


      <label for="product_desc">PRODUCT DESCRIPTION:</label>
      <textarea type="text" class="form_txt_input_2" name="product_desc" id="Product_desc" value="<?php echo $product_desc; ?>"></textarea><br><br>


      <label for="Product_price">PRODUCT PRICE:</label>
      <input type="text" class="form_txt_input" name="Product_price" id="Product_price" value="<?php echo $Product_price; ?>"><br><br>

      <!-- image upload -->
      <input type="file" name="images" id="inpFile">

      <div class="image-preview" id="imagePreview">

        <img src="" alt="Image preview" class="image-preview__image">
        <span class="image-preview__default-text">Image preview</span>

      </div><br>
      <input class="btn" type="submit" value="Change" name="Changebar" />
      <button class="button"><a href="Product_View.php" id="view">Cancel</a></button>
    </fieldset>
  </form>
  <script>
    const inpFile = document.getElementById("inpFile");
    const previewContainer = document.getElementById("imagePreview");
    const previewImage = previewContainer.querySelector(".image-preview__image");
    const previewDefaultText = previewContainer.querySelector(".image-preview__default-text");

    inpFile.addEventListener("change", function() {
      const file = this.files[0];

      if (file) {
        // FileReader()
        const reader = new FileReader();

        previewDefaultText.style.display = "none";
        previewImage.style.display = "block";

        reader.addEventListener("load", function() {
          previewImage.setAttribute("src", this.result);
        });

        reader.readAsDataURL(file);
      }
    });
  </script>

  <footer>
    Copyright 2021<br>
    Author:group 13<br>
    Email:102579094@student.swin.edu.au
  </footer>
  </center>
</body>

</html>
<!-- change the data section -->