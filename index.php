<?php
session_start(); // Démarrer la session

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

// Vérifier si l'utilisateur a demandé à se déconnecter
if (isset($_GET['logout'])) {
    session_destroy(); // Détruire la session
    header("Location: connexion.php"); // Rediriger vers la page de connexion
    exit();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Acceuil</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>
<body>
<nav class="navbar">
    <a href="index.php"><img src="img/logo.png" alt="logo"></a>
    <h1>Céramique</h1>
    <div class="navright">
        <a href="php/panier.php"><img src="img/shopping-cart.svg" alt="panier"></a>
        
        <?php if ($email): ?>
            <a href="index.php?logout=true" style="display: flex; align-items: center; gap: 10px;">
                <!-- Vérifie si la photo de profil existe dans la session -->
                <?php if (isset($_SESSION['photo_profil']) && !empty($_SESSION['photo_profil'])): ?>
                    <img src="uploads/<?= htmlspecialchars($_SESSION['photo_profil']) ?>" alt="Photo de profil" width="40" height="40" style="border-radius: 50%;">
                <?php else: ?>
                    <img src="img/default-avatar.png" alt="Photo de profil par défaut" width="40" height="40" style="border-radius: 50%;">
                <?php endif; ?>
                <span><?php echo htmlspecialchars($email); ?></span>
            </a>
        <?php else: ?>
            <!-- Afficher les liens pour s'inscrire ou se connecter si l'utilisateur n'est pas connecté -->
            <a href="html/inscription.html">Inscription</a>
            <a href="html/connexion.html">Connexion</a>
        <?php endif; ?>
    </div>
</nav>
<div>
    <h2 class="titreAcceuil">Site de vente de c&#233;ramique</h2>

    <img class="banner" src="img/accueil.jpg" alt="imgAccueil">
    <!--- article -->

    <div class="articleDiv">
        <article>
            <img src="img/Oiseau-Ceramique.jpg" alt="">
            <p>Oiseau d&#233;co</p>
            <p>Oiseau d&#233;co fait a la main</p>
            <p>30€</p>
        </article>

        <article>
            <img src="img/vase.jpg" alt="">
            <p>Vases Sculpturaux pour une D&#233;coration Raffin&#233;e</p>
            <p>D&#233;couvrez notre collection exclusive de vases sculpturaux en terre cuite.</p>
            <p>50€</p>
        </article>

        <article>
            <img src="img/tasses.jpg" alt="">
            <p>Ensemble de Tasses à Th&#233; Artisanales</p>
            <p>Offrez-vous une exp&#233;rience de th&#233; authentique avec notre ensemble de tasses à th&#233; artisanales.</p>
            <p>120€</p>
        </article>
    </div>
    <!--ajout d'un bouton vers les articles-->
    <div class="toutArticle">
    <!-- lancement article.php -->
    <button class="buttonstylerond" onclick="window.location.href='php/articles.php'">
        <span>Afficher tout les articles</span>
        <svg width="15px" height="10px" viewBox="0 0 13 10">
            <path d="M1,5 L11,5"></path>
            <polyline points="8 1 12 5 8 9"></polyline>
        </svg>
    </button>
    </div>
    
    <!-- Ajouter un bouton "Laisser un avis" -->
    <div class="toutAvis">
    <!-- Lien vers la page pour laisser un avis (avis.php) dans un nouvel onglet -->
    <button class="buttonstylerond" onclick="window.open('php/avis.php', '_blank')">
        <span>Laisser un avis</span>
        <svg width="15px" height="10px" viewBox="0 0 13 10">
            <path d="M1,5 L11,5"></path>
            <polyline points="8 1 12 5 8 9"></polyline>
        </svg>
    </button>
</div>

    <!--presentation de la pratique -->
    <div class="presentation">
        <h3>Pr&#233;sentation de la pratique</h3>
        <p>La poterie, ancienne pratique artistique, repose sur l'argile model&#233;e à la main ou au tour. Explorez les
            diff&#233;rentes techniques, du tournage à la construction, avec des outils spécialisés. Après le modelage, le
            séchage progressif et la cuisson à des températures spécifiques sont essentiels. Ajoutez une touche
            artistique avec la décoration et l'émaillage. La poterie offre une fusion unique de créativité et de
            maîtrise technique, permettant aux artistes de donner vie à leur vision à travers ce medium polyvalent.</p>
    </div>

    <footer>
        <a class="contact" href="html/contact.html">Contact</a>
        <a class="apropos" href="html/aProrpos.html">A propos</a>
    </footer>
</div>
</body>
</html>
