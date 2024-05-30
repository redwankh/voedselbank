<?php
include 'config.php';

// Handle form submission for adding a new customer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_klant'])) {
    $naam = $_POST['naam'];
    $stmt = $pdo->prepare("INSERT INTO klanten (naam) VALUES (:naam)");
    $stmt->execute(['naam' => $naam]);
    header("Location: klanten.php");
    exit();
}

// Handle form submission for deleting a customer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_klant'])) {
    $klant_id = $_POST['klant_id'];
    $stmt = $pdo->prepare("DELETE FROM klanten WHERE klant_id = :klant_id");
    $stmt->execute(['klant_id' => $klant_id]);
    header("Location: klanten.php");
    exit();
}

// Fetch all customers
$stmt = $pdo->query("SELECT * FROM klanten");
$klanten = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once( 'includes/header.php' );
?>
<div class="container mt-5">
    <h1>Klanten teovogen</h1>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="naam" class="form-control" placeholder="Nieuwe klant naam" required>
            <button type="submit" name="add_klant" class="btn btn-primary">Toevoegen</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Actie</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($klanten as $klant): ?>
            <tr>
                <td><?= htmlspecialchars($klant['klant_id']) ?></td>
                <td><?= htmlspecialchars($klant['naam']) ?></td>
                <td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="klant_id" value="<?= $klant['klant_id'] ?>">
                        <button type="submit" name="delete_klant" class="btn btn-danger">Verwijderen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once ('includes/footer.php') ?>

