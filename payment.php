<?php include('connect.php'); ?>
<?php 
 session_start();
 if(!isset($_SESSION['usertype'])){
    header("Location: login.php");
  }
  
  if($_SESSION['usertype'] == "admin"){
      header("Location: login.php");
  }
	
    $message = "";
    
    $userid = "";
	$userid =  $_SESSION['userid'];
    $usertype =  $_SESSION['usertype'];

    $udsql = "select * from `payment` as p JOIN `booking` as b ON p.bookingid = b.bookingid where p.userid = '$userid' ORDER BY p.paymentid DESC";

    $res = mysqli_query($conn,$udsql);

    $orders = mysqli_fetch_all($res,MYSQLI_ASSOC);
    $count = mysqli_num_rows($res);

    if($count < 0){
        $message = "No orders available";
    }
    
?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="p-5">
        <table>
                <tr id="t01">
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Rate</th>
                </tr>

                <?php if($message != ""): ?>
                    <tr>
	                <p style="color:red; text-align:center;">Warning:-<?php echo $message; ?></p>
                    </tr>
	            <?php endif; ?>
                <?php foreach($orders as $order): ?>

                    <tr>
                        <td><?php echo $order['startdate']; ?></td>
                        <td><?php echo $order['enddate']; ?></td>
                        <td><?php echo $order['status']; ?></td>
                        <td>$<?php echo $order['total']; ?></td>
                        <td><a href="feedback.php?creatorid=<?php echo $order['creatorid']; ?>">Rate Creator</a></td>
                    </tr>
                <?php endforeach; ?>
        </table>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>