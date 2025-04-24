<?php
session_start();
$debugMode = true;

// Connexion à la base de données via MySQLi
$host = "localhost"; // Remplace par ton hôte MySQL
$user = "cvgmfvga_commerce";      // Remplace par ton nom d'utilisateur MySQL
$password = "Sio2LesGoat";      // Remplace par ton mot de passe MySQL
$database = "cvgmfvga_site_e_commerce"; // Remplace par le nom de ta base de données

$connection = mysqli_connect($host, $user, $password, $database);

// Vérifier la connexion
if (!$connection) {
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
}

// Forcer l'encodage UTF-8
mysqli_set_charset($connection, "utf8mb4");

// Vérification de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy(); // Détruire la session
    header("Location: index.php"); // Rediriger vers la page d'accueil
    exit();
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Récupérer l'email de l'utilisateur connecté
} else {
    $email = null; // L'utilisateur n'est pas connecté
}

echo "<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <title>Article</title>
    <link rel=\"stylesheet\" href=\"../style/style.css\">
    <link rel=\"icon\" href=\"../img/logo.png\" type=\"image/x-icon\">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<nav class=\"navbar\">
    <a href=\"../index.php\"><img src=\"../img/logo.png\" alt=\"logo\"></a>
    <h1>C&#233;ramique</h1>
    <div class=\"navright\">
        <a href=\"panier.php\"><img src=\"../img/shopping-cart.svg\" alt=\"panier\"></a>";
        if ($email) {
            echo "<a href=\"index.php?logout=true\">Se déconnecter (".$email.")</a>";
        } else {
            echo "<a href=\"../html/inscription.html\">Inscription</a>
                  <a href=\"../html/connexion.html\">Connexion</a>";
        }
echo "  </div>
</nav>
<body>";

// Formulaire d'avis
if ($email) {
    echo "
        <form method='post' action='avis.php'>
            <label for='product_id'>Produit:</label>
            <select name='product_id' required>
                <!-- Ajoutez ici une liste déroulante des produits -->
            </select><br><br>

            <label for='rating'>Note:</label>
            <select name='rating' required>
                <option value='1'>1 étoile</option>
                <option value='2'>2 étoiles</option>
                <option value='3'>3 étoiles</option>
                <option value='4'>4 étoiles</option>
                <option value='5'>5 étoiles</option>
            </select><br><br>

            <label for='comment'>Commentaire:</label><br>
            <textarea name='comment' required></textarea><br><br>

            <button type='submit' name='submit_review'>Soumettre l'avis</button>
        </form>
    ";
} else {
    echo "Veuillez vous connecter pour laisser un avis.";
}

// Gestion de la soumission de l'avis
if (isset($_POST['submit_review'])) {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Récupérer l'ID du client à partir de l'email
    $query = "SELECT IDCLIENT FROM client WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $client_id = $row['IDCLIENT'];

        // Insérer l'avis dans la base de données
        $query = "INSERT INTO avis (IDPRODUIT, IDCLIENT, NOTE, COMMENTAIRE) VALUES ('$product_id', '$client_id', '$rating', '$comment')";
        if (mysqli_query($connection, $query)) {
            echo "Avis soumis avec succès!";
        } else {
            echo "Erreur lors de l'envoi de l'avis: " . mysqli_error($connection);
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
}

// Fermer la connexion MySQLi
mysqli_close($connection);

echo "</body></html>";
?>
