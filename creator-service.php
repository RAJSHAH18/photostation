<?php include('connect.php'); ?>
<?php
session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if ($_SESSION['usertype'] != "creator") {
    header("Location: login.php");
}

if (isset($_POST['submit'])) {
    $creatorid = $_POST["creatorid"];
    $serviceid = $_POST["serviceid"];
    $rate = $_POST["amount"];
    $udsql = "select * from `creatorservice` where `creatorid` = $creatorid AND `serviceid` = $serviceid";
    $res = mysqli_query($conn,$udsql);

    $row = mysqli_fetch_array($res,MYSQLI_ASSOC);
    $count = mysqli_num_rows($res);

    if($count == 1) {
        $udregistrationsql = "UPDATE `creatorservice` SET rate = '$rate' WHERE `creatorid` = $creatorid AND `serviceid` = $serviceid";
    
        $resReg = mysqli_query($conn,$udregistrationsql);
        $count = mysqli_affected_rows($conn);
    } else {

        $udregistrationsql = "INSERT INTO `creatorservice` (`creatorid`, `serviceid`, `rate`) VALUES ('$creatorid', '$serviceid', '$rate')";
    
        $resReg = mysqli_query($conn,$udregistrationsql);
        $count = mysqli_affected_rows($conn);
    }
}

$message = "";

$userid = $_SESSION['userid'];



$servicesql = "SELECT *, s.name as servicename FROM `service` as s JOIN `creatorservice` as cs ON s.serviceid = cs.serviceid 
                                            JOIN `creator` as c ON cs.creatorid = c.creatorid
                                            WHERE c.userid = $userid";
$res = mysqli_query($conn, $servicesql);
// $services = mysqli_fetch_array($res, MYSQLI_ASSOC);

$services = mysqli_fetch_all($res, MYSQLI_ASSOC);


$servsql = "SELECT * FROM `service`";
$res = mysqli_query($conn, $servsql);
// $services = mysqli_fetch_array($res, MYSQLI_ASSOC);

$servicesall = mysqli_fetch_all($res, MYSQLI_ASSOC);

$creatorsql = "select * from `creator` where `userid` = '$userid'";
$res = mysqli_query($conn,$creatorsql);

$creator = mysqli_fetch_array($res,MYSQLI_ASSOC);

$creatorid = $creator["creatorid"];

?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <form id="userform" class="registration-form" name="userform" action="<?php echo $_SERVER['PHP_SELF']; ?>"
            method="POST" onSubmit="registerSubmit(event)" enctype="multipart/form-data">

            <div class="col-sm">
                <input type="hidden" value="<?php echo $creatorid; ?>" name="creatorid" />
                <label for="street" class="form-text w-100">Service: </label>
                <select id="serviceid" name="serviceid" class="p-3">
                    <?php foreach ($servicesall as $item): ?>
                        <option value="<?php echo $item["serviceid"]; ?>"><?php echo $item["name"]; ?></option>

                    <?php endforeach; ?>

                </select>
                <br /> <br />
                <label for="street" class="form-text w-100">Charge Per Day: </label>

                <input type="number" name="amount" placeholder="Enter your charge..."/>
                <br /> <br />
                <button type="submit" name="submit" value="userform">Save</button>

            </div>
        </form>
        <div class="p-5">
            <h4 class="text-center">Your Services </h4>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Rate</th>
                </tr>

                <?php foreach ($services as $item): ?>

                    <tr>

                        <td>
                            <?php echo $item['servicename']; ?>
                        </td>
                        <td>
                            <?php echo $item['rate']; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>