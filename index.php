<?php
include_once 'config.php';

// terug naar login als je niet ingelogd bent
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: login.php");
    exit();
}

$week_number = date('W');

// alle pakketen ophallen die deze week opgesteld moet worden, dus nog niet compleet
$stmt = $pdo->prepare("
    SELECT  *, WEEK(pakketten.datum, 1) FROM pakketten 
    INNER JOIN klanten ON (pakketten.klant_id = klanten.klant_id)  
    WHERE pakketten.status != 'compleet' AND WEEK(pakketten.datum,1) = ".$week_number."
    ORDER BY pakketten.datum
");
$stmt->execute();
$pakketten = $stmt->fetchAll();

include_once( 'includes/header.php' );
?>

<div class="container mt-5">
    <h1>Onverwerkte Pakketten - Week <?= $week_number ?></h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Pakket ID</th>
            <th>Klant Naam</th>
            <th>Datum</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($pakketten as $pakket): ?>
                <tr>
                    <td><?= $pakket['pakket_id'] ?></td>
                    <td><?= $pakket['naam'] ?></td>
                    <td><?= $pakket['datum'] ?></td>
                    <td><?= $pakket['status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once ('includes/footer.php') ?>

