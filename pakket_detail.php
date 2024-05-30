<?php
include 'config.php'; // database contectie en ander config

// terug naar login als je niet ingelogd bent
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: login.php");
    exit();
}

// pakket_id van de url
$pakket_id = isset($_GET['pakket_id']) ? $_GET['pakket_id'] : null;

// de geselecteerde pakket met klant gegevens uit database ophallen
$stmt = $pdo->prepare("SELECT pakketten.*, klanten.naam FROM pakketten 
    LEFT JOIN klanten ON pakketten.klant_id = klanten.klant_id WHERE pakketten.pakket_id = :pakket_id");
$stmt->execute(['pakket_id' => $pakket_id]);
$pakket = $stmt->fetch();

// alle product in een pakket uit database hallen
$stmt_products = $pdo->prepare("SELECT pakket_producten.*, producten.naam FROM pakket_producten 
    LEFT JOIN producten ON pakket_producten.product_id = producten.product_id WHERE pakket_producten.pakket_id = :pakket_id");
$stmt_products->execute(['pakket_id' => $pakket_id]);
$producten = $stmt_products->fetchAll();

// alle producten uit database ophallen
$stmt_all_products = $pdo->query("SELECT * FROM producten");
$all_products = $stmt_all_products->fetchAll();

// product aan pakket toevogen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pakket_product_toevogen'])) {
    $product_id = $_POST['product_id'];
    $aantal = $_POST['aantal'];
    $stmt_add = $pdo->prepare("INSERT INTO pakket_producten (pakket_id, product_id, aantal) VALUES (:pakket_id, :product_id, :aantal)");
    $stmt_add->execute(['pakket_id' => $pakket_id, 'product_id' => $product_id, 'aantal' => $aantal]);
    header("Location: pakket_detail.php?pakket_id=$pakket_id");
    exit();
}

// ophallen van header
include_once( 'includes/header.php' );
?>

<div class="container mt-5">
    <h1>Pakket Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Pakket ID: <?= $pakket['pakket_id'] ?></h5>
            <p class="card-text"><strong>Klant:</strong> <?= $pakket['naam'] ?></p>
            <p class="card-text"><strong>Datum:</strong> <?= $pakket['datum'] ?></p>
            <p class="card-text"><strong>Status:</strong> <?= $pakket['status'] ?></p>
        </div>
    </div>
    <h2>Voeg product</h2>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <select name="product_id" class="form-select" required>
                <option value="">Selecteer product</option>
                <?php foreach ($all_products as $product): ?>
                    <option value="<?= $product['product_id'] ?>"><?= $product['naam'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="aantal" class="form-control" placeholder="Aantal" required>
            <button type="submit" name="pakket_product_toevogen" class="btn btn-primary">Toevoegen</button>
        </div>
    </form>
    
    <h2>Pakket producten</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Product ID</th>
            <th>Naam</th>
            <th>Aantal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($producten as $product): ?>
            <tr>
                <td><?= $product['product_id'] ?></td>
                <td><?= $product['naam'] ?></td>
                <td><?= $product['aantal'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="pakketten.php" class="btn btn-primary">Terug naar Pakketten</a>
</div>
<?php include_once ('includes/footer.php') ?>

