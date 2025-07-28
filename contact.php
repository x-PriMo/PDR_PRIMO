<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header_nav.php'; ?>

<section class="contact-section">
    <div class="contact-info">
        <h2>Skontaktuj się z nami</h2>
        <p>Chętnie odpowiemy na wszystkie Twoje pytania. Możesz do nas napisać, a my odezwiemy się tak szybko, jak to możliwe.</p>
        <p>Adres e-mail: kontakt@pdrprimo.pl</p>
        <p>Telefon: +48 123 456 789</p>
    </div>

    <div class="contact-form">
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Imię i nazwisko:</label>
            <input class="form-input" type="text" id="name" name="name" required>

            <label for="email">E-mail:</label>
            <input class="form-input" type="email" id="email" name="email" required>

            <label for="phone">Telefon:</label>
            <input class="form-input" type="tel" id="phone" name="phone" required>

            <label for="subject">Temat:</label>
            <select class="form-input" id="subject" name="subject" required>
                <option value="pytanie">Pytanie</option>
                <option value="skarga">Skarga</option>
                <option value="inny">Inny</option>
            </select>

            <label for="message">Wiadomość:</label>
            <textarea class="form-input" id="message" name="message" rows="5" required></textarea>

            <label for="attachment">Załącz plik (opcjonalnie):</label>
            <input class="form-input" type="file" id="attachment" name="attachment">

            <button type="submit">Wyślij</button>
        </form>
        <?php
// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdrprimo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Połączenie z bazą danych nie powiodło się: " . $conn->connect_error);
}

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Pobierz dane z formularza
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Walidacja danych
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        die("Wszystkie pola oprócz załącznika są wymagane.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Niepoprawny adres e-mail.");
    }

    if (!preg_match('/^[0-9\-\+ ]{7,15}$/', $phone)) {
        die("Niepoprawny numer telefonu.");
    }

    // Obsługa załącznika
    $attachmentPath = null;
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['attachment']['name']);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
            $attachmentPath = $uploadFile;
        } else {
            die("Nie udało się przesłać załącznika.");
        }
    }

    // Przygotowanie zapytania SQL
    $stmt = $conn->prepare("INSERT INTO contact_form (name, email, phone, subject, message, attachment) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $subject, $message, $attachmentPath);

    if ($stmt->execute()) {
        echo "Formularz został wysłany pomyślnie.";
    } else {
        echo "Wystąpił błąd podczas zapisywania danych: " . $stmt->error;
    }

    $stmt->close();

}   
 else {
    echo "Formularz nie został przesłany.";
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
