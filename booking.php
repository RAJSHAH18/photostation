<?php include('connect.php'); ?>
<?php 

 $message = "";

 $productid = "";
 $bookingid = "";
 $cardnumber = "";
 $cardname = "";
 $cvc = "";
 $type = "";

 session_start();
 if(!isset($_SESSION['usertype'])){
    header("Location: login.php");
}

if($_SESSION['usertype'] != "customer"){
    header("Location: login.php");
}

  if(!isset($_GET['bookingid']) && !isset($_POST['bookingid'])){
    header("Location: index.php");
  }

  if(isset($_GET['bookingid']) ){
    $bookingid = $_GET['bookingid'];
  }
    $userid = "";
    $userid =  $_SESSION['userid'];

    //User row
    $udsql = "SELECT * FROM `booking` as b JOIN `creator` as c ON b.creatorid = c.creatorid WHERE b.bookingid = $bookingid order by b.bookingid desc";

    $resuser = mysqli_query($conn,$udsql);
    $booking = mysqli_fetch_array($resuser,MYSQLI_ASSOC);
    $booking_amount = $booking["amount"];
    $tax = 0.15 * $booking_amount;
    $comission = 0.10 * $booking_amount;

    $total_amount = $booking_amount + $tax + $comission;

   // $udsqlf = "select * as name from `bookingservice` as bs JOIN `service` as s ON bs.serviceid = s.serviceid where bs.bookingid = '$bookingid'";
   // $ress = mysqli_query($conn, $udsqlf);
   // $services = mysqli_fetch_all($ress, MYSQLI_ASSOC);
	


        if(isset($_POST['submit']))
        {
            $cardnumber = $_POST['cardnumber'];
            $cardname = $_POST['cardname'];
            $cvc = $_POST['cvc'];
            $type = $_POST['type'];

            $udregistrationsql = "INSERT INTO `payment` (`bookingid`, `userid`, `cardnumber`, `cardname`, `cvc`, `type`, `total`) VALUES ('$bookingid', '$userid', '$cardnumber', '$cardname', '$cvc', '$type', $total_amount)";
                
            $resReg = mysqli_query($conn,$udregistrationsql);
            $count = mysqli_affected_rows($conn);
            
            if($count == 1)
            {
                header('Location: payment.php');
            } else {
                $message = "Payment failed, Please try again.";
            }
            
        }
    
?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
<?php include_once("header.php"); ?>
    <main>
        <div class="">
        <form id="submit"  enctype="multipart/form-data" class="registration-form" name="submit" action="booking.php?bookingid=<?php echo $bookingid; ?>" method="POST" onSubmit="paymentSubmit(event)"> 
            <h3>Order Detail</h3>

            <?php if($message != ""): ?>
	        <p style="color:red; text-align:center;">Warning:-<?php echo $message; ?></p>
	        <?php endif; ?>

             <input type="hidden" name="bookingid" value="<?php echo $bookingid; ?>" />

             <label for="productname"><?php echo $booking["name"]; ?></label><br /><br />
     
             <label for="price">Order Amount: <?php echo $booking_amount; ?></label><br /><br />
             <label for="price">Vat Tax: <?php echo $tax; ?></label><br /><br />
             <label for="price">Service Charge: <?php echo $comission; ?></label><br /><br />
             <label for="price">Total: <?php echo $total_amount; ?></label><br /><br />

             <label for="cardnumber">Card Number: </label><br /><br />
             <input type="number" id="cardnumber" name="cardnumber" value=""><br /><br />

             <label for="cardname">Card Name: </label><br /><br />
             <input type="text" id="cardname" name="cardname" value=""><br /><br />

             <label for="cvc">CVC: </label><br /><br />
             <input type="number" id="cvc" name="cvc" value=""><br /><br />

             <label for="type">Card Type: </label><br /><br />
             <input type="text" id="type" name="type" value=""><br /><br />

             <!-- submit and reset form -->
             <button type="submit" name="submit" value="submit">Payment</button>

         </form>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>