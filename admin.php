<?php include('connect.php'); ?>
<?php 
 session_start();
 if(!isset($_SESSION['usertype'])){
    header("Location: login.php");
  }
  
  if($_SESSION['usertype'] != "Admin"){
      header("Location: login.php");
  }
	
    $message = "";

    if(isset($_POST['submit']))
	{
        $productid = $_POST['productid'];
		$udsql = "delete from `product` where `productid` = $productid";
        $res = mysqli_query($conn,$udsql);
		if($res){
        }
        else{
			$message = "Delete product failed, Please try again.";
        }
			
    }
    
    $userid = "";
	$userid =  $_SESSION['userid'];
    $email =  $_SESSION['email'];
    $usertype =  $_SESSION['usertype'];

    $udsql = "select * from `product`";

    $res = mysqli_query($conn,$udsql);

    $products = mysqli_fetch_all($res,MYSQLI_ASSOC);
    $count = mysqli_num_rows($res);

    if($count < 0){
        $message = "No product available";
    }
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/slideshow.css">
    <link rel="stylesheet" href="CSS/register.css">
    <title>Photostation</title>
</head>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="">
        <table>
                <tr class="table-caption">
                    <caption class="logo-text">List of Products</caption>
                </tr>
                <tr id="t01">
                    <th>Product Id</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>

                <?php if($message != ""): ?>
                    <tr>
	                <p style="color:red; text-align:center;">Warning:-<?php echo $message; ?></p>
                    </tr>
	            <?php endif; ?>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td><?php echo $product['productid']; ?></td>
                        <td><img style="width:20%; height:auto;" src="<?php echo $product['image']; ?>" /></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td>
                            <form id="submit" name="submit" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="productid" value="<?php echo $product['productid']; ?>"> 
                                <input class="btn" type="submit" name="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
        </table>
        <a style="width:10%; margin-left:60px;" class="btn" href="productcreateedit.php">Create</a>

        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>