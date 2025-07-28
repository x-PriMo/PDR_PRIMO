<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Przekierowanie, jeśli nie jest zalogowany
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

// Sprawdzenie, czy koszyk istnieje w sesji
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id']; // Użyj ID użytkownika z sesji
    $order_date = date('Y-m-d H:i:s'); // Data zamówienia

    // Wstawienie zamówienia do tabeli orders
    $sql = "INSERT INTO orders (user_id, order_date) VALUES ($user_id, '$order_date')";
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // ID nowego zamówienia

        // Wstawienie pozycji zamówienia
        foreach ($_SESSION['cart'] as $service_code) {
            $sql = "INSERT INTO order_items (order_id, service_code) VALUES ($order_id, '$service_code')";
            $conn->query($sql);
        }

        // Wyczyść koszyk po złożeniu zamówienia
        unset($_SESSION['cart']);
        
        // Zakończenie sesji
        session_destroy(); // Zniszczenie sesji
        header("Location: index.php"); // Przekierowanie na stronę główną
        exit();
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Koszyk jest pusty.";
}

$conn->close();
header("Location: cart.php"); // Przekierowanie z powrotem do koszyka
exit();
?>
