<?php
include 'session_start.php';

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

// Usuwanie usługi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_code = $_POST['service_code'];

    $sql = "DELETE FROM services WHERE service_code='$service_code'";
    if ($conn->query($sql) === TRUE) {
        echo "Usługa usunięta pomyślnie!";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: services.php"); // Przekierowanie z powrotem do strony usług
exit();
?>
