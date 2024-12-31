<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    $stmt = $pdo->prepare("DELETE FROM ruangan WHERE id = ?");
    $stmt->execute([$id]);

    echo 'success';
}
?>
