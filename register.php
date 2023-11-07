<?php include('connect.php'); ?>
<?php 

    $message = "";
    
    $name = "";
    $email = "";
    $password = "";
    $address = "";
    $phone = "";
    $userid = "";
    
    if(isset($_POST['submit']))
	{
        if($_POST['submit'] == "userform"){
            $name = $_POST['username'];
            $password = $_POST['password'];

            if($name == "" ||
               $password == "" )
               {
                    $message = "All fields are required. Registration failed, Please try again.";
               }
               else{
                $udsql = "select * from `user` where `username` = '$name'";
                $res = mysqli_query($conn,$udsql);
    
                $row = mysqli_fetch_array($res,MYSQLI_ASSOC);
                $count = mysqli_num_rows($res);
    
                if($count == 1) {
                    $message = "This user name is already registered. Please provide another email.";
                }
                else{
                    $udregistrationsql = "INSERT INTO `user` (`username`, `password`) VALUES ('$name', '$password')";
    
                    $resReg = mysqli_query($conn,$udregistrationsql);
                    $count = mysqli_affected_rows($conn);
                    if($count > 0){
                        header("Location: login.php");
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
        <form id="userform" class="registration-form" name="userform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onSubmit="registerSubmit(event)"> 
            <h3>User Registration</h3>
            <?php if($message != ""): ?>
	        <p style="color:red; text-align:center;">Warning:-<?php echo $message; ?></p>
	        <?php endif; ?>
             
             <label for="name">User Name: </label><br /><br />
             <input type="text" id="username" name="username" placeholder="Your user name.."><br /><br />
            
             <label for="password">Password:</label><br /><br />
             <input type="password" id="password" name="password"><br /><br />
            
             <!-- submit and reset form -->
             <button type="submit" name="submit" value="userform">Submit</button>
             <button type="reset" value="Reset">Reset</button>
         </form>
        </div>

    </main>
    <?php include_once("footer.php") ?>
</body>

</html>