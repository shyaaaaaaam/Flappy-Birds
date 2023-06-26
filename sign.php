<html>
    <?php
        $fname = $_POST["firstname"];
        $lname = $_POST["lastname"];
        $email = $_POST["Email"];
        $gender = $_POST["gender"];
        $pword = $_POST["password"];
        $con=mysqli_connect("localhost", "root","", "flappy");
        $create=mysqli_query($con, "CREATE TABLE IF NOT EXISTS userdata(fname varchar(100), lname varchar(100), email varchar(100), gender varchar(100), pword varchar(100));");
        $check=mysqli_query($con, "SELECT * FROM userdata WHERE email = '$email';");
        if(mysqli_num_rows($check) == 0) {
            $insert=mysqli_query($con, "INSERT INTO userdata (fname, lname, email, gender, pword) VALUES ('$fname', '$lname', '$email', '$gender', '$pword');");
            $xz=mysqli_close($con);
            echo '<script>alert("Account Created...");</script>';
            echo '<script>window.open("login.php","_self")</script>';
        }
        else {
            $xz=mysqli_close($con);
            echo '<script>alert("This Account Already Exists, Please Try Logging In...");</script>';
            echo '<script>window.open("login.php","_self")</script>';
        }
    ?>
</html>