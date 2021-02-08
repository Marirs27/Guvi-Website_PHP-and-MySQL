<?php
    ob_start();
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    if (array_key_exists("id",$_COOKIE)) {
        $_SESSION['id']=$_COOKIE['id'] ;
        $_SESSION['password']=$_COOKIE['pass'];
        $_SESSION['email']=$_COOKIE['email'];
    }
    if (!(array_key_exists("id",$_SESSION))) {
        header("Loaction: index.php");
    }
    $serverName = "sdb-c.hosting.stackcp.net";
    $userName = "myMoodleUsers-3137313d72";
    $password = "bizoimw133";
    $dbName = "myMoodleUsers-3137313d72";
    $link = mysqli_connect($serverName, $userName, $password, $dbName);
    $errorCheck = "" ;
    $errorMsg = "";
    $query = "SELECT * FROM users WHERE _id = '".$_SESSION['id']."' LIMIT 1" ;
    $result = mysqli_query($link,$query);
    $row = mysqli_fetch_array($result);
    if (array_key_exists("updateSave", $_POST)) {
        $query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['updatedEmail'])."'";
        $result = mysqli_query($link,$query);
        if (!(mysqli_num_rows($result)>1)) {
            $query = "UPDATE users SET firstName='".mysqli_real_escape_string($link,$_POST['updatedFirstName'])."',
                    lastName='".mysqli_real_escape_string($link,$_POST['updatedLastName'])."',
                    phnNumber='".mysqli_real_escape_string($link,$_POST['updatedNumber'])."',
                    email='".mysqli_real_escape_string($link,$_POST['updatedEmail'])."',
                    password='".mysqli_real_escape_string($link,$_POST['updatedPassword'])."' 
                    WHERE _id='".$_SESSION['id']."' LIMIT 1" ;
            $result = mysqli_query($link,$query);
                    
            $hash = password_hash($_POST['updatedPassword'], PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = '".$hash."' WHERE _id = '".$_SESSION['id']."' LIMIT 1" ;
            mysqli_query($link,$query) ;
            
            $query = "SELECT * FROM users WHERE _id = '".$_SESSION['id']."' LIMIT 1" ;
            $result = mysqli_query($link,$query);
            $row = mysqli_fetch_array($result) ;
        }
    }
    ob_end_flush();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        Personal Details
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
    <link href="Display\details.css" rel="stylesheet">
</head>

<body>
    <section id="NavigationBar">
        <nav class="navbar navbar-expand-lg navbar-light sticky-top">
            <a class="navbar-brand" href="#startHere">
                <img src="images\Icon.ico" width="50" height="50" class="d-inline-block align-top" alt="">
                Moodle
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle"
                aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarToggle" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="navBarText nav-link" href="#contact"> Contact Us </a>
                    </li>
                    <li class="nav-item">
                        <form id="my_form" action="/" method="POST">
                            <input id = "logoutCheck" type="hidden" name="logoutCheck" value='0'>
                            <a id = "logoutButton" class="navBarText nav-link" name="logout" value = "0" 
                            href="index.php?logout=1" type="submit"> Log Out </a>
                        </form>
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
    <section id="header">
        <div class="container-fluid">
            <div class="overlay">
                <h1><strong> Profile </strong></h1>
            </div>
            <a id="editIcon" href="#" class="editBttn"><i class="fas fa-pen-square fa-2x"></i></a>
            <img id="profilePic" class="image" src="images\Profile-Pic.png">
            <img id="editPic" class="image d-none" src="images\Edit-Pic.png">
        </div>
    </section>
    <section id="displayProfile">
        <div>
            <br>
            <br>
            <br>
        </div>
        <div id="profileDetails">
            <div class="container-fluid">
                <div class="row displayRow">
                    <div id="profileTag" class="bg-danger col-lg-4 displayCol"><i class="ic fas fa-user"></i> Name
                    </div>
                    <div id="profileValue" class="fg col-lg-6 displayCol">
                        <?php echo $row['firstName']." ".$row['lastName'] ;?>
                    </div>
                </div>
                <br>
                <div class="row displayRow">
                    <div id="profileTag" class="bg-danger col-lg-4 displayCol"><i class="ic fas fa-mobile"></i> Phone
                        Number </div>
                    <div id="profileValue" class="fg col-lg-6 displayCol">
                         <?php echo $row['phnNumber'] ;?>
                    </div>
                </div>
                <br>
                <div class="row displayRow">
                    <div id="profileTag" class="bg-danger col-lg-4 displayCol"><i class="ic fas fa-envelope"></i> Email
                        address
                    </div>
                    <div id="profileValue" class="fg col-lg-6 displayCol">
                         <?php echo $row['email'] ;?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="updateProfile" class="hideSection">
        <div class="container-fluid">
            <br>
            <br>
            <form method="POST">
                <div id="updateDetails">
                    <div class="form-group row">
                        <label class="bg-danger formLabel col-lg-4" for="Name"> Name </label>
                        <input type="text" name="updatedFirstName" value="<?php echo $row['firstName'] ;?>"
                            class="form-control formField col-lg-3" id="Name" placeholder="First Name">
                        <input type="text" name="updatedLastName" value="<?php echo $row['lastName'] ;?>"
                            class="form-control formField col-lg-3" id="lastName" placeholder="Last Name">
                    </div>
                    <div class="form-group row">
                        <label class="bg-danger formLabel col-lg-4" for="mobileNumber"> Phone Number </label>
                        <input type="tel" name="updatedNumber" value="<?php echo $row['phnNumber'] ;?>"
                            class="form-control formField col-lg-6" id="mobileNumber">
                    </div>
                    <div class="form-group row">
                        <label class="bg-danger formLabel col-lg-4" for="email">Email address </label>
                        <input type="email" name="updatedEmail" value="<?php echo $row['email'] ;?>"
                            class="form-control formField col-lg-6" id="email" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group row">
                        <label class="bg-danger formLabel col-lg-4" for="Password">Password </label>
                        <input type="text" name="updatedPassword" value="<?php echo $_SESSION['password'] ;?>"
                            class="form-control formField col-lg-6" id="Password">
                    </div>
                </div>
                <button id="updateSave" type="submit" name="updateSave" value="updateData"
                    class="btn btn-lg btn-danger">
                    Save </button>
            </form>
        </div>
    </section>
    <br>
    <br>
    <br>
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
    <script src="Display\detail.js"></script>
</body>

</html>