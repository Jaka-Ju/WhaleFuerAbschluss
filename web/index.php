<?php
// 1. Debugging aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Datenbankverbindung
$host = "db";
$benutzer = "root";
$pass = "Wurzelchef";
$db = "User";
//dhudghkrh
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
        $benutzername = trim($_POST["newBenutzer"]);
        $email = trim($_POST["newEmail"]);
        $passwort = trim($_POST["newPasswort"]);

        if ($benutzername && $email && $passwort) {

            $check = $conn->prepare("SELECT Benutzer_id FROM Benutzer WHERE Benutzername = ? OR Email = ?");
            $check->bind_param("ss", $benutzername, $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "<div style='color:red'>Benutzername oder E-Mail bereits vergeben!</div>";
            } else {

                $stmt = $conn->prepare("INSERT INTO Benutzer (Benutzername, email, passwort) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $benutzername, $email, $passwort);

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
        $benutzername = trim($_POST["benutzer"]);
        $passwort = trim($_POST["passwort"]);

        $stmt = $conn->prepare("SELECT Benutzername, passwort FROM Benutzer WHERE Benutzername = ?");
        $stmt->bind_param("s", $benutzername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $benutzer = $result->fetch_assoc();

            if ($passwort === $benutzer["passwort"]) {
                $_SESSION["benutzer"] = $benutzer["Benutzername"];
                header("Location: Index.html");
                exit;
            } else {
                $message = "<div style='color:red'>Benutzername oder Passwort falsch!</div>";
            }
        } else {
            $message = "<div style='color:red'>Benutzername oder Passwort falsch!</div>";
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
            <label>Benutzer:</label>
            <input type="text" name="benutzer" required>

            <label>Passwort:</label>
            <input type="password" name="passwort" required>

            <button type="submit" name="login">Einloggen</button>
        </form>
    </div>

    <div class="box">
        <h2>Registrieren</h2>
        <<form method="POST" action="index.php">
            <label>Neuer Benutzer:</label>
            <input type="text" name="newBenutzer" required>

            <label>E-Mail:</label>
            <input type="email" name="newEmail" required>

            <label>Neues Passwort:</label>
            <input type="password" name="newPasswort" required>

            <button type="submit" name="register">Konto erstellen</button>
        </form>
    </div>
</div>

</body>
</html>
