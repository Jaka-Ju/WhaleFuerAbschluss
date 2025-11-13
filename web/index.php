<?php
// === Verbindung zur Datenbank ===
$host = "db";     
$benutzer = "root";      
$pass = "Wurzelchef";           
$db   = "User";          

$conn = new mysqli($host, $benutzer, $pass, $db);

if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

session_start();

$message = "";

// === Formularverarbeitung ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Registrierung
    if (isset($_POST["register"])) {
        $username = trim($_POST["newUsername"]);
        $email = trim($_POST["newEmail"]);
        $password = trim($_POST["newPassword"]);

        if ($username && $email && $password) {
            $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "Benutzername oder E-Mail bereits vergeben!";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hash);
                $stmt->execute();
                $message = "Konto erfolgreich erstellt! Bitte melde dich an.";
            }
        } else {
            $message = "Bitte alle Felder ausfüllen!";
        }
    }

    // Login
    if (isset($_POST["login"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $_SESSION["username"] = $user["username"];
                header("Location: hauptseite.php");
                exit;
            } else {
                $message = "Falsches Passwort!";
            }
        } else {
            $message = "Benutzer nicht gefunden!";
        }
    }
}
?>
