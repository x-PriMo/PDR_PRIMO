<?php
include 'session_start.php';
// Sprawdzenie, czy użytkownik jest administratorem
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Przekierowanie, jeśli nie jest administratorem
    exit();
}

// Połączenie z bazą danych
$servername = "localhost";
$username = "root"; // Użytkownik bazy danych
$password = ""; // Hasło do bazy danych
$dbname = "pdrprimo"; // Nazwa bazy danych

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Usuwanie użytkownika
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM users WHERE UserID = $user_id";
    if ($conn->query($sql) === TRUE) {
        echo "Użytkownik usunięty pomyślnie!";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: admin_dashboard.php"); // Przekierowanie z powrotem do panelu administratora
exit();
?>
