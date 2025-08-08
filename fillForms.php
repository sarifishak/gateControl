<?php
include("database.inc");

$name = $_GET['name'] ?? '';
if ($name) {
    $stmt = $pdo->prepare("SELECT id,name,destination,alamat FROM registeredlist WHERE noKenderaan = ? ORDER BY id desc");
    $stmt->execute([$name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}
?>
