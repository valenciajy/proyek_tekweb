<?php
    require_once("helper.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #4d3c30; 
            background-image: url(bg_book1.jpg);
            margin: 0;
            padding: 0;
            color: #fff; 
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #3e3127; 
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1.5px solid #a1887f; 
        }

        th {
            background-color: #6d4c41; 
        }

        a {
            color: #ffc107; 
            text-decoration: none;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .input-box {
            text-align: center;
            margin-top: 20px;
        }

        .submit {
            background-color: #8d6e63; 
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit:hover {
            background-color: #795548; 
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fadeIn {
            animation: fadeIn 2.0s ease-in-out;
        }

        .submit:hover {
            background-color: #795548;
            transition: background-color 0.3s ease;
        }

    </style>
</head>

<body>
   <h1 class="fadeIn">Admin Dashboard</h1>
    <?php
        $stmt = $pdo->query("SELECT * FROM buku"); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<table border="1" class="fadeIn">'."\n";
        echo '<tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Sinopsis</th><th>Pages</th><th>Genre</th><th colspan="2">Actions</th></tr>';
        foreach ( $rows as $row ) {
            $id = $row["id"];
            echo "<tr><td>";
            echo($id);
            echo("</td><td>");
            echo($row['judul']);
            echo("</td><td>");
            echo($row['penulis']);
            echo("</td><td>");
            echo($row['sinopsis']);
            echo("</td><td>");
            echo($row['pages']);
            echo("</td><td>");
            echo($row['genre']);
            echo("</td><td><a href='del.php?id=$id' style='color: #FFFFFF;'>DEL</a>");
            echo("</td><td><a href='edit.php?id=$id' style='color: #FFFFFF;'>EDIT</a>");
            echo("</td></tr>\n");
        }
        echo "</table>\n";
    ?>
    <div class="input-box">
        <a href="login.php"><input type="submit" class="submit fadeIn" value="Log Out"></a>
    </div>
</body>