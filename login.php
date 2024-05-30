<?php
include 'config.php';

// uitloggen
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Als de gebruiker als is ingelogd dan naar index sturen
if (isset($_SESSION['gebruiker_id'])) {
    header("Location: index.php");
    exit();
}

// login form invullen
if (isset($_POST['login'])) {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // kijken of gebruiker in database zet
    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = :gebruikersnaam AND wachtwoord = :wachtwoord");
    $stmt->execute(['gebruikersnaam' => $gebruikersnaam, 'wachtwoord' => $wachtwoord]);
    $gebruiker = $stmt->fetch();


    // als gebruiker gevonden is dan inlogen
    if ($gebruiker) {
        $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Ongeldige gebruikersnaam of wachtwoord.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5 w-25 m-auto">
    <h1>Inloggen</h1>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert"><?= $error_message ?></div>
    <?php endif; ?>
    <form method="POST" class="mb-3 ">
        <div class="mb-3">
            <label for="gebruikersnaam" class="form-label">Gebruikersnaam</label>
            <input type="text" name="gebruikersnaam" class="form-control" id="gebruikersnaam" required>
        </div>
        <div class="mb-3">
            <label for="wachtwoord" class="form-label">Wachtwoord</label>
            <input type="password" name="wachtwoord" class="form-control" id="wachtwoord" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Inloggen</button>
    </form>
</div>

<?php include_once ('includes/footer.php') ?>


