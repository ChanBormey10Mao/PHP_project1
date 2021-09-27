<?php
include_once("DBconnect.php");
// Create database connection
//$db = mysqli_connect("localhost", "u596909339_MSG", "PHPmsg#12345", "u596909339_PHP_DB");
//$db = mysqli_connect("localhost", "root", "", "msp");
// Initialize message variable
  $msg = "";

// If upload button is clicked ...
  if (isset($_POST["upload"])) 
{
    	// Get image name
    	$image = $_FILES['images']['name'];
    	
  //product name
    $Pname=$_POST["newproduct"];
  //Product description
    $Pdesc=$_POST["pdesc"];
  //Product price
    $Pprice=$_POST["pprice"];
  // Get text
    //$image_text = mysqli_real_escape_string($db, $_POST['image_text']);

  // image file directory
    $target = "images/".basename($image);

    $sql = "INSERT INTO product (images,product_name,product_desc,Product_price) VALUES ('$image', '$Pname','$Pdesc','$Pprice')";
  // execute query
    mysqli_query($conn, $sql);

    if (move_uploaded_file($_FILES['images']['tmp_name'], $target)) 
    {
    	$msg = "Data uploaded successfully";
      echo "<script type='text/javascript'>alert('$msg');</script>";
    }
    else
    {
    	$msg = "Failed to upload Data";
      echo "<script type='text/javascript'>alert('$msg');</script>";
    }
}
  $result = mysqli_query($conn, "SELECT * FROM product");


?>
<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<style type="text/css">
   #content{
   	width: 50%;
   	margin: 20px auto;
   	border: 1px solid #cbcbcb;
   }
   .image-preview
    {
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

    .image-preview__image
    {
        display: none;
    }

    img 
    {
      width: 300px;
    }
</style>


</head>
<body>
<div id="content">
  <form method="POST" action="#" enctype="multipart/form-data">
  <div>
    <label for="newproduct">Enter new product:</label><br>
    <input type="text" id="newproduct" name="newproduct" required="required"><br><br>
<!-- // product ID is auto increment -->
<!-- product description ID is pdesc -->
    <label for="pdesc">Enter product description:</label><br>
    <input type="text" id="pdesc" name="pdesc" required="required"><br><br>
    <label for="pprice">Enter product price:</label><br>
    <input type="text" id="pprice" name="pprice" required="required"><br><br>
<!-- image upload -->
  	  <input type="file" name="images" id="inpFile">
<!-- image display -->
  </div>
  <div class="image-preview" id="imagePreview">

        <img  src="" alt="Image preview" class="image-preview__image"> 
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
    const inpFile=document.getElementById("inpFile");
const previewContainer=document.getElementById("imagePreview");
const previewImage=previewContainer.querySelector(".image-preview__image");
const previewDefaultText= previewContainer.querySelector(".image-preview__default-text");

inpFile.addEventListener("change",function()
{
    const file=this.files[0];

    if (file) 
    {
        // FileReader()
        const reader=new FileReader();

        previewDefaultText.style.display="none";
        previewImage.style.display="block";

        reader.addEventListener("load",function()
        {
            previewImage.setAttribute("src",this.result);
        });

        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html>