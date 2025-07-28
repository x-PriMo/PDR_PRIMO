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

// Zczytanie danych z tabeli użytkowników
$sql_users = "SELECT UserID, username, email, role FROM users";
$result_users = $conn->query($sql_users);

// Zczytanie danych z tabeli zamówień
$sql_orders = "SELECT o.id, u.username, o.order_date FROM orders o JOIN users u ON o.user_id = u.UserID";
$result_orders = $conn->query($sql_orders);

// Zczytanie danych z tabeli contact_form
$sql_contact = "SELECT id, name, email, phone, subject, message, attachment, created_at FROM contact_form";
$result_contact = $conn->query($sql_contact);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<?php include 'header_nav.php'; ?>

<section>
    <h2>Panel Administratora</h2>

    <h4>Lista użytkowników</h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa użytkownika</th>
                <th>Email</th>
                <th>Rola</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sprawdzenie, czy są wyniki
            if ($result_users->num_rows > 0) {
                // Wyświetlenie danych w tabeli
                while ($row = $result_users->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['UserID']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['role']}</td>
                            <td>
                                <form method='POST' action='update_role.php' style='display:inline;'>
                                    <input type='hidden' name='user_id' value='{$row['UserID']}'>
                                    <select name='role'>
                                        <option value='user' " . ($row['role'] === 'user' ? 'selected' : '') . ">Użytkownik</option>
                                        <option value='admin' " . ($row['role'] === 'admin' ? 'selected' : '') . ">Administrator</option>
                                    </select>
                                    <button type='submit'>Zmień rolę</button>
                                </form>
                                <form method='POST' action='delete_user.php' style='display:inline;'>
                                    <input type='hidden' name='user_id' value='{$row['UserID']}'>
                                    <button type='submit'>Usuń</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Brak danych</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h4>Lista zamówień</h4>
    <table>
        <thead>
            <tr>
                <th>ID Zamówienia</th>
                <th>Użytkownik</th>
                <th>Data zamówienia</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sprawdzenie, czy są wyniki
            if ($result_orders->num_rows > 0) {
                // Wyświetlenie danych w tabeli
                while ($row = $result_orders->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['order_date']}</td>
                            <td>
                                <form method='POST' action='view_order_details.php' style='display:inline;'>
                                    <input type='hidden' name='order_id' value='{$row['id']}'>
                                    <button type='submit'>Szczegóły</button>
                                </form>    
                                <form method='POST' action='delete_order.php' style='display:inline;'>
                                    <input type='hidden' name='order_id' value='{$row['id']}'>
                                    <button type='submit'>Usuń</button>
                                </form>
                                
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Brak zamówień</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h4>Lista wiadomości kontaktowych</h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Temat</th>
                <th>Wiadomość</th>
                <th>Załącznik</th>
                <th>Data</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sprawdzenie, czy są wyniki
            if ($result_contact->num_rows > 0) {
                // Wyświetlenie danych w tabeli
                while ($row = $result_contact->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['subject']}</td>
                            <td>{$row['message']}</td>
                            <td>{$row['attachment']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <form method='POST' action='delete_contact.php' style='display:inline;'>
                                    <input type='hidden' name='contact_id' value='{$row['id']}'>
                                    <button type='submit'>Usuń</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Brak wiadomości kontaktowych</td></tr>";
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
