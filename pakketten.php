<?php
include 'config.php'; // database contectie en ander config

// terug naar login als je niet ingelogd bent
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: login.php");
    exit();
}

// als de formmulier wordt verstuurd dan een pakket toevogen
if (isset($_POST['pakket_opslaan'])) {
    $klant_id = $_POST['klant_id'];
    $datum = $_POST['datum'];
    $status = $_POST['status'];

    // kijken of ik pakket moet aanpassen
    if (isset($_POST['pakket_id']) && $_POST['pakket_id']){
        $stmt = $pdo->prepare("UPDATE pakketten SET klant_id = :klant_id, datum = :datum, status = :status WHERE pakket_id = :pakket_id");
        $stmt->execute(['klant_id' => $klant_id, 'datum' => $datum, 'status' => $status, 'pakket_id' => $_POST['pakket_id']]);

    }else{
        $stmt = $pdo->prepare("INSERT INTO pakketten (klant_id, datum, status) VALUES (:klant_id, :datum, :status)");
        $stmt->execute(['klant_id' => $klant_id, 'datum' => $datum, 'status' => $status]);
    }

    header("Location: pakketten.php");
    exit();
}

// Handle form submission for deleting a package
if (isset($_POST['delete_pakket'])) {
    $pakket_id = $_POST['pakket_id'];
      $stmt = $pdo->prepare("DELETE FROM pakketten WHERE pakket_id = :pakket_id");
     $stmt->execute(['pakket_id' => $pakket_id]);

     header("Location: pakketten.php");
    exit();
}

// alle pakketen opahllen voor overzicht
$stmt = $pdo->query("SELECT * FROM pakketten LEFT JOIN klanten ON pakketten.klant_id = klanten.klant_id");
$pakketten = $stmt->fetchAll();

// alle klanten ophallen voor formulier
$stmt_klanten = $pdo->query("SELECT klant_id, naam FROM klanten");
$klanten = $stmt_klanten->fetchAll();

// de geselecteerde pakket met klant gegevens uit database ophallen
$pakket_id = isset($_GET['pakket_id']) ? $_GET['pakket_id'] : null;
$stmt = $pdo->prepare("SELECT * FROM pakketten  WHERE pakketten.pakket_id = :pakket_id");
$stmt->execute(['pakket_id' => $pakket_id]);
$pakket = $stmt->fetch();

if (!$pakket){
    $pakket = ['pakket_id' => '','klant_id' => '', 'datum' => '', 'status' => '' ];
}

include_once( 'includes/header.php' );
?>
<div class="container mt-5">
    <h1>Pakketten</h1>
    
    <form method="POST" class="mb-3">
        <div class="input-group">
            <select name="klant_id" class="form-select" required>
                <option value="">Selecteer klant</option>
                <?php foreach ($klanten as $klant): ?>
                    <option <?php echo ($pakket['klant_id'] == $klant['klant_id'] ? 'selected' : '') ?> value="<?= $klant['klant_id'] ?>"><?= $klant['naam'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="datum" class="form-control" required value="<?php echo $pakket['datum']?>">
            <select name="status" class="form-select" required>
                <option value="">Selecteer status</option>
                <option <?php echo ($pakket['status'] == 'nieuw' ? 'selected' : '') ?> value="nieuw">Nieuw</option>
                <option <?php echo ($pakket['status'] == 'bezig' ? 'selected' : '') ?> value="bezig">Bezig</option>
                <option <?php echo ($pakket['status'] == 'compleet' ? 'selected' : '') ?> value="compleet">Compleet</option>
            </select>
            <input type="hidden" name="pakket_id" class="form-control" placeholder="id" value="<?php echo $pakket['pakket_id']?>">

            <button type="submit" name="pakket_opslaan" class="btn btn-primary">
                <?php echo $pakket['pakket_id'] ? 'Aanpassen' :  'Toevoegen';?></button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Klant</th>
            <th>Datum</th>
            <th>Status</th>
            <th style="width: 30%">Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pakketten as $pakket): ?>
            <tr>
                <td><?= $pakket['pakket_id'] ?></td>
                <td><?= $pakket['naam'] ?></td>
                <td><?= $pakket['datum'] ?></td>
                <td><?= $pakket['status'] ?></td>
                <td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="pakket_id" value="<?= $pakket['pakket_id'] ?>">
                        <button type="submit" name="delete_pakket" class="btn btn-danger">Verwijderen</button>
                    </form>
                    <a class="btn btn-warning" href="pakketten.php?pakket_id=<?php echo $pakket['pakket_id']?>">Aanpassen</a>
                    <a class="btn btn-primary" href="pakket_detail.php?pakket_id=<?php echo $pakket['pakket_id']?>">Bekijken</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include_once ('includes/footer.php') ?>

