<?php
require_once("DBconnect.php");//connected the database



//check if the connection is successful
if(!$conn)
{
	//display error message
	echo "<p>Database connection failure</p>";
}else
{

	if(isset($_GET["product_ID"]) && !empty(trim($_GET["product_ID"])))
	{
        // Get URL parameter
        $product_ID =  trim($_GET["product_ID"]);
        $sql="SELECT * FROM product WHERE product_ID =$product_ID;";
		$result=mysqli_query($conn,$sql);

		if(mysqli_num_rows($result) == 1){
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
	if(isset($_POST["Changebar"]))
	{
		//sanitize the data of product_ID
		

		//sanitize the data of product_name
		$product_name=trim($_POST["product_name"]);
		$product_name=htmlspecialchars($_POST["product_name"]);

		//sanitize the data of product_desc
		$product_desc=trim($_POST["product_desc"]);
		$product_desc=htmlspecialchars($_POST["product_desc"]);

		//sanitize the data of Product_price
		$Product_price=trim($_POST["Product_price"]);
		$Product_price=htmlspecialchars($_POST["Product_price"]);

		//image 
		$images = $_POST['images'];//image1.png

		//echo "<script type='text/javascript'>alert('$images');</script>";

		//set up the SQL command to query or adddata into the table
		$query="update product SET product_name='$product_name' where product_ID='$product_ID'";

		////set up the SQL command to query or adddata into the table
		$query1="update product SET product_desc='$product_desc' where product_ID='$product_ID'";

		////set up the SQL command to query or adddata into the table
		$query2="update product SET Product_price='$Product_price' where product_ID='$product_ID'";

		//set up the SQL command to query or adddata into the table
		$query3="update product SET images='$images' where product_ID='$product_ID'";
		//execute the query and store result into the result pointer
		$result=mysqli_query($conn,$query);
		$result1=mysqli_query($conn,$query1);
		$result2=mysqli_query($conn,$query2);
		$result3=mysqli_query($conn,$query3);
		//check if the execution is successful
		
		if(!$result)
		{
			echo "<p>Something is wrong with ",$query, "</p>";
		}else
		{
			
			 echo "<script>window.location = \"productVIew.php\"</script>";
		}

	}
}
?>

<!-- //html for change section -->
<!DOCTYPE html>
<html>
<head>
	<title></title>

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
    
    #view
    {
        text-decoration:none;
    }
</style>
</head>
<body>

<form method="post" action="#">
<fieldset>
<legend><h3>CHANGE DATA</h3></legend>

<label for="product_name">product name:</label> 
<input type="text" name="product_name" id="product_name" value="<?php echo $product_name;?>" ><br><br>


<label for="product_desc">product description:</label> 
<input type="text" name="product_desc" id="Product_desc" value="<?php echo $product_desc;?>"><br><br>


<label for="Product_price">product price:</label> 
<input type="text" name="Product_price" id="Product_price" value="<?php echo $Product_price;?>"><br><br>

<!-- image upload -->
<input type="file" name="images" id="inpFile">

<div class="image-preview" id="imagePreview">

        <img  src="" alt="Image preview" class="image-preview__image"> 
        <span class="image-preview__default-text">Image preview</span>
    
  </div><br>
<input type="submit" value="change" name="Changebar" />
<button><a href="productVIew.php" id="view">Cancel</a></button>
</fieldset>
</form>
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
<!-- change the data section -->


