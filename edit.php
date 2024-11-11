<?php
require_once("helper.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

    $selectStmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id"); // Assuming the table name is "buku"
    $selectStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $selectStmt->execute();
    $book = $selectStmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo "Book not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newJudul = $_POST['new_judul'];
        $newPenulis = $_POST['new_penulis'];
        $newSinopsis = $_POST['new_sinopsis'];
        $newPages = $_POST['new_pages'];
        $newGenre = $_POST['new_genre'];

        $updateStmt = $pdo->prepare("UPDATE buku SET judul = :judul, penulis = :penulis, sinopsis = :sinopsis, pages = :pages, genre = :genre WHERE id = :id");
        $updateStmt->bindParam(':judul', $newJudul, PDO::PARAM_STR);
        $updateStmt->bindParam(':penulis', $newPenulis, PDO::PARAM_STR);
        $updateStmt->bindParam(':sinopsis', $newSinopsis, PDO::PARAM_STR);
        $updateStmt->bindParam(':pages', $newPages, PDO::PARAM_INT);
        $updateStmt->bindParam(':genre', $newGenre, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header("Location: dashboardAdmin.php");
            exit();
        } else {
            echo "Error updating book.";
        }
    }

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='logobuku.jpg' rel='shortcut icon'>
    <title>Edit Book</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #5d4037;
            background-image: url(bg_book1.jpg);
            margin: 0;
            padding: 0;
            color: #fff;
        }

        h1 {
            text-align: center;
            color: #fff;
            padding: 20px;
            background-color: #3e3127;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 2.0s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .edit-container {
            background: linear-gradient(135deg, #8d6e63, #6d4c41);
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(20px);
            animation: slideIn 0.5s ease-in-out 0.3s forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            color: #3e2723;
            border: 1px solid #fff;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.3s ease;
        }

        input[type="submit"] {
            background-color: #8d6e63;
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #6d4c41;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #a1887f;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h1>Edit Book</h1>
    <div class="edit-container">
        <form method="post" action="edit.php?id=<?php echo $id; ?>">
            <label for="new_judul">New Judul:</label>
            <input type="text" name="new_judul" value="<?php echo $book['judul']; ?>" required>

            <label for="new_penulis">New Penulis:</label>
            <input type="text" name="new_penulis" value="<?php echo $book['penulis']; ?>" required>

            <label for="new_sinopsis">New Sinopsis:</label>
            <textarea name="new_sinopsis" required><?php echo $book['sinopsis']; ?></textarea>

            <label for="new_pages">New Pages:</label>
            <input type="number" name="new_pages" value="<?php echo $book['pages']; ?>" required>

            <label for="new_genre">New Genre:</label>
            <input type="text" name="new_genre" value="<?php echo $book['genre']; ?>" required>

            <input type="submit" value="Save Changes">
            <a href="dashboardAdmin.php"><input type="submit" class="submit fadeIn" value="Back"></a>
        </form>
    </div>
</body>
</html>
