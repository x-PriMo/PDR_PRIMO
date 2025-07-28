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

// Zczytanie szczegółów zamówienia
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    $sql = "SELECT oi.service_code, s.description, s.price 
            FROM order_items oi 
            JOIN services s ON oi.service_code = s.service_code 
            WHERE oi.order_id = $order_id";
    $result = $conn->query($sql);
} else {
    echo "Brak ID zamówienia.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły zamówienia - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<?php include 'header_nav.php'; ?>

<section>
    <h4>Szczegóły zamówienia</h4>

    <table>
        <thead>
            <tr>
                <th>Usługa</th>
                <th>Opis</th>
                <th>Cena (PLN)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sprawdzenie, czy są wyniki
            if ($result && $result->num_rows > 0) {
                // Wyświetlenie danych w tabeli
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['service_code']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['price']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Brak szczegółów zamówienia.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2024 PDR PRIMO. Wszelkie prawa zastrzeżone.</p>
</footer>

</body>
</html>

<?php
// Zamknięcie połączenia
$conn->close();
?>
