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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <h1>Login</h1>
    <form action="do_login.php" method="POST">
        <label for="">Username:</label>
        <input type="text" name="username" id=""><br>

        <label for="">Password:</label>
        <input type="password" name="password" id=""><br>
        <button type="submit" name="login">Login</button><br>
        <a href="register.php">Register here!</a>
    </form>
</body>
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