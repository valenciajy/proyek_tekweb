<?php
    require_once('helper.php');

    if(isset($_POST["register"])){
        if($_POST["username"]!=="" && $_POST["password"]!=="" && $_POST["confirm_password"]!=="" && $_POST["email"]!==""){
            if($_POST["password"]!=$_POST["confirm_password"]){
                header("location: register.php?error=1");
            }else{
                $username = $_POST["username"];
                $email = $_POST["email"];
                $adaUsername = false;
                $adaEmail = false;
    
                $stmt = $pdo->query("select * from user where username='$username'");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ( $rows as $row ) {
                    $adaUsername = true;
                }

                $stmt = $pdo->query("select * from user where email='$email'");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ( $rows as $row ) {
                    $adaEmail = true;
                }
    
                if($adaUsername){
                    header("location: register.php?error=2");
                }else if($adaEmail){
                    header("location: register.php?error=3");
                }else{
                    $password = $_POST["password"];
                    
                    $sql = "INSERT into user values(null, :username, :pass, :email)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(':username' => $username,
                            ':pass' => $password,
                            ':email' => $email
                        ));
    
                    header("location: login.php?success=1");
                }
            }
        }else{
            header("location: register.php?error=4");
        }
    }else{
        header("location: login.php");
    }