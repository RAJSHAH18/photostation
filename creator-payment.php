<?php include('connect.php'); ?>
<?php
session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if ($_SESSION['usertype'] != "creator") {
    header("Location: login.php");
}

$message = "";

$userid = "";
$userid = $_SESSION['userid'];
$usertype = $_SESSION['usertype'];

$udsql = "SELECT * from `payment` as p JOIN `booking` as b ON p.bookingid = b.bookingid
        JOIN `creator` as c ON b.creatorid = c.creatorid
        JOIN `user` as u ON c.userid = u.userid 
     where u.userid = '$userid' ORDER BY p.paymentid DESC";

$res = mysqli_query($conn, $udsql);

$orders = mysqli_fetch_all($res, MYSQLI_ASSOC);
$count = mysqli_num_rows($res);

$udsql = "SELECT AVG(r.rating) as rating FROM rating as r
                JOIN `creator` as c ON r.creatorid = c.creatorid WHERE c.userid = $userid";

$res = mysqli_query($conn, $udsql);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
$count = mysqli_num_rows($res);


if ($count < 0) {
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
            <h4 class="text-center">Your Invoice </h4>
            <h6 class="text-center text-primary"><i>Your Average Rating is <?php echo $row["rating"]; ?> </i></h6>
            <table>
                <tr id="t01">
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>

                <?php if ($message != ""): ?>
                    <tr>
                        <p style="color:red; text-align:center;">Warning:-
                            <?php echo $message; ?>
                        </p>
                    </tr>
                <?php endif; ?>
                <?php foreach ($orders as $order): ?>

                    <tr>
                        <td>
                            <?php echo $order['startdate']; ?>
                        </td>
                        <td>
                            <?php echo $order['enddate']; ?>
                        </td>
                        <td>
                            <?php echo $order['status']; ?>
                        </td>
                        <td>
                            <?php echo $order['total']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>