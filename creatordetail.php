<?php include('connect.php'); ?>
<?php

$message = "";

$creatorid = "";
$creatorname = "";
$city = "";
$path = "";
$userid = "";

session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if (!isset($_GET['creatorid']) && !isset($_POST['creatorid'])) {
    header("Location: index.php");
}

if (isset($_GET['creatorid'])) {
    $creatorid = $_GET['creatorid'];
}

if (isset($_POST['creatorid'])) {
    $creatorid = $_POST['creatorid'];
}

$userid = $_SESSION['userid'];
$usertype = $_SESSION['usertype'];

$udsql = "select * from `creator` as c JOIN `user` as u ON c.userid = u.userid where creatorid = $creatorid";

$res = mysqli_query($conn, $udsql);

$creator = mysqli_fetch_array($res, MYSQLI_ASSOC);
$count = mysqli_num_rows($res);

if ($count == 0) {
    $message = "No product with this id";
} else {
    $creatorid = $creator['creatorid'];
    $creatorname = $creator['name'];
    $city = $creator['city'];
    $state = $creator['state'];
    $street = $creator['street'];
    $bio = $creator['bio'];

    if (isset($creator['path'])) {
        $path = $creator['path'];
    }


    if (isset($_POST['submit'])) {
        $quantity = $_POST['quantity'];
        $totalprice = $quantity * $city;

        $udregistrationsql = "INSERT INTO `cart` (`cartid`, `creatorid`, `quantity`, `totalprice`, `userid`) VALUES (NULL, $creatorid, '$quantity ', '$totalprice', '$userid')";

        $resReg = mysqli_query($conn, $udregistrationsql);
        $count = mysqli_affected_rows($conn);

        if ($count == 1)
            header("Location: cart.php");
    } else {
        $message = "Add product failed, Please try again.";
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="creator-detail">
            <form id="submit" enctype="multipart/form-data" class="registration-form" name="submit"
                action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <h3>Creator Detail</h3>

                <input type="hidden" name="creatorid" value="<?php echo $creatorid; ?>" />
                <div class="">
                    <div class="creator-detail-item-first">
                        <img style="width:50%;height:auto;" src="<?php echo $path; ?>" /><br /><br />
                    </div>

                    <div>
                        <label for="productname">
                            <?php echo $creatorname; ?>
                        </label><br /><br />
                        <label for="bio"><i>"
                                <?php echo $bio; ?>"
                            </i></label><br /><br />

                        <label for="street">Street:
                            <?php echo $street; ?>
                        </label><br /><br />

                        <label for="city">City:
                            <?php echo $city; ?>,
                            <?php echo $state; ?>
                        </label><br /><br />

                        <!-- submit and reset form -->

                        <a href="calendar.php?creatorid=<?php echo $creatorid; ?>" class="link-button" name="submit"
                            value="submit">Book A
                            Creator</a>

                    </div>
                </div>
        </div>
        </form>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>