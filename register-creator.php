<?php include('connect.php'); ?>
<?php include("upload.php"); ?>

<?php

$message = "";

$name = "";
$email = "";
$password = "";
$address = "";
$phone = "";
$userid = "";

if (isset($_POST['submit'])) {


    if ($_POST['submit'] == "userform") {
        $name = $_POST['username'];
        $password = $_POST['password'];

        $fullname = $_POST['fullname'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $age = $_POST['age'];
        $street = $_POST['street'];
        $creatortype = $_POST['creatortype'];
        $bio = $_POST['bio'];

        // $crequery = "SELECT SUM(rate) AS total FROM creatorservice WHERE serviceid IN ($placeholders) AND creatorid = $creatorid";
        $filtered = array_filter(array_keys($_POST), function ($key) {
            return strpos($key, 'service') !== false;
        });
        $selectedservices = array_values(array_intersect_key($_POST, array_flip($filtered)));

        if (count($selectedservices) <= 0) {
            echo "<script> alert('Please select atleast one service.'); </script>";
            $shouldSubmit = false;
        }

        if (
            $name == "" ||
            $password == "" ||
            $fullname == "" ||
            $city == "" ||
            $state == "" ||
            $age == "" ||
            $street == "" ||
            $creatortype == ""
        ) {
            $message = "All fields are required. Registration failed, Please try again.";
        } else {
            $udsql = "select * from `user` where `username` = '$name'";
            $res = mysqli_query($conn, $udsql);

            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
            $count = mysqli_num_rows($res);

            if ($count == 1) {
                $message = "This user name is already registered. Please provide another email.";
            } else {


                $udregistrationsql = "INSERT INTO `user` (`username`, `password`, `role`) VALUES ('$name', '$password', 'creator')";

                $resReg = mysqli_query($conn, $udregistrationsql);
                $count = mysqli_affected_rows($conn);
                $lastid = mysqli_insert_id($conn);
                if ($count > 0) {

                    if (isset($_FILES['coop'])) {
                        if ($_FILES['coop']['error'] == 0) {
                            $uploadFile = new uploadFile();
                            $coop = $uploadFile->upload($_FILES['coop'], "img/creator", "img/creator/$name");
                        } else {
                            $message = 'Please try again.';
                        }
                    }

                    $insertcreator = "INSERT INTO `creator` (`name`, `city`, `state`, `street`, `bio`, `age`, `userid`, `creatortype`, `path`) VALUES ('$fullname', '$city', '$state', '$street', '$bio', '$age', '$lastid', '$creatortype', '$coop')";
                    // $udregistrationsql = "INSERT INTO `user` (`username`, `password`) VALUES ('$name', '$password')";
                    $resReg = mysqli_query($conn, $insertcreator);
                    $count = mysqli_affected_rows($conn);

                    if ($count > 0) {
                        $lastcreatorid = mysqli_insert_id($conn);



                        foreach ($selectedservices as $serviceid) {
                            $sql = "INSERT INTO `creatorservice` (`creatorid`,`serviceid`) VALUES ($lastcreatorid,$serviceid)";

                            $save = $conn->query($sql);
                        }

                        header("Location: login.php");
                    } else {
                        $deletesql = "DELETE FROM `user` WHERE userid = $lastid";
                        $resReg = mysqli_query($conn, $deletesql);

                        $message = "Registration failed, Please try again.";
                    }
                } else {
                    $message = "Registration failed, Please try again.";
                }
            }
        }
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
            <form id="userform" class="registration-form" name="userform" action="<?php echo $_SERVER['PHP_SELF']; ?>"
                method="POST" onSubmit="registerSubmit(event)" enctype="multipart/form-data">
                <h3>Creator Registration</h3>
                <?php if ($message != ""): ?>
                    <p style="color:red; text-align:center;">Warning:-
                        <?php echo $message; ?>
                    </p>
                <?php endif; ?>

                <div class="row">
                    <div class="col-sm">
                        <label for="username" class="form-text w-100">User Name: </label>
                        <input type="text" id="username" name="username" placeholder="Your user name..">
                    </div>

                    <div class="col-sm form-group">

                        <label for="password" class="form-text w-100">Password:</label>
                        <input type="password" id="password" name="password" style="padding: 12px;margin-top: 6px;"
                            class="form-control">
                    </div>
                </div>

                <div class="row ">
                    <div class="col-sm">
                        <label for="fullname" class="form-text w-100">Full Name: </label>
                        <input type="text" id="fullname" name="fullname" placeholder="Your full name..">

                    </div>
                    <div class="col-sm">
                        <label for="city" class="form-text w-100">City: </label>
                        <input type="text" id="city" name="city" placeholder="Your city..">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <label for="state" class="form-text w-100">State: </label>
                        <input type="text" id="state" name="state" placeholder="Your state..">

                    </div>
                    <div class="col-sm">
                        <label for="age" class="form-text w-100">Age: </label>
                        <input type="number" id="age" name="age" style="padding: 12px;margin-top: 6px;"
                            placeholder="Your age.." class="form-control">
                    </div>
                </div>

                <div class="col-sm">
                    <label for="street" class="form-text w-100">Street: </label>
                    <input type="text" id="street" name="street" placeholder="Your street..">
                </div>

                <div class="col-sm">
                    <label for="street" class="form-text w-100">Upload File: </label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="coop">
                    </div>
                </div>

                <div class="col-sm">
                    <label for="street" class="form-text w-100">Creator Type: </label>
                    <select id="creatortype" name="creatortype" class="p-3">
                        <option value="photographer">Photographer</option>
                        <option value="videographer">Videographer</option>
                    </select>
                </div>

                <label for="bio" class="form-text w-100">Bio:</label>
                <textarea id="bio" name="bio" placeholder="Write bio.." style="height:100px"></textarea><br /><br />

                
                <!-- submit and reset form -->
                <button type="submit" name="submit" value="userform">Submit</button>
                <button type="reset" value="Reset">Reset</button>
            </form>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>