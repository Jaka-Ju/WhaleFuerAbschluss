<?php
// 1. Debugging aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Datenbankverbindung
$host = "db"; 
$benutzer = "root";      
$pass = "Wurzelchef";           
$db   = "User";           

// Test: Gebe etwas aus, um sicherzugehen, dass PHP überhaupt läuft
// (Das kannst du später löschen)
// echo ""; 

$conn = new mysqli($host, $benutzer, $pass, $db);

if ($conn->connect_error) {
    die("<h2>Verbindung zur Datenbank fehlgeschlagen:</h2> " . $conn->connect_error);
}

session_start();

$message = "";

// 3. Formularverarbeitung (Nur bei Klick auf Button)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // --- REGISTRIERUNG ---
    if (isset($_POST["register"])) {
        $username = trim($_POST["newUsername"]);
        $email = trim($_POST["newEmail"]);
        $password = trim($_POST["newPassword"]);

        if ($username && $email && $password) {
            // Prüfen ob User existiert
            $check = $conn->prepare("SELECT id FROM User WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "<div style='color:red'>Benutzername oder E-Mail bereits vergeben!</div>";
            } else {
                // Neuen User anlegen
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hash);
                
                if ($stmt->execute()) {
                    $message = "<div style='color:green'>Konto erfolgreich erstellt! Bitte anmelden.</div>";
                } else {
                    $message = "<div style='color:red'>Datenbankfehler beim Erstellen: " . $conn->error . "</div>";
                }
            }
            $check->close();
        } else {
            $message = "<div style='color:orange'>Bitte alle Felder ausfüllen!</div>";
        }
    }

    // --- LOGIN ---
    if (isset($_POST["login"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
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
                $message = "<div style='color:red'>Falsches Passwort!</div>";
            }
        } else {
            $message = "<div style='color:red'>Benutzer nicht gefunden!</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registrierung</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .container { display: flex; gap: 2rem; }
        .box { flex: 1; border: 1px solid #ccc; padding: 1rem; border-radius: 8px; }
        input { display: block; margin-bottom: 10px; width: 100%; padding: 8px; box-sizing: border-box;}
        button { cursor: pointer; padding: 10px; background: #007bff; color: white; border: none; width: 100%; }
        button:hover { background: #0056b3; }
        .message { margin-bottom: 1rem; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

    <h1>Willkommen beim Dashboard</h1>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="container">
        <div class="box">
            <h2>Login</h2>
            <form method="POST" action="index.php">
                <label>Benutzername:</label>
                <input type="text" name="username" required>
                
                <label>Passwort:</label>
                <input type="password" name="password" required>
                
                <button type="submit" name="login">Einloggen</button>
            </form>
        </div>

        <div class="box">
            <h2>Registrieren</h2>
            <form method="POST" action="index.php">
                <label>Neuer Benutzername:</label>
                <input type="text" name="newUsername" required>
                
                <label>E-Mail:</label>
                <input type="email" name="newEmail" required>
                
                <label>Neues Passwort:</label>
                <input type="password" name="newPassword" required>
                
                <button type="submit" name="register">Konto erstellen</button>
            </form>
        </div>
    </div>

</body>
</html>