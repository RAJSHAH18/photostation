<?php include('connect.php'); ?>
<?php

$message = "";


session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if ($_SESSION['usertype'] != "customer") {
    header("Location: login.php");
}

if (!isset($_GET['creatorid'])) {
    header("Location: index.php");
}

if (isset($_GET['creatorid'])) {
    $creatorid = $_GET['creatorid'];
}

$userid = $_SESSION['userid'];
$usertype = $_SESSION['usertype'];


if (isset($_POST['submit'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $udregistrationsql = "INSERT INTO `rating` (`creatorid`, `userid`, `rating`, `comment`) VALUES ('$creatorid', '$userid', '$rating', '$comment')";

    $resReg = mysqli_query($conn, $udregistrationsql);
    $count = mysqli_affected_rows($conn);

    if ($count == 1) {
        // header('Location: payment.php');
    } else {
        $message = "Payment failed, Please try again.";
    }

}


// //User row
$udsql = "SELECT * FROM `rating` WHERE userid = $userid and creatorid = $creatorid";

$resuser = mysqli_query($conn, $udsql);
$rating = mysqli_fetch_array($resuser, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="container  w-50">
            <h1 class="text-center">Rate Creator</h1>
            <div class="container">
                <form method="post" action="feedback.php?creatorid=<?php echo $creatorid; ?>">
                    <label for="rating">Your Rating:
                        <?php echo $rating["rating"]; ?>
                    </label>
                    <br />
                    <?php echo $rating["comment"]; ?>

                    <?php if ($rating == null || $rating == ""): ?>
                        <div class="form-group mb-2">

                            <select class="form-control" id="rating" name="rating">
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="commentInput">Leave a Comment:
                            </label>
                            <textarea name="comment" class="form-control" id="commentInput" rows="3"></textarea>
                        </div>
                        <br />

                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </main>
    <?php include_once("footer.php") ?>
    <script src="site.js"></script>

</body>

</html>