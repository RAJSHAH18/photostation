<?php
    if(!isset($_GET["id"]))
    {
        header("Location: login.php");        
    }
?>
<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>

<body>
    <?php include_once("header.php"); ?>
    <main>
        <div class="">
            <form id="userform" class="registration-form" name="resetform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="msg()"> 
                <h3>RESET PASSWORD</h3>
             
                 <label for="email">E-Mail ID: </label><br />
                 <input type="email" id="email" name="email" placeholder="Your E-Mail ID.." required><br /><br />

                 <br /><br />

                 <!-- submit and reset form -->
                 <button type="submit" name="submit" value="userform">Submit</button>
                 <!-- <button type="reset" value="Reset">Reset</button> -->
            </form>
        </div>

    </main>
    <?php include_once("footer.php") ?>
    <script>
        function msg()
        {
            alert("Check Your Mail to reset the password");
        }
    </script>
</body>

</html>