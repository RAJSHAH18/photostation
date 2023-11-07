<?php include('connect.php'); ?>
<?php
session_start();
$message = "";

$userid = "";
$userid = $_SESSION['userid'];
$email = $_SESSION['email'];
$usertype = $_SESSION['usertype'];

$udsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid WHERE `creatortype` = 'photographer'";

if(isset($_GET["serviceid"])){
    $serviceid = $_GET["serviceid"];
    $udsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid JOIN `creatorservice` as cs ON c.creatorid = cs.creatorid WHERE `creatortype` = 'photographer' AND `serviceid` = $serviceid";
}


$res = mysqli_query($conn, $udsql);
$photographers = mysqli_fetch_all($res, MYSQLI_ASSOC);

$fludsql = "SELECT * FROM `service`";
$flres = mysqli_query($conn, $fludsql);
$services = mysqli_fetch_all($flres, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>

        <div class="discount-section">
            <div class="discount-banner">
                <img src="img/p-banner-page.jpg" width="100%">
            </div>
        </div>

        <div class="other-items clearfix">
            <div style="width:100%">
                <p>FILTER SERVICES</p>
                <?php foreach ($services as $item): ?>
                    <a class="filter-service-link" style="text-transform: uppercase;padding: 10px 20px;color:black;" href="photo.php?serviceid=<?php echo $item["serviceid"]; ?>"><?php echo $item["name"]; ?></a>
                <?php endforeach; ?>
            </div>

            <div class="other-item-header logo-text">
                <p>Book Our
                    <span class="web-text">Exclusive</span> Photographers!
                </p>
            </div>

            <?php foreach ($photographers as $item): ?>
                <div class="other-item item-1">
                    <div class="other-item-image-cover">
                        <img src="<?php echo $item['path']; ?>" alt="Consectetur Hampden">
                        <a href="creatordetail.php?creatorid=<?php echo $item['creatorid']; ?>" class="shop-button">View</a>
                    </div>
                    <p class="other-item-bio">
                        <?php echo $item['bio']; ?>
                    </p>
                    <p class="other-item-username">
                        <?php echo $item['username']; ?>
                    </p>
                    <!-- <a href="creatordetail.php?creatorid=<?php echo $item['creatorid']; ?>" class="shop-button">Shop Now</a> -->
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include_once("footer.php") ?>
</body>

</html>