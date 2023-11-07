<?php include('connect.php'); ?>
<?php
session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if ($_SESSION['usertype'] != "customer") {
    header("Location: login.php");
}

$message = "";

$userid = $_SESSION['userid'];

$udsql = "SELECT *, b.bookingid as bid FROM `booking` as b JOIN `creator` as c ON b.creatorid = c.creatorid LEFT JOIN `payment` as p ON b.bookingid = p.bookingid WHERE b.userid = $userid order by b.bookingid desc";

$res = mysqli_query($conn, $udsql);
$bookings = mysqli_fetch_all($res, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="p-5">
            <h4 class="text-center">Your Bookings </h4>
            <table>
                <tr id="t01">
                    <th>Image</th>
                    <th>Name</th>
                    <th>Services</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Cost(Excl. Tax)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if ($message != ""): ?>
                    <tr>
                        <p style="color:red; text-align:center;">Warning:-
                            <?php echo $message; ?>
                        </p>
                    </tr>
                <?php endif; ?>
                <?php foreach ($bookings as $item): ?>

                    <?php
                    $bookingid = $item['bookingid'];
                    $udsql = "select s.name as name from `bookingservice` as bs JOIN `service` as s ON bs.serviceid = s.serviceid where bs.bookingid = '$bookingid'";
                    $res = mysqli_query($conn, $udsql);
                    $serviceNames = mysqli_fetch_all($res, MYSQLI_ASSOC);


                    $selectedservices = "";

                    if ($bookings != null) {
                        $sarray = [];
                        foreach($serviceNames as $name)
                        {
                            $stringservice = $name['name'];
                            array_push($sarray, $stringservice);
                        }
                        $selectedservices = implode(',', $sarray);
                    }
                    ?>
                    <tr>
                        <td width="10%"><img style="width: 150px;height:auto;" src="<?php echo $item['path']; ?>"></td>
                        <td>
                            <?php echo $item['name']; ?>
                        </td>
                        <td>
                            <?php echo $selectedservices; ?>
                        </td>
                        <td>
                            <?php echo $item['startdate']; ?>
                        </td>
                        <td>
                            <?php echo $item['enddate']; ?>
                        </td>
                        <td>
                            $<?php echo $item['amount']; ?>
                        </td>
                        <td>
                            <?php echo $item['status']; ?>
                        </td>
                        <td>
                            <?php if($item['status'] == "approved" && $item["paymentid"] == null): ?>
                                <a href="booking.php?bookingid=<?php echo $item['bid']; ?>" class="link-button">Payment</a>
                            <?php elseif($item["paymentid"] != null): ?>
                                <p>Payment Done</p>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>