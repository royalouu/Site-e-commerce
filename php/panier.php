<?php
session_start();
// Connexion à la base de données via MySQLi
$host = "localhost"; // Remplace par ton hôte MySQL
$user = "cvgmfvga_commerce";      // Remplace par ton nom d'utilisateur MySQL
$password = "Sio2LesGoat";      // Remplace par ton mot de passe MySQL
$database = "cvgmfvga_site_e_commerce"; // Remplace par le nom de ta base de données

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Récupérer l'email de l'utilisateur connecté

    // Connexion à la base de données pour récupérer la photo de profil de l'utilisateur
    $connection = mysqli_connect($host, $user, $password, $database);
    if (!$connection) {
        die("Erreur de connexion : " . mysqli_connect_error());
    }

    $query = "SELECT photo_profil FROM clients WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $photo_profil = $row['photo_profil'] ?? 'default-avatar.png'; // Utilise une photo par défaut si l'utilisateur n'a pas de photo
    } else {
        $photo_profil = 'default-avatar.png'; // Photo par défaut en cas d'erreur
    }
    mysqli_close($connection); // Fermer la connexion
} else {
    $email = null; // L'utilisateur n'est pas connecté
    $photo_profil = 'default-avatar.png'; // Photo par défaut si l'utilisateur n'est pas connecté
}

echo "<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <title>Panier</title>
    <link rel=\"stylesheet\" href=\"../style/style.css\">
    <link rel=\"icon\" href=\"../img/logo.png\" type=\"image/x-icon\">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        /* Ajoute ton CSS ici */
    </style>
</head>
<body>
<nav class=\"navbar\">
    <a href=\"../index.php\"><img src=\"../img/logo.png\" alt=\"logo\"></a>
    <h1>Céramique</h1>
    <div class=\"navright\">
        <a href=\"panier.php\"><img src=\"../img/shopping-cart.svg\" alt=\"panier\"></a>";
        
        if ($email) {
            // Afficher la photo de profil si l'utilisateur est connecté
            echo "<a href=\"panier.php?logout=true\" style=\"display: flex; align-items: center; gap: 10px;\">";
            echo "<img src=\"../uploads/$photo_profil\" alt=\"Photo de profil\" width=\"40\" height=\"40\" style=\"border-radius: 50%;\">";
            echo "<span>" . htmlspecialchars($email) . "</span>";
            echo "</a>";
        } else {
            // Afficher les liens pour s'inscrire ou se connecter si l'utilisateur n'est pas connecté
            echo "<a href=\"../html/inscription.html\">Inscription</a>
                  <a href=\"../html/connexion.html\">Connexion</a>";
        }

echo "</div>
</nav>
<h2>Contenu du panier</h2>";

// Afficher le tableau HTML avec les articles du panier
echo "<form method='post'>
        <table border='1'>
            <tr>
                <th>IdArticle</th>
                <th>Photo</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Supprimer</th>
            </tr>";

// Vérification si le panier existe dans la session
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $articleId = $item['id'];

        // Requête SQL pour récupérer les détails de l'article
        $connection = mysqli_connect($host, $user, $password, $database);
        $query = "SELECT * FROM articles WHERE idArticle = $articleId";
        $result = mysqli_query($connection, $query);

        // Vérifiez si la requête a réussi
        if (!$result) {
            die("Échec de la requête : " . mysqli_error($connection));
        }

        // Récupérez les détails de l'article
        $row = mysqli_fetch_assoc($result);

        // Affichez les détails de l'article
        echo "<tr>
                <td>".$row['IdArticle']."</td>
                <td><img src='../img/imageproduit/{$row['imageUrlArticle']}' alt='image produit' width='100' height='100'></td>
                <td>".$row['nomArticle']."</td>
                <td>".$row['prixArticle']." &#8364</td>
                <td>
                    <input type='number' name='quantite_$articleId' value='".$item['quantite']."' min='1' max='".$row['quantiteStockArticle']."'>
                    <button type='submit' name='mettre_a_jour' value='$articleId'>Mettre à jour</button>
                </td>
                <td><button type='submit' name='supprimer' value='$articleId'>Supprimer</button></td>
            </tr>";
    }
} else {
    // Affichez un message si le panier est vide
    echo "<tr><td colspan='6'>Le panier est vide</td></tr>";
}

// Bouton pour vider le panier
echo "<tr>
        <td colspan='5'></td>
        <td><button type='submit' name='reset_panier'>Vider le panier</button></td>
      </tr>";

echo "</table></form>";

// Fermer la connexion MySQLi
mysqli_close($connection);

echo "</body></html>
<footer>
    <a class=\"contact\" href=\"..\html\contact.html\">Contact</a>
    <a class=\"apropos\" href=\"..\html\aProrpos.html\">À propos</a>
</footer>";
?>
