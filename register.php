<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header_nav.php'; ?>

<section>
    <h2>Rejestracja</h2>
    <div class="contact-form">
        <form method="POST">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" class="form-input" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-input" required>
            
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" class="form-input" required>
            
            <label for="confirm_password">Potwierdź hasło:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
            
            <button type="submit">Zarejestruj się</button>
        </form>

    <?php
    // Połączenie z bazą danych
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "pdrprimo";
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    
    if ($conn->connect_error) {
        die("Połączenie z bazą danych nie powiodło się: " . $conn->connect_error);
    }

    $error_message = ""; // Zmienna do przechowywania komunikatów o błędach

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Sprawdzenie, czy hasła są zgodne
        if ($password !== $confirm_password) {
            $error_message = "Hasła nie są zgodne.";
        }

        // Walidacja e-maila
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Nieprawidłowy adres e-mail.";
        }

        // Sprawdzenie, czy użytkownik już istnieje
        if (empty($error_message)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Użytkownik o podanej nazwie lub e-mailu już istnieje.";
            }
        }

        // Haszowanie hasła i zapis do bazy danych
        if (empty($error_message)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "Rejestracja zakończona sukcesem. <a href='login.php'>Zaloguj się</a>";
            } else {
                $error_message = "Wystąpił błąd podczas zapisywania danych: " . $stmt->error;
            }        
            $stmt->close();
        }
    }

    // Wyświetlenie komunikatu o błędzie, jeśli wystąpił
    if (!empty($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }

    $conn->close();
    ?>
    
    </div>
</section>

<footer>
    <p>&copy; 2024 PDR PRIMO. Wszelkie prawa zastrzeżone.</p>
</footer>

</body>
</html>
