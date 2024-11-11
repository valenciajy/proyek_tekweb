<?php
require_once("helper.php");

$data = [];
$data2 = []; // Tambahkan inisialisasi $data2

// Check if the user is logged in
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $stmt = $pdo->query("SELECT * FROM user WHERE id='$id'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if user data is found
    if (!empty($rows)) {
        foreach ($rows as $row) {
            $data2["id"] = $row["id"];
            $data2["username"] = $row["username"];
            $data2["email"] = $row["email"];
        }
    } else {
        // Handle the case where user data is not found (user not logged in)
        // For now, let's redirect to the login page or set default values.
        header("Location: login.php");
        exit();
    }
} else {
    // Handle the case where "id" parameter is not set (user not logged in)
    // For now, let's redirect to the login page or set default values.
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM buku");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
    $data_baru = [];
    $data_baru["id"] = $row["id"];
    $data_baru["judul"] = $row["judul"];
    $data_baru["gambar"] = $row["gambar"];
    $data_baru["genre"] = $row["genre"];
    $data_baru["penulis"] = $row["penulis"];

    // Hitung total progress untuk setiap buku yang sudah di-bookmark
    $bookId = $row["id"];
    $userId = $data2["id"];
    $stmtBookmark = $pdo->query("SELECT page FROM bookmark WHERE userid='$userId' AND bookid='$bookId'");
    $bookmarkRows = $stmtBookmark->fetchAll(PDO::FETCH_ASSOC);

    $totalProgress = 0;
    foreach ($bookmarkRows as $bookmarkRow) {
        $totalProgress += $bookmarkRow["page"];
    }

    // Kalikan dengan 100 untuk mendapatkan persentase progres
    $data_baru["progress"] = $totalProgress * 100 / $row["pages"];

    $data[] = $data_baru;
}

$data2 = [];

// Check if the user is logged in
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $stmt = $pdo->query("SELECT * FROM user WHERE id='$id'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if user data is found
    if (!empty($rows)) {
        foreach ($rows as $row) {
            $data2["id"] = $row["id"];
            $data2["username"] = $row["username"];
            $data2["email"] = $row["email"];
        }
    } else {
        // Handle the case where user data is not found (user not logged in)
        // For now, let's redirect to the login page or set default values.
        header("Location: login.php");
        exit();
    }
} else {
    // Handle the case where "id" parameter is not set (user not logged in)
    // For now, let's redirect to the login page or set default values.
    header("Location: login.php");
    exit();
}

// Handle search functionality
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
    <title>Novelnest Homepage</title>
    <link rel="stylesheet" href="homestyle.css">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                        <a class="nav-link" aria-current="page" style="font-size : 20px;" href="<?= "dashboard.php?id=" . $data2["id"] ?>">Home</a>
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

    <!-- CAROUSELS -->
    <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="nn2.jpg" class="d-block w-100" style="max-height: 685px; object-fit: cover;" alt="gambar1">
            </div>
            <div class="carousel-item">
                <img src="cinderella.jpg" class="d-block w-100" style="max-height: 685px; object-fit: cover;" alt="gambar2">
            </div>
            <div class="carousel-item">
                <img src="pinokio.jpg" class="d-block w-100" style="max-height: 685px; object-fit: cover;" alt="gambar3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- SLIDER 2.0 -->
    <div class="featured_boks">
        <h1>BOOKS</h1>
        <div class="featured_book_box">

            <?php
            foreach ($data as $d) {
                $bookLink = isset($_GET['query']) ? '#' : 'detailBuku.php?id=' . $data2["id"] . '&buku=' . $d["id"];
            ?>
                <div class="featured_book_card">
                    <a href="<?= $bookLink ?>">
                        <div class="featurde_book_img">
                            <img src="<?= $d["gambar"] ?>">
                        </div>
                        <div class="featurde_book_tag">
                            <h5><?= $d["judul"] ?></h2>
                            <p class="writer"><?= $d["penulis"] ?></p>
                            <div class="categories"><?= $d["genre"] ?></div><br>
                            <h5 class="progress">Read Progress: <?= number_format($d["progress"], 2) ?>%</h5>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="reviews">
        <h1>Reviews</h1>

        <div class="review_box">

            <div class="review_card">
                <i class="fa-solid fa-quote-right"></i>
                <div class="card_top">
                    <img src="j1.jpg">
                </div>
                <div class="card">
                    <h2>Pinoccchio</h2>
                    <p>"Captivating plot, intricate characters this fiction gem sparks imagination and reflection."</p>
                    <div class="review_icon">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                </div>
            </div>

            <div class="review_card">
                <i class="fa-solid fa-quote-right"></i>
                <div class="card_top">
                    <img src="j5.jpg">
                </div>
                <div class="card">
                    <h2>Beauty & Beast</h2>
                    <p>"Immersive storytelling, evoking emotions that linger fiction at its finest."</p>
                    <div class="review_icon">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                </div>
            </div>

            <div class="review_card">
                <i class="fa-solid fa-quote-right"></i>
                <div class="card_top">
                    <img src="j3.jpg">
                </div>
                <div class="card">
                    <h2>Cinderella</h2>
                    <p>"Page-turner with unexpected twists, a delightful escape into fantastical realms."</p>
                    <div class="review_icon">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                </div>
            </div>

            <div class="review_card">
                <i class="fa-solid fa-quote-right"></i>
                <div class="card_top">
                    <img src="j4.jpg">
                </div>
                <div class="card">
                    <h2>Jack & Jill</h2>
                    <p>"Compelling narrative, rich prose a fiction masterpiece that resonates profoundly."</p>
                    <div class="review_icon">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>