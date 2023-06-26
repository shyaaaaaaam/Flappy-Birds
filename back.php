<html>
    <?php
        $email = $_POST["email"];
        $pword = $_POST["psw"];
        function getscore() {
            $email = $_COOKIE["email"];
            $password = $_COOKIE["password"];
            $con=mysqli_connect("localhost", "root", "", "flappy");
            $check=mysqli_query($con, "CREATE TABLE IF NOT EXISTS gamedata(email VARCHAR(50), best INT(255));");
            $check=mysqli_query($con, "SELECT best FROM gamedata WHERE email='$email';");
            if(mysqli_num_rows($check) == 0) {
                $confirm=mysqli_query($con, "INSERT INTO gamedata(email, best) VALUES ('$email', 0);");
            } else {
                $check=mysqli_query($con, "CREATE TABLE IF NOT EXISTS gamedata(email VARCHAR(50), best INT(255));");
            }
            $check=mysqli_query($con, "SELECT best FROM gamedata WHERE email='$email';");
            $check = mysqli_fetch_array($check)[0];
            $xz=mysqli_close($con);
            return $check;
        }
        
        $con=mysqli_connect("localhost", "root", "", "flappy");
        $confirm=mysqli_query($con, "SELECT * FROM userdata WHERE email = '$email' and pword = '$pword';");
        if(mysqli_num_rows($confirm) > 0) {
            $rem = $_POST["remember"];  
            $xz=mysqli_close($con);
            if (isset($rem)){
                setcookie("email", $email, time()+3600*24*365*10, "/");
                setcookie("password", $pword, time()+3600*24*365*10, "/");
                if(!isset($_COOKIE['HighScore'])) {
                    setcookie("HighScore", getscore(), 0, "/");
                }
            } else {
                setcookie("email", $email, 0, "/");
                setcookie("password", $pword, 0, "/");
                if(!isset($_COOKIE['HighScore'])) {
                    setcookie("HighScore", getscore(), 0, "/");
                }
            }
            echo '<script>window.open("flappy.php","_self")</script>';
        }
        else {
            $xz=mysqli_close($con);
            echo '<script>alert("This Account Doesnt Exist, Please Try Creating An Account");</script>';
            echo '<script>window.open("signup.html","_self")</script>';
        }
    ?>
</html>