<?php
include("database.inc");
$q = $_GET['q'] ?? '';
if ($q) {
    $stmt = $pdo->prepare("SELECT noKenderaan FROM registeredlist WHERE noKenderaan LIKE LOWER(?) LIMIT 5");
    $stmt->execute([$q . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}
?>