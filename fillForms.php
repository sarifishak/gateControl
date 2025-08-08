BY <?php
include("database.inc");

$name = $_GET['name'] ?? '';
if ($name) {
    $stmt = $pdo->prepare("SELECT name,destination,alamat FROM registeredlist WHERE noKenderaan = ? ORDER BY ID DESC limit 1");
    $stmt->execute([$name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}
?>
