<?php
    ob_start();
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    $errorCheck = "" ;
    $errorMsg = "";
    if (!(array_key_exists('id',$_COOKIE))) {
        $_COOKIE['email']="";
        $_COOKIE['pass']="";
    }
    if (array_key_exists('logout',$_GET)) {
        unset($_SESSION);
        setcookie("id","") ;
        setcookie("pass","") ;
        setcookie("email","") ;
    } 
/*    else if ((array_key_exists("id",$_SESSION) AND $_SESSION['id']!='') OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id']!='')) {
        header ("Location: details.php");
    }*/
    if (array_key_exists("signUpSubmit", $_POST)) {
        $serverName = "sdb-c.hosting.stackcp.net";
        $userName = "myMoodleUsers-3137313d72";
        $password = "bizoimw133";
        $dbName = "myMoodleUsers-3137313d72";
        $link = mysqli_connect($serverName, $userName, $password, $dbName);
        if (mysqli_connect_error()) {
            die("DB connection error !");
        }
        else {
            $query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['mail'])."' LIMIT 1";
            $result = mysqli_query($link,$query);
            if (mysqli_num_rows($result)>0) {
                $errorCheck = "raiseError" ;
                $errorMsg = "Email address already exists !";
            }
            else {
                $query = "INSERT INTO users (firstName,lastName,phnNumber,email,password) 
                          VALUES('".mysqli_real_escape_string($link,$_POST['fName'])."',
                          '".mysqli_real_escape_string($link,$_POST['lName'])."',
                          '".mysqli_real_escape_string($link,$_POST['pNumber'])."',
                          '".mysqli_real_escape_string($link,$_POST['mail'])."',
                          '".mysqli_real_escape_string($link,$_POST['pass'])."' )" ;
                $result = mysqli_query($link,$query);
                        
                $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                $query = "UPDATE users SET password = '".$hash."' WHERE _id = ".mysqli_insert_id($link)." LIMIT 1" ;
                mysqli_query($link,$query) ;
            }
        }
        header("Location: index.php");
    }
    if (array_key_exists("signInSubmit", $_POST)) {
        $serverName = "sdb-c.hosting.stackcp.net";
        $userName = "myMoodleUsers-3137313d72";
        $password = "bizoimw133";
        $dbName = "myMoodleUsers-3137313d72";
        $link = mysqli_connect($serverName, $userName, $password, $dbName);
        if (mysqli_connect_error()) {
            die("DB connection error !");
        }
        else {
           $query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1";
            if (!($result = mysqli_query($link,$query))) {
                $errorCheck = "raiseError";
                $errorMsg = "Wrong Credentials, Try Again !";
            }
            else {
                $row = mysqli_fetch_array($result);
                if (password_verify($_POST['pass'],$row['password'])) {
                    $_SESSION['id']=$row['_id'];
                    $_SESSION['password']=$_POST['pass'];
                    $_SESSION['email']=$row['email'];
                    if (!empty($_POST['staySignedIn'])) {
                        setcookie("id",$row['_id'], time()+(60*60*24*365));
                        setcookie("email",$row['email'], time()+(60*60*24*365));
                        setcookie("pass",$_POST['pass'], time()+(60*60*24*365));
                    }
                    header("Location: details.php" ); 
                }
                else {
                    $errorCheck = "raiseError";
                    $errorMsg = "Wrong Credentials, Try Again !";
                }
            }
        }
    }
    ob_end_flush();
?>
        
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        Moodle Login
    </title>
    <!-- FAV ICON -->
    <link rel="shortcut icon" href="images\Icon.ico" type="image/x-icon">
    <!-- FONT AWESOME ICONS -->
    <script src="https://kit.fontawesome.com/08d11cd3e5.js" crossorigin="anonymous"></script>
    <!-- FONT LINKS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Pacifico&display=swap" rel="stylesheet">
    <!-- CSS FILE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="Index/style.css" rel="stylesheet">
</head>

<body>
    <section id="NavigationBar">
        <nav class="navbar navbar-expand-lg navbar-light sticky-top">
            <a class="navbar-brand" href="#startHere">
                <img src="images\Icon.ico" width="45" height="45" class="d-inline-block align-top ic" alt="">
                Moodle
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle"
                aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarToggle" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="navBarText nav-link" href="#aboutUs"> About Us </a>
                    </li>
                    <li class="nav-item">
                        <a class="navBarText nav-link" href="#contact"> Contact </a>
                    </li>
                </ul>
            </div>
        </nav>
    </section>
    <section id="startHere">
        <br>
        <br>
        <br>
    </section>
    <section id="signIn">
        <div class="container-fluid row">
            <div id="signInDetails" class="col-lg-6 tablePadding">
                <h1><strong> Sign In </strong></h1>
                <div id="alertBig" class="alert alert-warning alert-dismissible <?php echo $errorCheck;?>">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $errorMsg; ?>
                </div>
                <div id="alertSmall" class="alert alert-warning alert-dismissible <?php echo $errorCheck;?>">
                    <h6 class="alterPad">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo $errorMsg; ?>
                    </h6>
                </div>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Email address </label>
                        <input type="email" name="email" value="<?php echo $_COOKIE['email'] ;?>"
                        class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text subText"> - We'll never share your email
                            with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password </label>
                        <input type="password" name="pass" value="<?php echo $_COOKIE['pass'] ;?>"
                        class="form-control" id="Password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" class="checkBoxDesign" name="staySignedIn" value=1>
                        <label for="staySignedIn" class="checkBoxStyle subText"> Remember me . </label>
                    </div>
                    <button id="signUpButton" name="signUpButton" value="signUp" class="b btn btn-lg btn-danger">Sign
                        Up</button>
                    <button id="signInSubmit" name="signInSubmit" value="signedIn" type="submit"
                        class="b btn btn-lg btn-danger">Submit</button>
                </form>
            </div>
            <div id="picture" class="col-lg-6 img">
                <img src="images\Welcome.jpg" class="image">
            </div>
        </div>
    </section>
    <section id="signUp" class="hideSection">
        <div class="container-fluid row">
            <div id="signUpDetails" class="col-lg-6 tablePadding">
                <h1><strong> Sign Up </strong></h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="Name"> Name </label>
                        <input type="text" name="fName" class="form-control" id="Name" placeholder="First Name">
                        <input type="text" name="lName" class="form-control" id="lastName" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <label for="mobileNumber"> Phone Number </label>
                        <input type="tel" name="pNumber" class="form-control" id="mobileNumber"
                            placeholder="Mobile Number">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address </label>
                        <input type="email" name="mail" class="form-control" id="email" aria-describedby="emailHelp"
                            placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="Password">Password </label>
                        <input type="password" name="pass" class="form-control" id="Password" placeholder="Password">
                        <small class="form-text subText"> -Should be 8 characters or more. It can be any
                            combination of letters,numbers, and symbols .</small>
                    </div>
                    <button id="signUpSubmit" name="signUpSubmit" value="signedUp" type="submit"
                        class="b btn btn-lg btn-danger">Submit</button>
                </form>
            </div>
            <div id="picture" class="col-lg-6 img">
                <img src="images\Welcome.jpg" class="image">
            </div>
    </section>
    <br>
    <br>
    <hr>
    <section id="aboutUs">
        <h1><strong>About Us</strong></h1>
        <p>We work together to design, create and produce work that we are proud of for folks that we believe in. We
            are available for hire in a wide range of creative disciplines for a variety of jobs, projects and gigs.
        </p>
    </section>
    <br>
    <br>
    <hr>
    <footer id="contact">
        <br>
        <a href="https://www.facebook.com/sriram.kannan.35380">
            <i class="contact fab fa-facebook-square fa-lg"></i>
        </a>
        <a href="https://www.instagram.com/marirs2703">
            <i class=" contact fab fa-instagram-square fa-lg"></i>
        </a>
        <a href="https://twitter.com/Marirs2703">
            <i class="contact fab fa-twitter-square fa-lg"></i>
        </a>
        <p> © Copyright 2020 Moodle </p>
        <br>
    </footer>
    <!-- JQUERY FILES -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    <script src="Index/style.js"></script>
</body>

</html>