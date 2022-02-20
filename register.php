<?php
session_start();
require 'config/config.php';
require 'config/common.php';

if ($_POST) {
    if (
        empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])
        || empty($_POST['phone']) || empty($_POST['address']) || strlen($_POST['password']) < 6
    ) {
        if (empty($_POST['name'])) {
            $nameError = "Name is required";
        }
        if (empty($_POST['email'])) {
            $emailError = "Email is required";
        }
        if (empty($_POST['password'])) {
            $passwordError = "Password is required";
        }
        if (strlen($_POST['password']) < 6) {
            $passwordError = "Password should have atleast 6 characters";
        }
        if (empty($_POST['phone'])) {
            $phoneError = "Phone is required";
        }
        if (empty($_POST['address'])) {
            $addressError = "Address is required";
        }
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $created_at = $_POST['created_at'];
        //check email duplicated
        $estmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $estmt->execute([':email' => $email]);
        $emailDuplicated = $estmt->fetch();
        if ($emailDuplicated) {
            echo "<script>alert('Email Duplicated!!');window.location.href='register.php'</script>";
            exit();
        }

        $statement = $pdo->prepare("INSERT INTO users(name,email,password,phone,address,role,created_at) VALUES(:name,:email,:password,:phone,:address,:role,:created_at)");
        $result = $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':phone' => $phone,
            ':address' => $address,
            ':role' => 0,
            ':created_at' => $created_at
        ]);
        if ($result) {
            echo "<script>alert('Successfully created account,you can now login.');window.location.href='login.php'</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>Code1 Shop | Register</title>

    <!--
		CSS
		============================================= -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <!-- Start Header Area -->
    <header class="header_area sticky-header">
        <div class="main_menu">
            <nav class="navbar navbar-expand-lg navbar-light main_box">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="index.html">
                        <h4>Code 1 Shopping<h4>
                    </a>
                </div>
            </nav>
        </div>
        <div class="search_input" id="search_input_box">
            <div class="container">
                <form class="d-flex justify-content-between">
                    <input type="text" class="form-control" id="search_input" placeholder="Search Here">
                    <button type="submit" class="btn"></button>
                    <span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
                </form>
            </div>
        </div>
    </header>
    <!-- End Header Area -->

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Register</h1>
                    <nav class="d-flex align-items-center">
                    <a href="login.php">Login&nbsp;</a>
						<a href="register.php">/Register</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Login Box Area =================-->
    <section class="login_box_area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 container">
                    <div class="login_form_inner">
                        <h3>Register With Email</h3>
                        <form class="row login_form" action="" method="post" id="contactForm" novalidate="novalidate">
                            <input type="hidden" class="form-control" name="_token" value="<?= $_SESSION['_token'] ?>">
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="name" name="name" style="<?= empty($nameError) ? '' : 'border:1px solid red;'; ?>" placeholder="Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'">
                                <small style="color:red;float:left"><?= isset($nameError) ? '*' . $nameError : '' ?></small>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="email" class="form-control" id="name" name="email" style="<?= empty($emailError) ? '' : 'border:1px solid red;'; ?>" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
                                <small style="color:red;float:left"><?= isset($emailError) ? '*' . $emailError : '' ?></small>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="password" class="form-control" id="name" name="password" style="<?= empty($passwordError) ? '' : 'border:1px solid red;'; ?>" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
                                <small style="color:red;float:left"><?= isset($passwordError) ? '*' . $passwordError : '' ?></small>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="name" name="phone" style="<?= empty($phoneError) ? '' : 'border:1px solid red;'; ?>" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
                                <small style="color:red;float:left"><?= isset($phoneError) ? '*' . $phoneError : '' ?></small>
                            </div>
                            <div class="col-md-12 form-group">
                                <textarea name="address" rows="3" class="form-control" style="<?= empty($addressError) ? '' : 'border:1px solid red;'; ?>" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'"></textarea>
                                <small style="color:red;float:left"><?= isset($addressError) ? '*' . $addressError : '' ?></small>
                            </div>
                            <input type="hidden" class="form-control" name="created_at" value="<?= date('Y-m-d') ?>">
                            <div class="col-md-12 form-group">
                                <button type="submit" value="submit" class="primary-btn">Register</button>
                                <a href="login.php" type="button" class="primary-btn" style="color:white">Login</a>
                            </div>
                        </form>
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Box Area =================-->

    <!-- start footer Area -->
    <footer class="footer-area section_gap">
        <div class="container">
            <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
                <p class="footer-text m-0">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;<script>
                        document.write(new Date().getFullYear());
                    </script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </p>
            </div>
        </div>
    </footer>
    <!-- End footer Area -->


    <script src="js/vendor/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/nouislider.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <!--gmaps Js-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
    <script src="js/gmaps.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>