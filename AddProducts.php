<?php
include_once("DBconnect.php");
// Create database connection
//$db = mysqli_connect("localhost", "u596909339_MSG", "PHPmsg#12345", "u596909339_PHP_DB");
//$db = mysqli_connect("localhost", "root", "", "msp");
// Initialize message variable
$msg = "";

// If upload button is clicked ...
if (isset($_POST["upload"])) {
  // Get image name
  $image = $_FILES['images']['name'];

  //product name
  $Pname = $_POST["newproduct"];
  //Product description
  $Pdesc = $_POST["pdesc"];
  //Product price
  $Pprice = $_POST["pprice"];
  // Get text
  //$image_text = mysqli_real_escape_string($db, $_POST['image_text']);

  // image file directory
  $target = "images/" . basename($image);

  $sql = "INSERT INTO product (images,product_name,product_desc,Product_price) VALUES ('$image', '$Pname','$Pdesc','$Pprice')";
  // execute query
  mysqli_query($conn, $sql);

  if (move_uploaded_file($_FILES['images']['tmp_name'], $target)) {
    $msg = "Data uploaded successfully";
    echo "<script type='text/javascript'>alert('$msg');</script>";
  } else {
    $msg = "Failed to upload Data";
    echo "<script type='text/javascript'>alert('$msg');</script>";
  }
}
$result = mysqli_query($conn, "SELECT * FROM product");


?>
<!DOCTYPE html>
<html>
<center>

  <head>
    <title>Add Product</title>
    <!--styling for image box-->
    <style type="text/css">
      #content {
        width: 50%;
        margin: 20px auto;
        border: 1px solid #cbcbcb;
      }

      body {
        font-size: 1.15em;
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
    </style>

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
        background-color: #333 border bold;
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

      form {
        width: 100%;
      }

      .form_txt_input {
        width: 90%;
        height: 3em;
        border-radius: 5px;
        display: inline-block;
        border: grey;
        line-height: inherit;
        font-size: 2em;
        /* height: 1rem; */
      }

      .form_txt_input_2 {
        width: 90%;
        height: 10em;
        border-radius: 5px;
        display: inline-block;
        border: grey;
        line-height: inherit;
        font-size: 1em;
      }
    </style>
    <!--end of footer styling-->

  </head>

  <body>
    <ul>
      <li><a href="Menu.php">Menu</a></li>
    </ul>

    <div id="content">
      <form method="POST" action="#" enctype="multipart/form-data">

        <label for="newproduct">Enter new product:</label><br>
        <input type="text" class="form_txt_input" id="newproduct" name="newproduct" required="required"><br><br>
        <!-- // product ID is auto increment -->
        <!-- product description ID is pdesc -->
        <label for="pdesc">Enter product description:</label><br>
        <textarea class="form_txt_input_2" id="pdesc" name="pdesc" required="required"></textarea><br><br>
        <label for="pprice">Enter product price:</label><br>
        <input type="text" class="form_txt_input" id="pprice" name="pprice" required="required"><br><br>
        <!-- image upload -->
        <input type="file" name="images" id="inpFile">
        <!-- image display -->

        <div class="image-preview" id="imagePreview">

          <img src="" alt="Image preview" class="image-preview__image">
          <span class="image-preview__default-text">Image preview</span>

        </div><br>
        <!-- button submit and reset -->
        <div>
          <button type="submit" name="upload">Submit</button>
          <button type="reset" value="Reset">Reset</button>
        </div>
      </form>
    </div>
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