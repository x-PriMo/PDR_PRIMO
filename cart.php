<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy koszyk istnieje w sesji
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Inicjalizacja koszyka
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

// Zczytanie danych z tabeli na podstawie koszyka
if (!empty($_SESSION['cart'])) {
    $cart_items = implode("','", $_SESSION['cart']);
    $sql = "SELECT service_code, description, price FROM services WHERE service_code IN ('$cart_items')";
    $result = $conn->query($sql);
} else {
    $result = null; // Brak wyników, gdy koszyk jest pusty
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<?php include 'header_nav.php'; ?>

<section>
    <h4>Twój koszyk</h4>

    <table>
        <thead>
            <tr>
                <th>Usługa</th>
                <th>Opis</th>
                <th>Cena (PLN)</th>
                <th>Akcje</th>
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
                            <td>
                                <form method='POST' action='remove_from_cart.php'>
                                    <input type='hidden' name='service_code' value='{$row['service_code']}'>
                                    <button type='submit'>Usuń z koszyka</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Koszyk jest pusty.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="place_order.php">
            <button type="submit">Złóż zamówienie</button>
        </form>
    <?php endif; ?>
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
