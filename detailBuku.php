<?php
require_once("helper.php");

if (isset($_GET["buku"])) {
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
}

if (isset($_POST["addlibrary"])) {
    $bookId = $data["id"];
    $userId = $data2["id"];

    $stmt = $pdo->prepare("SELECT * FROM library WHERE userId = :userId AND bookId = :bookId");
    $stmt->execute(array(':userId' => $userId, ':bookId' => $bookId));

    if ($stmt->rowCount() == 0) {
        $sql = "INSERT into library values(null, :userId, :bookId)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':userId' => $userId, ':bookId' => $bookId));

        header("Location: library.php?id=" . $userId);
        exit();
    }
}

$bookId = $data["id"];
$userId = $data2["id"];

$stmt = $pdo->prepare("SELECT * FROM bookmark WHERE userid = :userId AND bookid = :bookId");
$stmt->execute(array(':userId' => $userId, ':bookId' => $bookId));

$data3 = [];
if ($stmt->rowCount() != 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $data3["userid"] = $row["userid"];
    $data3["bookid"] = $row["bookid"];
    $data3["page"] = $row["page"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookId = $data["id"];
    $userId = $data2["id"];
    $username = $data2["username"];
    $reviewText = $_POST['review'];
    $rating = $_POST['rating']; // Perbaiki ini dengan menggunakan 'rating' sebagai nama input

    try {
        $stmtCheck = $pdo->prepare("SELECT * FROM review WHERE bookid = :bookId AND userid = :userId");
        $stmtCheck->execute([':bookId' => $bookId, ':userId' => $userId]);

        if ($stmtCheck->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO review (bookid, userid, username, isireview, rating) VALUES (:bookId, :userId, :username, :review, :rating)");
            $stmt->bindParam(':bookId', $bookId);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':review', $reviewText);
            $stmt->bindParam(':rating', $rating);
            $stmt->execute();

            echo '<div class="alert alert-success" role="alert">Review submitted successfully!</div>';
        } else {
            echo '<div class="alert alert-warning" role="alert">You have already reviewed this book.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>';
    }
}

$searchResults = [];
if (isset($_GET["query"])) {
    $query = $_GET["query"];

    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul LIKE :query");
    $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
    $stmt->execute();

    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($searchResults)) {
        header("Location: search.php");
        exit();
    }
}

$bookId = $data["id"];

$stmt = $pdo->prepare("SELECT * FROM review WHERE bookid = :bookId");
$stmt->execute([':bookId' => $bookId]);

$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bookId = $data["id"];

$stmt = $pdo->prepare("SELECT * FROM review WHERE bookid = :bookId");
$stmt->execute([':bookId' => $bookId]);

$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the average star rating
$totalStars = 0;
foreach ($reviews as $review) {
    $totalStars += $review['rating'];
}

$averageRating = count($reviews) > 0 ? round($totalStars / count($reviews), 1) : 0;



$totalStars = 0;
foreach ($reviews as $review) {
    $totalStars += $review['rating'];
}

$averageRating = count($reviews) > 0 ? round($totalStars / count($reviews), 1) : 0;

function getStarColor($rating, $index)
{
    $floorRating = floor($rating);
    if ($index <= $floorRating) {
        return 'star-filled';
    } elseif ($index - 0.5 == $floorRating) {
        return 'star-half';
    } else {
        return 'star-empty';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NovelNest Book</title>
    <link rel="stylesheet" href="bookstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-dfmdN5smp3qpxL07fe7qpwMLmUoZ6A7Ij/5P9zZ9o4mti6jE7/kLb9CZO/hA+dD5" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        #starRating {
    display: flex;
    flex-direction: row-reverse;
    margin-bottom: 20px;
}

        .star {
    cursor: pointer;
    font-size: 24px;
    color: lightgray;
    margin-right: 5px;
    flex-direction: row; /* Ubah baris ini */
}

.star:hover,
.star:hover~.star,
input[type="radio"]:checked~.star {
    color: gold;
    /* Tidak perlu mengubah flex-direction di sini */
}



        .star-filled {
            color: gold;
        }

        .star-half {
            color: gold;
            position: relative;
            display: inline-block;
            overflow: hidden;
        }

        .star-half:after {
            content: '\2605';
            position: absolute;
            left: 0;
            top: 0;
            color: lightgray;
            z-index: 1;
        }

        .star-empty {
            color: lightgray;
            /* Change this to your desired color for empty stars */
        }

        .star {
            cursor: pointer;
            margin-right: 5px;
            font-size: 24px;
            /* Adjust the font size as needed */
            color: lightgray;
            /* Default star color */
            position: relative;
            /* Add this line */
        }

        .star span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .star-filled {
            color: gold;
            /* Change this to your desired color for filled stars */
        }

        .star-empty {
            color: lightgray;
            /* Change this to your desired color for empty stars */
        }

        .star-half::before {
            content: '\2605';
            /* Unicode character for a half star */
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            overflow: hidden;
        }

        .star {
            position: relative;
        }

        /* Tambahkan class baru untuk styling bintang setengah */
        .star-half {
            color: gold;
        }

        .star:hover,
        .star:hover~.star,
        input[type="radio"]:checked~.star,
        input[type="radio"]:checked~.star-half::before {
            color: gold;
        }


        /* Hide the radio buttons */
        input[type="radio"] {
            display: none;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: cornsilk;
            /* warna cream */
            background: url('b3.jpg') center/cover fixed;
            color: #4d4d4d;
            /* warna teks gelap */
        }

        header {
            background-color: #e26274;
            /* warna header */
            color: black;
            /* warna teks header */
            padding: 10px;
            text-align: center;
        }

        section {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            max-height: 400px;
            /* Tinggi maksimum foto buku */
            max-width: 400px;
            /* Lebar maksimum foto buku */
        }

        h2,
        p {
            margin: 0;
        }

        h2 {
            color: #4d4d4d;
            /* warna teks judul */
            margin-bottom: 10px;
        }

        .details {
            max-width: 400px;
            margin-top: 20px;
        }

        .details p {
            margin-bottom: 10px;
            color: #666;
            /* warna teks detail */
        }

        .button {
            margin-top: auto;
        }

        .button button {
            background-color: pink;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

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

    <section>
        <img class="book-cover" src="<?= $data["gambar"] ?>" alt="Book Cover">
        <div class="details">
            <h2><?= $data["judul"] ?></h2>
            <p><strong>Author:</strong><?= $data["penulis"] ?></p>
            <p><strong>Genre:</strong> <?= $data["genre"] ?></p>
            <p><strong>Pages:</strong> <?= $data["pages"] ?></p>
            <p><strong>Description:</strong><?= $data["sinopsis"] ?></p>
            <p><strong>Average Rating:</strong>
            <div class="star-rating">
                <?php
                // Display average rating as stars with value
                for ($i = 5; $i >= 1; $i--) {
                    $starClass = getStarColor($averageRating, $i);
                    echo '<span class="star average-star ' . $starClass . '">&#9733;</span>';
                }

                echo $averageRating;
                ?>
            </div>
            </p>
            <div class="button">
                <?php
                if ($data3 == null) {
                    echo '<a href="nyoba.php?id=' . urlencode($data2["id"]) . '&buku=' . urlencode($data["id"]) . '&page=0">';
                    echo '<button>Read</button>';
                    echo '</a>';
                } else {
                    echo '<a href="nyoba.php?id=' . urlencode($data2["id"]) . '&buku=' . urlencode($data["id"]) . '&page=' . $data3["page"] . '">';
                    echo '<button>Read</button>';
                    echo '</a>';
                }
                ?>
                <form method="post">
                <button type="submit" name="addlibrary">Add to library</button>
    <!-- Tempatkan div starRating di sini -->
    <textarea id="review" name="review" rows="4" cols="50" placeholder="REVIEW" style="flex: 1; margin-bottom: 20px;"></textarea>

<div id="starRating">
    <input type="radio" name="rating" value="5" id="star5">
    <label for="star5" class="star">&#9733;</label>
    <input type="radio" name="rating" value="4" id="star4.5">
    <label for="star4.5" class="star">&#9733;</label>
    <input type="radio" name="rating" value="3" id="star4">
    <label for="star4" class="star">&#9733;</label>
    <input type="radio" name="rating" value="2" id="star3.5">
    <label for="star3.5" class="star">&#9733;</label>
    <input type="radio" name="rating" value="1" id="star3">
    <label for="star3" class="star">&#9733;</label>
</div>

<!-- Tombol "Submit Review" -->
<button type="submit" >Submit Review</button>
</form>



            </div>
            <div class="reviews">
                <?php
                foreach ($reviews as $review) {
                    echo '<div class="card">';
                    echo '<h2>' . $review['username'] . '</h2>';
                    echo '<p>';
                    for ($i = 1; $i <= 5; $i++) {
                        $starClass = getStarColor($review['rating'], $i);
                        echo '<span class="star ' . $starClass . '">&#9733;</span>';
                    }
                    echo '</p>';
                    echo '<p>"' . $review['isireview'] . '"</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        updateAverageStarsColor(); // Invoke the function on page load
        const starRating = document.getElementById('starRating');

        // Add a click event listener to each star
        starRating.addEventListener('click', function (event) {
            const clickedStar = event.target;
            if (clickedStar.tagName === 'LABEL') {
                // Set the value of the clicked star as the rating
                document.getElementById('rating').value = clickedStar.previousElementSibling.value;

                // Update the color of stars based on the selected rating
                const stars = document.querySelectorAll('.star');
const selectedRating = parseFloat(clickedStar.previousElementSibling.value);
stars.forEach(function (star, index) {
    star.classList.toggle('star-filled', index + 1 <= selectedRating);
});
            }
        });
    });

    function updateAverageStarsColor() {
        const averageRating = <?php echo json_encode($averageRating); ?>;
        const averageStars = document.querySelectorAll('.average-star');

        averageStars.forEach(function (star, index) {
            const starClass = index + 1 <= averageRating ? 'star-filled' : 'star-empty';
            star.className = 'star ' + starClass;
        });
    }
    </script>
</body>



</html>