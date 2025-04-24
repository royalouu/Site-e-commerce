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

    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "cvgmfvga_commerce", "Sio2LesGoat", "cvgmfvga_site_e_commerce");

    // Vérifier la connexion
    if (!$connexion) {
        die("Échec de la connexion : " . mysqli_connect_error());
    }

    // Requête pour récupérer la photo de profil
    $query = "SELECT photo_profil FROM clients WHERE email = '$email'";
    $result = mysqli_query($connexion, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['photo_profil'] = $row['photo_profil']; // Stocker le chemin de la photo dans la session
    }

    mysqli_close($connexion);
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
    <style>
        body {
            background-image: url('../img/hub-1985520_960_720.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: center;
            margin: 0;
            font-family: sans-serif;
        }
        table {
            display: flex;
            justify-content: center;
            padding : 10vh;
            margin: 0;
        }
        th, td {
            border: 1px solid rgb(160 160 160);
            padding: 8px 10px;
        }
        th[scope='col'] {
            background-color: #505050;
            color: #fff;
        }
        th[scope='row'] {
            background-color: #d6ecd4;
        }
        td {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            border: 2px solid rgb(140 140 140);
            font-family: sans-serif;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }
        caption {
            caption-side: bottom;
            padding: 10px;
        }
        button {
         border: none;
         color: #fff;
         background-image: linear-gradient(228deg, #97B3AD, #CC8E6C);
         border-radius: 20px;
         background-size: 100% auto;
         font-family: inherit;
         font-size: 17px;
         padding: 0.6em 1.5em;
        }
        button:hover {
         background-position: right center;
         background-size: 200% auto;
         animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
         0% {
          box-shadow: 0 0 0 0 #97B3AD;
         }
         70% {
          box-shadow: 0 0 0 10px rgb(218 103 68 / 0%);
         }
         100% {
          box-shadow: 0 0 0 0 rgb(218 103 68 / 0%);
         }
        }
    </style>
</head>
<nav class=\"navbar\">
    <a href=\"../index.php\"><img src=\"../img/logo.png\" alt=\"logo\"></a>
    <h1>Céramique</h1>
    <div class=\"navright\">
        <a href=\"panier.php\"><img src=\"../img/shopping-cart.svg\" alt=\"panier\"></a>";
        if ($email) {
            // Afficher la photo de profil si elle existe
            if (isset($_SESSION['photo_profil']) && !empty($_SESSION['photo_profil'])) {
                echo '<a href="index.php?logout=true" style="display: flex; align-items: center; gap: 10px;">';
                echo '<img src="../uploads/' . htmlspecialchars($_SESSION['photo_profil']) . '" alt="Photo de profil" width="40" height="40" style="border-radius: 50%;">';
                echo '<span>' . htmlspecialchars($email) . '</span>';
                echo '</a>';
            } else {
                // Photo de profil par défaut si l'utilisateur n'a pas de photo
                echo '<a href="index.php?logout=true" style="display: flex; align-items: center; gap: 10px;">';
                echo '<img src="../uploads/default-avatar.png" alt="Photo de profil par défaut" width="40" height="40" style="border-radius: 50%;">';
                echo '<span>' . htmlspecialchars($email) . '</span>';
                echo '</a>';
            }
        } else {
            echo "<a href=\"../html/inscription.html\">Inscription</a>
                  <a href=\"../html/connexion.html\">Connexion</a>";
        }
echo "  </div>
</nav>
<body>";

// Requête SQL pour récupérer toutes les lignes de la table articles
$query = "SELECT * FROM articles ORDER BY IdArticle ASC";
$result = mysqli_query($connection, $query);

// Vérifier si la requête a réussi
if (!$result) {
    die("Échec de la requête : " . mysqli_error($connection));
}

// Affichage du tableau HTML avec les données récupérées
echo "<form method='post'>
        <table border='1'>
            <tr>
                <th>Article Num&#233;ro</th>
                <th>Nom article</th>
                <th>Prix</th>
                <th>Quantit&#233; voulue</th>
                <th>Cat&#233;gorie article</th>
                <th>Description</th>
                <th>Image</th>
                <th>Commander</th>
            </tr>";

// Parcourir les résultats et afficher chaque ligne dans le tableau HTML
while ($row = mysqli_fetch_assoc($result)) {
    $articleId = $row['IdArticle']; // Récupérer l'ID de l'article
    $quantiteStock = $row['quantiteStockArticle']; // Récupérer la quantité en stock de l'article

    echo "<tr>
            <td>{$row['IdArticle']}</td>
            <td>{$row['nomArticle']}</td>
            <td>{$row['prixArticle']} &euro;</td>
            <td>
                <select name='quantite_$articleId' id='quantite_$articleId'>
            ";
    // Boucle pour générer les options du menu déroulant en fonction de la quantité disponible
    if ($quantiteStock > 0) {
        for ($i = 1; $i <= $quantiteStock; $i++) {
            echo "<option value='$i'>$i</option>";
        }
    } else {
        echo "<option value='0' disabled>Rupture de stock</option>";
    }

    echo "</select>
            </td>
            <td>{$row['categorieArticle']}</td>
            <td>{$row['descriptionArticle']}</td>
            <td><img src='../img/imageproduit/{$row['imageUrlArticle']}' alt='image produit' width='100' height='100'></td>
            <td>
                <button type=\"submit\" name=\"ajouter_panier_$articleId\">Ajouter au panier</button>
                <input type=\"hidden\" name=\"article_id_$articleId\" value=\"$articleId\">
            </td>
          </tr>";
}
echo "</table></form>";

// Gestion du panier
foreach ($_POST as $key => $value) {
    if (strpos($key, 'ajouter_panier_') !== false) {
        $articleId = str_replace('ajouter_panier_', '', $key);
        $quantite = $_POST["quantite_$articleId"];

        // Récupérer la quantité en stock de l'article
        $query = "SELECT quantiteStockArticle FROM articles WHERE idArticle = $articleId";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $quantiteStock = $row['quantiteStockArticle'];

        // Calculez la quantité totale de cet article dans le panier
        $quantiteDansPanier = 0;
        if (isset($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $item) {
                if ($item['id'] == $articleId) {
                    $quantiteDansPanier += $item['quantite'];
                }
            }
        }

        // Vérifiez si la quantité totale (dans le panier + demandée) est supérieure à la quantité en stock
        if ($quantite + $quantiteDansPanier > $quantiteStock) {
            echo "<script>alert('Il n\'y a pas assez de stock pour ajouter cette quantit\u00e9 de cet article au panier.');</script>";
            continue;
        }

        // Vérifiez si le panier est déjà créé dans la session
        if (isset($_SESSION['panier'])) {
            $found = false;
            foreach ($_SESSION['panier'] as &$item) {
                if ($item['id'] == $articleId) {
                    $item['quantite'] += $quantite;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['panier'][] = array('id' => $articleId, 'quantite' => $quantite);
            }
        } else {
            $_SESSION['panier'] = array(array('id' => $articleId, 'quantite' => $quantite));
        }
    }
}

// Fermer la connexion MySQLi
mysqli_close($connection);
echo "</body></html>
<footer>
    <a class=\"contact\" href=\"..\html\contact.html\">Contact</a>
    <a class=\"apropos\" href=\"..\html\aProrpos.html\">À propos</a>
</footer>";
?>
