<?php
    if(count($_COOKIE) > 0) {
        $email = $_COOKIE["email"];
        $password = $_COOKIE["password"];

        function updatescore() {
            $email = $_COOKIE["email"];
            $password = $_COOKIE["password"];
            $best = (integer)$_COOKIE["HighScore"];
            $con=mysqli_connect("localhost", "root", "", "flappy");
            $check=mysqli_query($con, "CREATE TABLE IF NOT EXISTS gamedata(email VARCHAR(50), best INT(255));");
            $checkz=mysqli_query($con, "SELECT * FROM gamedata WHERE email = '$email';");
            if(mysqli_num_rows($checkz) == 0) {
                $confirm=mysqli_query($con, "INSERT INTO gamedata(email, best) VALUES ('$email', $best);");
            } else {
                $update=mysqli_query($con, "UPDATE gamedata SET best = $best WHERE email = '$email';");
            }
            $xz=mysqli_close($con);
        }
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

        #setCookie("HighScore", 0, 0);

        $getscore = 'getscore';
        $updatescore = 'updatescore';

        $htmlcode = <<<XYZ
        <html>
            <head>
                <style>
                    @font-face {
                        font-family: flappybird;
                        src: url('http://www.mediafont.com/storage/contents/3184/font.eot');
                        src: url('http://www.mediafont.com/storage/contents/3184/04B_19__.TTF');
                    }
                    
                    body{
                        margin: 0;
                        width:100%;
                        height:100%;
                        overflow: hidden;
                    }
                    #Canvas{
                        position:relative;
                        width: 100%;
                        height: 100%;
                        background: steelblue;
                    }
                    #Birdy{
                width: 2.9%;
                        padding-bottom: 2%;
                        
                        
                        background-image: url('http://flappybird.io/img/bird.png');
                        
                        background-size: 300%;
                        
                        position:absolute;
                        top: 50%;
                        left: 20%;
                        
                        z-index: 150;
                    }
                    
                    .FallenBirdy{
                        -webkit-transform:rotate(90deg);
                        -moz-transform:rotate(90deg);
                        -ms-transform:rotate(90deg);
                        -o-transform:rotate(90deg);
                        transform:rotate(90deg);
                    }
                    
                    #PauseButton{
                        position:absolute;
                        /* Position button so it's not hugging the screen edge */
                        top: 2%; 
                        right: 1%;
                        
                        width: 3%; /* Make the width scale with the canvas size */
                        height: 0; /* Set an initial 0 height */
                        /* Use padding of same width% to create a square
                        Padding uses percentages based on width, so we're going to use padding that'll add up to 3%*/
                        padding-top: 1.25%;
                        padding-bottom: 1.75%;
                        
                        background: orange;
                        
                        border-radius: 5px;
                        
                        font-size: 2.6vw;
                        text-align: center;
                        
                        z-index: 50; /* Make it lay on top of the pipes */
                        
                        cursor:pointer; /* Make it appear like it's a button so the cursor turns into a hand when hovered */
                    }
                    #PauseButton span{
                        line-height: 3%; /*Vertically center the pause text */
                        margin-left: 3%; /* Adjust pause text position */
                    }

                    #LogoutButton{
                        position:absolute;
                        top: 77%; 
                        right: 3%;
                    }
                    #LogoutButton span{
                        line-height: 3%;
                        margin-left: 3%;
                    }
                    
                    .Pipe{
                        position:absolute;
                        top: 0;
                        left: 100%;
                        
                        width: 5%;
                        
                        background: greenyellow;
                        
                        animation: PipeMovement 15s linear;
                        -webkit-animation: PipeMovement 15s linear;
                    }
                    
                    @keyframes PipeMovement{
                        from {left: 100%}
                        to {left: -25%}
                    }
                    @-webkit-keyframes PipeMovement{
                        from {left: 100%}
                        to {left: -25%}
                    }
                    
                    .paused {
                        -ms-animation-play-state:paused;
                        -o-animation-play-state:paused;
                        -moz-animation-play-state:paused;
                        -webkit-animation-play-state:paused;
                        animation-play-state: paused;
                    }
                    
                    .noSelect{
                        -webkit-touch-callout: none;
                        -webkit-user-select: none;
                        -khtml-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                    }
                    
                    #LostScoreScreen{
                        left: -50%;
                        margin-top: -70%;
                        
                        padding: 5px 0px 40px 12px;
                        
                        background: #ded895;
                        
                        border-radius: 4%;
                        border: 2px solid black;
                        
                        text-align: center;
                        
                        display: none;
                        
                        font-family: flappybird;
                        font-size: 2vw;
                        
                        color:white;            
                        text-shadow:
                            -2px -2px 0 #000,  
                            2px -2px 0 #000,
                            -2px 2px 0 #000,
                            2px 2px 0 #000;
                        
                        z-index: 150;
                    }
                    
                    #CurrentScoreCard{
                        left: -50%;
                        margin-top: -700%;
                                        
                        text-align: center;
                        
                        font-family: flappybird;
                        font-size: 4vw;
                        
                        color:white;               
                        text-shadow:
                            -2px -2px 0 #000,  
                            2px -2px 0 #000,
                            -2px 2px 0 #000,
                            2px 2px 0 #000;
                        
                        z-index: 50;
                    }
                    
                    
                    #DebugInfo{
                        position: absolute;
                        top: 0; left: 0;
                        width: 150px;
                        height: 250px;
                        z-index: 25;
                        background: gray;
                        opacity: 0.7;
                        color:white;     
                        display:none;
                    }
                </style>
                
                
                <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
            </head>
            <body>
                <div id="Canvas">
                    
                    
                    <div id="InstructionBox" style="position: absolute; left: 50%; display:none;">
                        <div style="position: relative; left: -50%; z-index: 150; font-family:flappybird; color:white; font-size: 2vw;" class="noSelect">
                            Click to Fly <br>
                            Space Bar to Reset <br>
                            "P" to Pause
                        </div>
                    </div>
                                
                    <div style="position: absolute; left: 50%; top: 50%;">
                        <div id="CurrentScoreCard" class="noSelect">
                            <span id="CurrentScore">0</span>
                        </div>
                    </div>
                    
                    <div style="position: absolute; left: 50%; top: 50%;">
                        <div id="LostScoreScreen" class="noSelect">
                            <span>Score</span>
                            <br>
                            <span id="FinalScore">0</span>
                            <br>
                            <span>Best</span>
                            <br>
                            <span id="BestScore">0</span>
                            <br>
                            <button id="ResetButton" type="button" class="btn btn-warning">Reset</button>
                            <br>
                            <button id="LogoutButton" type="button" class="btn btn-warning">Logout</button>
                            </div>
                    </div>
                    
                    <div id="DebugInfo" class="noSelect"></div>
                    <div id="Birdy" style=" background-position-x: 400%;">
                    </div>
                    <div id="PauseButton" class="noSelect">
                        <span class="glyphicon glyphicon-pause"></span>
                    </div>
                </div>
                
                
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                <script>
                    $(function(){
                        $('#InstructionBox').slideDown(); //Slide down the instructions box
                        setTimeout(function(){ $('#InstructionBox').slideUp(); }, 5000);
                        
                        var canvasObject = $('#Canvas');
                        
                        var gameLoopIntervalID = 0;
                        var Paused = true;
                        
                        var Lost = false;
                        
                        function pauseGame(){
                            clearInterval(gameLoopIntervalID);
                            $('.Pipe').addClass('paused');
                            $('#PauseButton span').removeClass('glyphicon-pause').addClass('glyphicon-play');
                            Paused = true;
                        }
                        
                        function startGame(){
                            if(Lost){
                                return;
                            }
                            gameLoopIntervalID = setInterval(function(){gameLoop();}, 30);
                            $('.Pipe').removeClass('paused'); //Unpause the CSS3 animations
                            $('#PauseButton span').removeClass('glyphicon-play').addClass('glyphicon-pause');
                            Paused = false;
                        }
                        
                        function endGame(){
                            Lost = true;
                            pauseGame(); 
                            var cookieScore = getCookie('HighScore');      
                            console.log(Math.max(CurrentScore,cookieScore));
                            console.log(cookieScore);
                            setCookie('HighScore', Math.max(CurrentScore,cookieScore), 30000);
                            {$updatescore()}
                            Birdy.BirdyObject.animate({top:'90%'}, 1500, 'linear');
                            $('#FinalScore').html(CurrentScore);
                            $('#BestScore').html(Math.max(CurrentScore,cookieScore));
                            $('#LostScoreScreen').slideDown();
                        }

                        function logout(){
                            setCookie('email', 'cookie_value', 0);
                            setCookie('password', 'cookie_value', 0);
                            setCookie('HighScore', 0, 0);
                            var loc = window.location.pathname;
                            var dir = loc.substring(0, loc.lastIndexOf('/'));
                            setCookie('HighScore', 0, 0, dir);
                            window.open("login.php","_self");
                        }
                        
                        function resetGame(){
                            pauseGame();
                            $('.Pipe').remove();
                            Lost = false;
                            CurrentScore = 0;
                            Birdy.Reset();
                            {$updatescore()}
                            location.reload();
                            startGame();                   
                            $('#LostScoreScreen').slideUp();
                        }
                        
                        function togglePause(){
                            if(!Paused){
                                pauseGame();
                            }else{
                                startGame();
                            }
                        }                            
                        
                        var CurrentScore = 0;
                        
                        $('#PauseButton').mousedown(function(event){
                            event.stopPropagation();
                            togglePause();
                        });

                        
                        $('#ResetButton').click(function(){
                            resetGame();
                        });

                        $('#LogoutButton').click(function(){
                            logout();
                        });
                        
                        canvasObject.mousedown(function(){
                            Birdy.jump();
                        });
                        
                        $('body').keydown(function(event){
                            if(event.which == 32){
                                resetGame();
                            }
                            if(event.which == 80){
                                togglePause();
                            }
                        });
                        
                        var gameLoopCounter = 0;
                        
                        function gameLoop(){
                            
                            if(gameLoopCounter % 2 === 0){
                                incrementScore();
                                checkCollisions();
                            }
                            
                            isInBound(Birdy.BirdyObject, canvasObject);
                            Birdy.fall();
                            
                            if(gameLoopCounter%90 === 0){
                                addPipe();
                                cleanPipes();
                            }
                            
                            if(gameLoopCounter%7 === 0){
                                Birdy.flapWings();
                            }
                            
                            gameLoopCounter++;
                        }
                        
                        var Birdy = new (function(){
                            var selectorObject = $('#Birdy');
                            
                            var jumping = false;
                            
                            var gravVeloc = 0;
                            
                            var gravAccel = 0.3;
                            
                            var terminalVelocity = 5;
                            
                            var Angle = 0;
                            
                            var WingPosition = 0;

                            var WingPositions = [0, 1, 2, 1];
                            
                            this.Reset = function(){
                                jumping = false;
                                gravVeloc = 0;
                                Angle = 0;
                                WingPosition = 0;
                                selectorObject.stop().rotate(0).css('top','50%');
                            }
                            
                            this.fall = function(){
                                if(!jumping){
                                    selectorObject.stop().animate({top:'+='+gravVeloc+'%'}, 30, 'linear'); //Stop any current animations and then drop the bird by Velocity%
                                    gravVeloc += gravAccel; //Add the acceleration scalar to the velocity scalar.
                                    if(gravVeloc>terminalVelocity){
                                        gravVeloc = terminalVelocity;
                                    }
                                    var AdjustedAngle = Angle+15*(gravVeloc/terminalVelocity)^2;
                                    adjustAngle(Math.min(AdjustedAngle,90));
                                    $('#DebugInfo').html('Gravity: '+gravVeloc);
                                }else{
                                    gravVeloc = 0; //Reset the falling velocity.
                                    //console.log('Grav Disabled');
                                }
                            };
                            
                            this.jump = function(){
                                if(Paused){
                                    return;
                                }
                                jumping = true;
                                adjustAngle(-45);
                                selectorObject.stop().animate({top:'-=9%'}, 100, 'linear', function(){
                                    jumping = false;
                                    Birdy.fall();
                                });
                            };
                            
                            this.flapWings = function(){
                                WingPosition++;
                                
                                if(Angle > 45){
                                    WingPosition = 1;
                                }
                                
                                selectorObject.css("background-position-x", WingPositions[WingPosition%4]*50 + "%"); //Move the backgroud position of the bird to animate the flapping
                            }
                            
                            function adjustAngle(angle){
                                selectorObject.rotate(angle);
                                Angle = angle;
                            }
                            
                            
                            this.BirdyObject = selectorObject;
                        });
                        
                        //Generate a new pipe
                        function addPipe(){
                            var PipeGap = 30,
                                    MinPipeHeight = 5;
                            
                            var MaxTopPipeHeight = 100-PipeGap-2*MinPipeHeight;                    
                            var TopPipeHeight = Math.random()*MaxTopPipeHeight+MinPipeHeight;
                            var BottomPipeTop = TopPipeHeight+PipeGap;
                            var BottomPipeHeight = 100-BottomPipeTop;
                            
                            $('<div/>').addClass('Pipe').css('height',TopPipeHeight+'%').data('scored', false).appendTo(canvasObject);
                            $('<div/>').addClass('Pipe BottomPipe').css({height:BottomPipeHeight+'%',top: BottomPipeTop+'%'}).data('scored', false).appendTo(canvasObject); 
                            
                        }

                        function cleanPipes(){
                            $('.Pipe').each(function(){
                                if($(this).offset().left/$(this).parent().width() < -0.2){ 
                                    $(this).remove();
                                }
                            });
                        }
                        
                        function checkCollisions(){
                            $('.Pipe').each(function(){
                                if(isIntersecting(Birdy.BirdyObject, $(this))){
                                    console.log('Hit!');
                                    endGame();
                                }
                            });
                        }
                        
                        function isIntersecting(obj1, obj2){
                            var obj1Dimensions = [obj1.offset().left, obj1.offset().top, obj1.offset().left+obj1.width(), obj1.offset().top+obj1.height()];
                            var obj2Dimensions = [obj2.offset().left, obj2.offset().top, obj2.offset().left+obj2.width(), obj2.offset().top+obj2.height()];
                            
                            return !(obj1Dimensions[3] < obj2Dimensions[1]
                                    || obj1Dimensions[1] > obj2Dimensions[3]
                                    || obj1Dimensions[0] > obj2Dimensions[2]
                                    || obj1Dimensions[2] < obj2Dimensions[0] );
                            
                        }
                        
                        function isInBound(birdy, canvas){
                            if(birdy.offset().top+birdy.height()> canvas.offset().top+canvas.height() || birdy.offset().top<canvas.offset().top){ 
                                console.log('Out of Bounds!'); //Print out that we're out of bounds
                                endGame();
                            }
                        }
                        
                        function incrementScore(){
                            $('.BottomPipe').each(function(){
                                var BirdyBeakXPos = Birdy.BirdyObject.offset().left + Birdy.BirdyObject.width();
                                var PipeRightXPos = $(this).offset().left + $(this).width();
                                if(!$(this).data('scored') && BirdyBeakXPos>PipeRightXPos){
                                    CurrentScore++; //Increment score
                                    console.log(CurrentScore);
                                    $(this).data('scored', true);
                                }
                            });          
                            $('#CurrentScore').html(CurrentScore);
                        }
                                    
                        startGame();     

                        jQuery.fn.rotate = function(degrees) {
                            return $(this).css({'-webkit-transform' : 'rotate('+ degrees +'deg)', '-moz-transform' : 'rotate('+ degrees +'deg)', '-ms-transform' : 'rotate('+ degrees +'deg)', 'transform' : 'rotate('+ degrees +'deg)'});
                        };
                        
                        function setCookie(cname,cvalue,exdays, path = "/")
                        {
                            var d = new Date();
                            d.setTime(d.getTime()+(exdays*24*60*60*1000));
                            var expires = d.toGMTString();
                            document.cookie = cname + "=" + cvalue + "; expires=" + expires + "; path=" + path + ";";
                        }
                        function getCookie(cname)
                        {
                            var name = cname + "=";
                            var ca = document.cookie.split(';');
                            for(var i=0; i<ca.length; i++) 
                            {
                                var c = ca[i].trim();
                                if (c.indexOf(name)==0) return c.substring(name.length,c.length);
                            }
                            return "";
                        }
                    });
                    
                </script>
                
            </body>
        </html>
        XYZ;
        echo $htmlcode;
    } else {
        echo '<script>alert("Cookies Have Either Been Cleared Or They Are Disabled...");</script>';
        echo '<script>window.open("login.php","_self")</script>';
    }
?>