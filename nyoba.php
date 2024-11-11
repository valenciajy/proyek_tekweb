<?php
require_once("helper.php");
$data = [];
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

$data2 = [];

if(isset($_GET["id"])){
    $id = $_GET["id"];
    $data2 = [];
    
    $stmt = $pdo->query("select * from user where id='$id'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $data2["id"] = $row["id"];
        $data2["username"] = $row["username"];
        $data2["email"] = $row["email"];
    }
}
// Example content, replace this with your actual content
$content = $data["isi"];
$charsPerPage = 2500;

// Current page index
$currentPageIndex = isset($_GET['page']) ? $_GET['page'] : 0;

// Function to split content into pages
function splitContentIntoPages($content, $charsPerPage) {
    $pages = [];
    $currentPage = '';

    foreach (explode(' ', $content) as $word) {
        if (strlen($currentPage) + strlen($word) <= $charsPerPage) {
            $currentPage .= $word . ' ';
        } else {
            $pages[] = trim($currentPage);
            $currentPage = $word . ' ';
        }
    }

    // Add the last page
    if (trim($currentPage) !== '') {
        $pages[] = trim($currentPage);
    }

    return $pages;
}

// Function to display content on the current page
function displayCurrentPage() {
    $pages = splitContentIntoPages($GLOBALS['content'], $GLOBALS['charsPerPage']);

    // Check if the current page index is valid
    if ($GLOBALS['currentPageIndex'] >= 0 && $GLOBALS['currentPageIndex'] < count($pages)) {
        echo '<div id="content">' . $pages[$GLOBALS['currentPageIndex']] . '</div>';
    } else {
        echo '<div id="content">Page not found</div>';
    }
}

// Function to generate pagination controls
function generatePaginationControls() {
    $pages = splitContentIntoPages($GLOBALS['content'], $GLOBALS['charsPerPage']);
    $paginationDiv = '<div id="pagination" class="d-flex justify-content-center align-items-center">';

    // Prev Button
    if ($GLOBALS['currentPageIndex'] > 0) {
        $prevPage = $GLOBALS['currentPageIndex'] - 1;
        $prevLink = isset($GLOBALS['data2']['id']) && isset($GLOBALS['data']['id']) ? 'nyoba.php?id=' . $GLOBALS['data2']['id'] . '&buku=' . $GLOBALS['data']['id'] . '&page=' . $prevPage : '#';
        $paginationDiv .= '<a class="btn btn-pink btn-lg custom-btn-size page-link" href="' . $prevLink . '"><span class="larger-text">&lt;</span></a>';
        $paginationDiv .= '<span class="page-number mx-2">' . ($GLOBALS['currentPageIndex'] + 1) . '</span>';
    }

    // Next Button
    if ($GLOBALS['currentPageIndex'] < count($pages) - 1) {
        $nextPage = $GLOBALS['currentPageIndex'] + 1;
        $nextLink = isset($GLOBALS['data2']['id']) && isset($GLOBALS['data']['id']) ? 'nyoba.php?id=' . $GLOBALS['data2']['id'] . '&buku=' . $GLOBALS['data']['id'] . '&page=' . $nextPage : '#';
        $paginationDiv .= '<a class="btn btn-pink btn-lg custom-btn-size page-link" href="' . $nextLink . '"><span class="larger-text">&gt;</span></a>';
    }

    $paginationDiv .= '</div>';
    echo $paginationDiv;
}




if (isset($_POST["bookmark"])) {
    $bookId = $data["id"];
    $userId = $data2["id"];
    $page = $GLOBALS['currentPageIndex'];

    // Check if the combination of userid and bookid already exists
    $checkStmt = $pdo->prepare("SELECT * FROM bookmark WHERE userid = :userId AND bookid = :bookId");
    $checkStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $checkStmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        // If the combination exists, update the page
        $updateStmt = $pdo->prepare("UPDATE bookmark SET page = :page WHERE userid = :userId AND bookid = :bookId");
        $updateStmt->bindParam(':page', $page, PDO::PARAM_INT);
        $updateStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $updateStmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $updateStmt->execute();
    } else {
        // If the combination doesn't exist, insert a new record
        $insertStmt = $pdo->prepare("INSERT INTO bookmark (userid, bookid, page) VALUES (:userId, :bookId, :page)");
        $insertStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $insertStmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $insertStmt->bindParam(':page', $page, PDO::PARAM_INT);
        $insertStmt->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <title>Novelnest ReadingPage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('9445850.jpg');
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        #content-container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px auto; /* Updated to auto for centering */
            position: relative; /* Added for positioning */
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* To make sure it appears above other elements */
        }
        body {
            padding-top: 55px;
        }
        .larger-text {
            font-size: 1.4em; /* Adjust the font size as needed */
        }
        /* Updated styles for bookmark positioning */
        #content-container form {
            position: absolute;
            bottom: 20px; /* Adjust this value for the distance from the bottom */
            right: 20px;
        }
        .bookmark-btn {
            background-color: pink; /* Change this to your desired background color */
            color: black; /* Change this to your desired text color */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 300;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Added box shadow */
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-body-tertiary" style="height: 60px;">
        <div class="container-fluid">
          <a class="navbar-brand" href="#" style="font-size : 25px;" >Novel Nest</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link " aria-current="page" style="font-size : 20px;" href="<?= "dashboard.php?id=".$data2["id"]?>">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" style="font-size : 20px;" href="<?= "library.php?id=".$data2["id"]?>">Library</a>
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
    <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
    <button class="btn btn-outline-success" type="submit" style="font-size : 20px;" >Search</button>
</form>
        </div>
      </nav>

<div id="content-container">
    <?php displayCurrentPage(); ?>
    <?php generatePaginationControls(); ?>
    <form method="post">
        <input type="hidden" name="bookId" value="<?= $data["id"] ?>">
        <input type="hidden" name="userId" value="<?= $data2["id"] ?>">
        <button type="submit" name="bookmark" class="bookmark-btn">Bookmark</button>
        <!-- <button type="submit" name="bookmark">bookmark</button> -->
    </form>
</div>

</body>
</html>
    <script>
        function prevPage() {
            window.location.href = '?page=<?php echo max(0, $currentPageIndex - 1); ?>';
        }
        function nextPage() {
            window.location.href = '?page=<?php echo min(count($pages) - 1, $currentPageIndex + 1); ?>';
        }
    </script>
</body>
</html>
