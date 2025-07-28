<?php include 'session_start.php'; // Rozpoczęcie sesji ?>
<?php

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

// Zczytanie danych z tabeli
$sql = "SELECT service_code, description, price FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="ReturnUp.css">
    <script src="ReturnUp.js"></script>
</head>
<body>

<?php include 'header_nav.php'; ?>

<section>
    <h4>Cennik naszych usług</h4>

    <table>
        <thead>
            <tr>
                <th>Usługa</th>
                <th>Opis</th>
                <th>Cena (PLN)</th>
                <?php
                // Sprawdzenie, czy użytkownik jest administratorem
                if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
                    echo "<th>Akcje</th>"; // Dodaj kolumnę akcji dla administratora
                } elseif (isset($_SESSION['username']) && $_SESSION['role'] === 'user') {
                    echo "<th>Dodaj do koszyka</th>"; // Dodaj kolumnę dla użytkowników
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sprawdzenie, czy są wyniki
            if ($result->num_rows > 0) {
                // Wyświetlenie danych w tabeli
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['service_code']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['price']}</td>";
                    // Dodaj przyciski akcji dla administratora
                    if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
                        echo "<td>
                                <form method='POST' action='delete_service.php'>
                                    <input type='hidden' name='service_code' value='{$row['service_code']}'>
                                    <button type='submit'>Usuń</button>
                                </form>
                              </td>";
                    } elseif (isset($_SESSION['username']) && $_SESSION['role'] === 'user') {
                        // Dodaj przycisk "Dodaj do koszyka" dla użytkowników
                        echo "<td>
                                <form method='POST' action='add_to_cart.php'>
                                    <input type='hidden' name='service_code' value='{$row['service_code']}'>
                                    <button type='submit'>Dodaj do koszyka</button>
                                </form>
                              </td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Brak danych</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Formularz dodawania nowej usługi
    if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
        ?>
        <h4>Dodaj nową usługę</h4>
        <div class="contact-form">
            <form method="POST" action="add_service.php">
                <label for="service_code">Kod usługi:</label>
                <input type="text" id="service_code" name="service_code" required>
                <label for="description">Opis usługi:</label>
                <input type="text" id="description" name="description" required>
                <label for="price">Cena (PLN):</label>
                <input type="number" id="price" name="price" required step="0.01">
                <button type="submit">Dodaj usługę</button>
            </form>
        </div>
        <?php
    }
    ?>
</section>

<button id="scrollToTopBtn" onclick="scrollToTop()">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" viewBox="0 0 256 256">
        <path d="M208,80v88a64,64,0,0,1-128,0V51.31L45.66,85.66A8,8,0,0,1,34.34,74.34l48-48a8,8,0,0,1,11.32,0l48,48a8,8,0,0,1-11.32,11.32L96,51.31V168a48,48,0,0,0,96,0V80a8,8,0,0,1,16,0Z"></path>
    </svg>
</button>

<footer>
    <p>&copy; 2024 PDR PRIMO. Wszelkie prawa zastrzeżone.</p>
</footer>

</body>
</html>

<?php
// Zamknięcie połączenia
$conn->close();
?>
