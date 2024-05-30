<?php
include 'config.php'; // database contectie en ander config

// terug naar login als je niet ingelogd bent
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: login.php");
    exit();
}

// toevoegen van een prodit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $naam = $_POST['naam'];
    $voorraad = $_POST['voorraad'];
    $stmt = $pdo->prepare("INSERT INTO producten (naam, voorraad) VALUES (:naam, :voorraad)");
    $stmt->execute(['naam' => $naam, 'voorraad' => $voorraad]);
    header("Location: producten.php");
    exit();
}

// verwijderen van product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $stmt = $pdo->prepare("DELETE FROM producten WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    header("Location: producten.php");
    exit();
}

// haal alle producten op
$stmt = $pdo->query("SELECT * FROM producten");
$producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
include_once( 'includes/header.php' );
?>
<div class="container mt-5">
    <h1>Producten Beheer</h1>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="naam" class="form-control" placeholder="Product naam" required>
            <input type="number" name="voorraad" class="form-control" placeholder="Voorraad" required>
            <button type="submit" name="add_product" class="btn btn-primary">Toevoegen</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Voorraad</th>
            <th>Actie</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($producten as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['product_id']) ?></td>
                <td><?= htmlspecialchars($product['naam']) ?></td>
                <td><?= htmlspecialchars($product['voorraad']) ?></td>
                <td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <button type="submit" name="delete_product" class="btn btn-danger">Verwijderen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include_once ('includes/footer.php') ?>

