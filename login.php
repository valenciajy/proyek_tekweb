<?php
$error = -1;
if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

$success = -1;
if (isset($_GET["success"])) {
    $success = $_GET["success"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <title>Novel Nest</title>
</head>

<body>
<div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p>NOVEL NEST</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
            </ul>
        </div>
        <div class="nav-button">
            <a href="login.php">
                <button class="btn white-btn" id="loginBtn">Login</button>
            </a>
            <a href="register.php">
                <button class="btn" id="registerBtn">Sign Up</button>
            </a>
        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>
    <form action="do_login.php" method="POST">
            <div class="top">
                <span>Don't have an account? <a href="register.php" >Sign Up</a></span>
                <header>Login</header>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" name="username" placeholder="Username">
                <i class="bx bx-user"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="password" placeholder="Password">
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" name="login" value="Sign In">
            </div>  
    </form>
</div> 
</body>

<script>
   
   function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
   }
 
</script>

<script>

    var a = document.getElementById("loginBtn");
    var b = document.getElementById("registerBtn");
    var x = document.getElementById("login");
    var y = document.getElementById("register");

    function login() {
        x.style.left = "4px";
        y.style.right = "-520px";
        a.className += " white-btn";
        b.className = "btn";
        x.style.opacity = 1;
        y.style.opacity = 0;
    }

</script>

<script>
    var error = <?php echo json_encode($error) ?>;
    var success = <?php echo json_encode($success) ?>;

    if (error == 1) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: 'Username tidak terdaftar!',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (error == 2){
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: 'Password salah!',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (error == 3){
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: 'Mohon isi semua field!',
            showConfirmButton: false,
            timer: 1500
        });
    }
    
    if(success==1){
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Register',
            text: 'Selamat akun anda berhasil dibuat!',
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>
</html>