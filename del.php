<?php
require_once("helper.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $deleteStmt = $pdo->prepare("DELETE FROM buku WHERE id = :id"); // Assuming the table name is "buku"
    $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($deleteStmt->execute()) {
        header("Location: dashboardAdmin.php");
        exit();
    } else {
        echo "Error deleting book.";
    }
} else {
    echo "Invalid request. Please provide a book ID.";
}
?>
