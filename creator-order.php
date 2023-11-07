<?php include('connect.php'); ?>
<?php
session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if ($_SESSION['usertype'] != "creator") {
    header("Location: login.php");
}

if (isset($_GET["action"]) && isset($_GET["bookingid"])) {
    $action = $_GET["action"];
    $bookingid = $_GET["bookingid"];

    $udregistrationsql = "UPDATE `booking` SET `status` = '$action' WHERE `bookingid` = $bookingid";

    $resReg = mysqli_query($conn, $udregistrationsql);
    $count = mysqli_affected_rows($conn);

}

$message = "";

$userid = $_SESSION['userid'];

$udsql = "SELECT *, b.bookingid as bid FROM `booking` as b JOIN `creator` as c ON b.creatorid = c.creatorid
            JOIN `user` as u ON b.userid = u.userid
            LEFT JOIN `payment` as p ON b.bookingid = p.bookingid 
 WHERE c.userid = $userid order by b.bookingid desc";

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
                <tr>
                    <th>Name</th>
                    <th>Services</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Cost(Excl. Tax)</th>
                    <th>Status</th>
                    <th>Payment Status</th>
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
                        foreach ($serviceNames as $name) {
                            $stringservice = $name['name'];
                            array_push($sarray, $stringservice);
                        }
                        $selectedservices = implode(',', $sarray);
                    }
                    ?>
                    <tr>
                        <td>
                            <?php echo $item['username']; ?>
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
                            $
                            <?php echo $item['amount']; ?>
                        </td>
                        <td>
                            <?php if ($item['status'] == 'pending'): ?>
                                <a
                                    href="creator-order.php?action=approved&bookingid=<?php echo $item['bid']; ?>">Approve</a>
                                <a
                                    href="creator-order.php?action=rejected&bookingid=<?php echo $item['bid']; ?>">Reject</a>
                            <?php else: ?>
                                <?php echo $item['status']; ?>
                            <?php endif; ?>

                        </td>

                        <td>
                            <?php if ($item['paymentid'] == null): ?>
                                <?php echo "Pending"; ?>
                               
                             <?php else: ?>
                                <?php echo "Done"; ?>
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