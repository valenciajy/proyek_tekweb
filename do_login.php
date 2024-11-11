<?php
    require_once("helper.php");

    if(isset($_POST["login"])){
        if($_POST["username"]!=="" && $_POST["password"]!==""){
            if($_POST["username"]=="admin" && $_POST["password"]=="admin"){
                header("location: dashboardAdmin.php");
            }else{
                $username = $_POST["username"];
                $password = '';
                $ada=false;
    
                $stmt = $pdo->query("select * from user where username='$username'");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ( $rows as $row ) {
                    $password = $row["password"];
                    $ada = true;
                    $id = $row["id"];
                }
                if(!$ada){
                    header("location: login.php?error=1");
                }else{
                    // if(password_verify($_POST["password"],$password)){
                    if($_POST["password"]===$password){
                        header("location: dashboard.php?id=".$id);
                    }else{
                        header("location: login.php?error=2");
                    }
                }
            }
        }else{
            header("location: login.php?error=3");
        }
    }else{
        header("location: login.php");
    }