<?php
require_once("helper.php");

if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $data2 = [];

  $stmt = $pdo->query("select * from user where id='$id'");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $row) {
    $data2["id"] = $row["id"];
    $data2["username"] = $row["username"];
    $data2["email"] = $row["email"];
  }

  $data = [];
  $data3 = [];

  $stmt = $pdo->query("select * from library where userId='$id'");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $row) {
    $data_baru = [];
    $data_baru["id"] = $row["id"];
    $data_baru["bookId"] = $row["bookId"];
    $data_baru["userId"] = $row["userId"];

    $data[] = $data_baru;

    $stmt = $pdo->query("select * from buku where id='$row[bookId]'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
      $data_baru1 = [];
      $data_baru1["id"] = $row["id"];
      $data_baru1["judul"] = $row["judul"];
      $data_baru1["gambar"] = $row["gambar"];
      $data_baru1["penulis"] = $row["penulis"];

      $data3[] = $data_baru1;
    }
  }
}

$searchResults = [];
if (isset($_GET["query"])) {
    $query = $_GET["query"];

    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul LIKE :query");
    $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
    $stmt->execute();

    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if search results are not empty, then redirect to search.html
    if (!empty($searchResults)) {
        header("Location: search.php");
        exit();
    }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NovelNest Library</title>
  <link href='logobuku.jpg' rel='shortcut icon'>
  <link rel="stylesheet" href="librarystyle.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap JavaScript and Popper.js (Order matters) -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <style> 

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

header {
    background-color:#e26274;
    color: white;
    padding: 1em;
    text-align: center;
}

section {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 2em;
}

.book {
    display: flex;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 1em;
    margin: 1em;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 550px;
    height: 200px;
    justify-content: center;
    align-items: center;
}

.book img {
    max-width: 100px; /* Adjust the image size as needed */
    height: auto;
    margin-right: 1em;
}

.book-details {
    flex: 1;
}

  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary" style="height: 60px;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#" style="font-size : 25px;">Novel Nest</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" style="font-size : 20px;" href="<?= "dashboard.php?id=" . $data2["id"] ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size : 20px;" href="<?= "library.php?id=" . $data2["id"] ?>">Library</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" style="font-size : 20px;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Profile
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li class="dropdown-item"><span class="icon">👤</span><?= $data2["username"] ?></li>
              <li class="dropdown-item"><span class="icon">✉️</span><?= $data2["email"] ?></li>
              <li><a class="dropdown-item" href="login.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <form class="d-flex" action="search.php" method="GET" role="search">
        <input type="hidden" name="id" value="<?= $data2["id"] ?>">
        <input type="hidden" name="username" value="<?= $data2["username"] ?>">
        <input type="hidden" name="email" value="<?= $data2["email"] ?>">
        <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit" style="font-size: 20px;">Search</button>
      </form>
    </div>
  </nav>
  <header>
    <h1><?= $data2["username"] ?>'s library</h1>
  </header>
  <section>
    <?php
    foreach ($data3 as $d) {
    ?>
      <a href="<?= "detailBuku.php?id=" . $data2["id"] . "&buku=" . $d["id"] ?>">
        <div class="book">
          <img src="<?= $d["gambar"] ?>" alt="Book Cover">
          <div class="book-details">
            <h2><?= $d["judul"] ?></h2>
            <p>Author: <?= $d["penulis"] ?></p>
          </div>
        </div>
      </a>
    <?php
    }
    ?>
  </section>

</html>