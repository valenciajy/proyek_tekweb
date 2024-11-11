<?php
    require_once("helper.php");
    
    if(isset($_GET["buku"])){
        $id = $_GET["buku"];
        $data = [];
        
        $stmt = $pdo->query("select * from buku where id='$id'");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $data["id"] = $row["id"];
            $data["judul"] = $row["judul"];
            $data["penulis"] = $row["penulis"];
            $data["sinopsis"] = $row["sinopsis"];
            $data["gambar"] = $row["gambar"];
            $data["isi"] = $row["isi"];
            $data["genre"] = $row["genre"];
            $data["pages"] = $row["pages"];
        }
    } else {
        header("location: dashboard.php");
    }

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NovelNest Book</title>
    <!-- <link rel="stylesheet" href="bookstyle.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap JavaScript and Popper.js (Order matters) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body>

    <p><?= $data["isi"] ?></p>

</body>
</html>
