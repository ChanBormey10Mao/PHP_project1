<?php
session_start();
include_once'setting.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<br/>
<div class="container" style="width:700px;">
<h1>Inventory</h1><br/> 
<?php
$query="select * from Inventory Order by product_ID ASC";
$result=mysqli_query($conn,$query);
if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_array($result))
    {
?>
<div>
    <form method="post" action="stockmanagement.php?action=add&id=<?php echo $row["id"];?>">
        <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">
    </form>
</div>
<?php   
    }
}
?>
</div>
<br/>

</body>
</html>