<?php include('connect.php'); ?>
<?php
session_start();
session_destroy();
session_start();
$message = "";
$rows = array();

if(isset($_POST['submit']))
{ 

    $username = $_POST['username'];
    $password = $_POST['password'];

    if($_POST['submit'] == 'userform'){
        $udsql = "select * from `user` where `username` = '$username' And `password` = '$password'";

        $res = mysqli_query($conn,$udsql);
        
        $row = mysqli_fetch_array($res,MYSQLI_ASSOC);
        $count = mysqli_num_rows($res);
        
        if($count == 1) 
        {
            if($row['role'] == 'creator' )
            {
                $uid = $row['userid'];
                $qry = "SELECT status from creator where userid = $uid";
                $ans = mysqli_query($conn,$qry);
                $creator = mysqli_fetch_array($ans);

                if($creator['status'] == 'pending')
                {

                    $message = "Sorry ! you have not Approved by admin !";
                    
                }
                if($creator['status'] == 'rejected')
                {
                    $message = "Sorry ! you are Rejected by admin !";
                }
                if($creator['status'] == 'approved')
                {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['userid'] = $row['userid'];
                    $_SESSION['usertype'] = $row['role'];
    
                    header("Location: index.php");
                }
            }
            else
            {
                $_SESSION['username'] = $row['username'];
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['usertype'] = $row['role'];
    
                if($row['usertype'] == 'Admin'){
                    header("Location: admin.php");   
                }
                else{
                    header("Location: index.php");
                }
    
            }
        }
        else 
        {
            $message = "Login failed, Please try again.";
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
            <form id="userform" class="registration-form" name="userform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onSubmit="loginSubmit(event)"> 
                <h3>Please login as a User</h3>
             
                <?php if($message != ""): ?>
	            <p style="color:red; text-align:center;">Warning:-<?php echo $message; ?></p>
	            <?php endif; ?>
                 <label for="username">Username: </label><br />
                 <input type="text" id="username" name="username" placeholder="Your username.."><br /><br />

                 <label for="password">Password:</label><br />
                 <input type="password" id="password" name="password"><br /><br />
                 <a href="resetpassword.php?id=1">Reset Password</a>
                 <br /><br />

                 <!-- submit and reset form -->
                 <button type="submit" name="submit" value="userform">Submit</button>
                 <button type="reset" value="Reset">Reset</button>
            </form>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>