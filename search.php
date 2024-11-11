<?php
require_once("helper.php");

$data2 = [];

if (isset($_GET["id"])) {
    $userId = $_GET["id"];

    $stmtUser = $pdo->prepare("SELECT * FROM user WHERE id = :id");
    $stmtUser->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmtUser->execute();

    $data2 = $stmtUser->fetch(PDO::FETCH_ASSOC);
}

$searchResults = [];

if (isset($_GET["query"])) {
    $query = trim($_GET["query"]);  // Menggunakan trim untuk menghapus spasi di awal dan akhir input

    if (!empty($query)) {
        $queryChars = str_split($query);
        $queryLike = '';

        foreach ($queryChars as $char) {
            $queryLike .= '%' . $char;
        }

        $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul LIKE :query");
        $stmt->bindValue(':query', $queryLike . '%', PDO::PARAM_STR);
        $stmt->execute();

        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results</title>
    <link rel="stylesheet" href="searchstyle.css">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <link rel="stylesheet" href="bookstyle.css">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-image: url(b3.jpg);
        }

        header {
            text-align: center;
            margin: 20px 0;
        }

        .bookcontainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 120px;
        }

        .book {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px;
            padding: 15px;
            background-color: pink;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            width: 250px;
        }

        .book:hover {
            transform: scale(1.1);
        }

        .book img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .book-details h2 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .book-details p {
            font-size: 1rem;
            color: #6c757d;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="height: 60px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="font-size: 25px;">Novel Nest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" style="font-size: 20px;" href="<?= isset($data2["id"]) ? "dashboard.php?id=" . $data2["id"] : "#" ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="font-size: 20px;" href="<?= isset($data2["id"]) ? "library.php?id=" . $data2["id"] : "#" ?>">Library</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="font-size: 20px;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown-item"><span class="icon">👤</span><?= isset($data2["username"]) ? $data2["username"] : "Guest" ?></li>
                            <li class="dropdown-item"><span class="icon">✉️</span><?= isset($data2["email"]) ? $data2["email"] : "" ?></li>
                            <li><a class="dropdown-item" href="login.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <form class="d-flex" action="search.php" method="GET" role="search">
                <input type="hidden" name="id" value="<?= isset($data2["id"]) ? $data2["id"] : "" ?>">
                <input type="hidden" name="username" value="<?= isset($data2["username"]) ? $data2["username"] : "" ?>">
                <input type="hidden" name="email" value="<?= isset($data2["email"]) ? $data2["email"] : "" ?>">
                <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit" style="font-size: 20px;">Search</button>
            </form>

        </div>
    </nav>

    <!-- <header>
    <h1>Search Results for "<?php echo $query; ?>"</h1>
    </header> -->

    <!-- <section> -->
    <div class="bookcontainer">
    <?php
    foreach ($searchResults as $result) {
        ?>
        <a href='detailBuku.php?id=<?= urlencode($data2["id"]) ?>&buku=<?= urlencode($result["id"]) ?>'>
            <div class="book">
                <img src="<?= $result["gambar"] ?>" alt="Book Cover">
                <div class="book-details">
                    <h2><?= $result["judul"] ?></h2>
                    <p>Author: <?= $result["penulis"] ?></p>
                </div>
            </div>
        </a>
    <?php
    }
    ?>
</div>
    <!-- </section> -->
</body>

</html>