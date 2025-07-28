<?php
session_start(); // Rozpoczęcie sesji

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

// Logowanie użytkownika
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Weryfikacja hasła
        if (password_verify($password, $hashed_password)) {
            // Użytkownik zalogowany, zapisz dane w sesji
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id; // Ustawienie ID użytkownika w sesji
            $_SESSION['role'] = $role; // Ustawienie roli w sesji
            header("Location: index.php"); // Przekierowanie na stronę główną
            exit();
        } else {
            echo "Nieprawidłowa nazwa użytkownika lub hasło.";
        }
    } else {
        echo "Nieprawidłowa nazwa użytkownika lub hasło.";
    }

    $stmt->close();
}

$conn->close();
