<?php include('connect.php'); ?>
<?php 
    session_start();
    $message = "";

    $userid = "";
	$userid =  $_SESSION['userid'];
    $email =  $_SESSION['email'];
    $usertype =  $_SESSION['usertype'];

    $udsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid WHERE `creatortype` = 'photographer' LIMIT 3";
    
    $res = mysqli_query($conn,$udsql);
    $photographers = mysqli_fetch_all($res,MYSQLI_ASSOC);

    
    $fludsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid WHERE `creatortype` = 'videographer' LIMIT 3";
    $flres = mysqli_query($conn,$fludsql);
    $videographers = mysqli_fetch_all($flres,MYSQLI_ASSOC);
    
    
?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="other-items clearfix">
            <div class="web-item-header logo-text">
                <p>Our Best Photographers!</p>
            </div>
             
            <?php foreach($photographers as $item): ?>
                <div class="other-item item-1">
                     <div class="other-item-image-cover">
                        <img src="<?php echo $item['path']; ?>" alt="Consectetur Hampden">
                        <a href="creatordetail.php?creatorid=<?php echo $item['creatorid']; ?>" class="shop-button">View</a>

                    
                    </div>
                    <p class="other-item-bio"><?php echo $item['bio']; ?></p>
                    <p class="other-item-username"><?php echo $item['username']; ?></p>
                </div>
            <?php endforeach; ?>
            
        </div>

        <div class="other-items clearfix">
            <div class="web-item-header logo-text">
                <p>Our Best Videographers!</p>
            </div>
             
            <?php foreach($videographers as $item): ?>
                <div class="other-item item-1">
                     <div class="other-item-image-cover">
                        <img src="<?php echo $item['path']; ?>" alt="Consectetur Hampden">
                        <a href="creatordetail.php?creatorid=<?php echo $item['creatorid']; ?>" class="shop-button">View</a>

                    </div>
                    <p class="other-item-bio"><?php echo $item['bio']; ?></p>
                    <p class="other-item-username"><?php echo $item['username']; ?></p>
                </div>
            <?php endforeach; ?>

        </div>

    </main>
    <?php include_once("footer.php") ?>
    <script src="site.js"></script>

</body>

</html>