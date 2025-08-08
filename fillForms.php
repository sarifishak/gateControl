<?php
include("database.inc");

$name = $_GET['name'] ?? '';
if ($name) {
    $stmt = $pdo->prepare("SELECT name,destination,alamat FROM registeredlist WHERE noKenderaan = ?");
    $stmt->execute([$name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}
?>
