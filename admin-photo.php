<?php include('connect.php'); ?>
<?php 
 session_start();
 if(!isset($_SESSION['usertype'])){
    header("Location: login.php");
  }
  
  if($_SESSION['usertype'] == "Admin"){
      header("Location: login.php");
  }

  if (isset($_GET["action"]) && isset($_GET["creatorid"])) {
    $action = $_GET["action"];
    $creatorid = $_GET["creatorid"];

    $udregistrationsql = "UPDATE `creator` SET `status` = '$action' WHERE `creatorid` = $creatorid";

    $resReg = mysqli_query($conn, $udregistrationsql);
    // $count = mysqli_affected_rows($conn);

}
	
  $message = "";

  $userid = "";
  $userid =  $_SESSION['userid'];
  $email =  $_SESSION['email'];
  $usertype =  $_SESSION['usertype'];

  $udsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid WHERE `creatortype` = 'photographer' order by createdon desc";
  
  $res = mysqli_query($conn,$udsql);
  $photographers = mysqli_fetch_all($res,MYSQLI_ASSOC);
    
?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="">
        <h3 class="table-heading" style="text-align:center">Registered Photographers</h3>
    <br />
        <table>
                <tr id="t01">
                    <th>Image</th>
                    <th>Name</th>
                    <th>Bio</th>
                    <th>Age</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Status</th>

                    <th>Registered Date</th>
                </tr>

                <?php if($message != ""): ?>
                    <tr>
	                <p style="color:red; text-align:center;">Warning:-<?php echo $message; ?></p>
                    </tr>
	            <?php endif; ?>
                <?php foreach($photographers as $item): ?>

                <?php 
                    //  $cartid = $order['cartid'];
                    //  $udsql = "select * from `cart` where `cartid` = '$cartid'";
                    //  $res = mysqli_query($conn,$udsql);
                    //  $cart = mysqli_fetch_array($res,MYSQLI_ASSOC);

                    //  if($cart != null){
                    //     $productid = $cart['productid'];
                    //     $udsql = "select * from `product` where `productid` = '$productid'";
                    //     $res = mysqli_query($conn,$udsql);
                    //     $product = mysqli_fetch_array($res,MYSQLI_ASSOC);
                    //  }
                ?>
                    <tr>
                        <td width="10%"><img style="width: 150px;;height:auto;" src="<?php echo $item['path']; ?>"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['bio']; ?></td>
                        <td><?php echo $item['age']; ?></td>
                        <td><?php echo $item['city']; ?></td>
                        <td><?php echo $item['state']; ?></td>
                        <td>
                            <?php if ($item['status'] == 'pending'): ?>
                                <a
                                    href="admin-photo.php?action=approved&creatorid=<?php echo $item['creatorid']; ?>">Approve</a>
                                <a
                                    href="admin-photo.php?action=rejected&creatorid=<?php echo $item['creatorid']; ?>">Reject</a>
                            <?php else: ?>
                                <?php echo $item['status']; ?>
                            <?php endif; ?>

                        </td>
                        <td><?php echo $item['createdon']; ?></td>
                       
                    </tr>
                <?php endforeach; ?>
        </table>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>