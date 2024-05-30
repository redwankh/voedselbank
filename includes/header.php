
<?php // terug naar login als je niet ingelogd bent
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM gebruikers  WHERE gebruiker_id = :gebruiker_id");
$stmt->execute(['gebruiker_id' => $_SESSION['gebruiker_id']]);
$gebruiker = $stmt->fetch();
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

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Voedsel Bank</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="pakketten.php">Pakketten</a></li>
                <li class="nav-item"><a class="nav-link active" href="producten.php">Producten</a></li>
                <li class="nav-item"><a class="nav-link active" href="klanten.php">Klanten</a></li>
            </ul>
            <strong class="px-2 text-white">Welkom, <?php echo $gebruiker['gebruikersnaam'] ?></strong>
            <a href="login.php?logout=1" type="submit" name="logout" class="btn btn-sm btn-danger">Uitloggen</a>
        </div>
    </div>
</nav>