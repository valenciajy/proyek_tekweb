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
    <link href='logobuku.jpg' rel='shortcut icon'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
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
    <form action="do_register.php" method="POST">
            <div class="top">
                <span>Have an account? <a href="login.php" onclick="login()">Login</a></span>
                <header>Sign Up</header>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" name="username" placeholder="Firstname">
                <i class="bx bx-user"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="password" placeholder="Password">
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="confirm_password" placeholder="Confirm Password">
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="email" class="input-field" name="email" placeholder="Email">
                <i class="bx bx-envelope"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" name="register" value="Register">
            </div>
    <form> 
</div>  
</body>
<script>
    var error = <?php echo json_encode($error) ?>;
    
    if (error == 1) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Register',
            text: 'Password dan Confirm Password harus sama!',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (error == 2){
        Swal.fire({
            icon: 'error',
            title: 'Gagal Register',
            text: 'Username sudah terdaftar!',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (error == 3){
        Swal.fire({
            icon: 'error',
            title: 'Gagal Register',
            text: 'Email sudah terdaftar!',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (error == 4){
        Swal.fire({
            icon: 'error',
            title: 'Gagal Register',
            text: 'Mohon isi semua field!',
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>

<script>
    function register() {
        x.style.left = "-510px";
        y.style.right = "5px";
        a.className = "btn";
        b.className += " white-btn";
        x.style.opacity = 0;
        y.style.opacity = 1;
    }
</script>

</html>