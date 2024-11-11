<?php
   require_once("helper.php");

   if(isset($_GET["id"])){
       $id = $_GET["id"];
       $data2 = [];
       
       $stmt = $pdo->query("select * from user where id='$id'");
       $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
       foreach ( $rows as $row ) {
           $data2["id"] = $row["id"];
           $data2["username"] = $row["username"];
           $data2["email"] = $row["email"];
       }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Profile</h1>
    <div>
        <p><?= $data2["username"] ?></p>
        <p><?= $data2["email"] ?></p>
    </div>
</body>
</html>