<?php
// Connexion à la base de données via MySQLi
$host = "localhost"; // Remplace par ton hôte MySQL
$user = "cvgmfvga_commerce";      // Remplace par ton nom d'utilisateur MySQL
$password = "Sio2LesGoat";      // Remplace par ton mot de passe MySQL
$database = "cvgmfvga_site_e_commerce"; // Remplace par le nom de ta base de données

// Connexion à la base de données
$connexion = mysqli_connect($host, $user, $password, $database);

// Vérification de la connexion
if ($connexion) {
    // Récupération des informations d'identification
    $email = mysqli_real_escape_string($connexion, $_POST['email']);
    $motdepasse = mysqli_real_escape_string($connexion, $_POST['motdepasse']);

    // Préparer la requête SQL pour vérifier les informations d'identification
    $requete = "SELECT * FROM clients WHERE email = '$email' AND mdp = '$motdepasse'";
    $resultatRequete = mysqli_query($connexion, $requete);

    // Vérifier si la requête a retourné des résultats
    if ($resultatRequete && mysqli_num_rows($resultatRequete) > 0) {
        // Connexion réussie, démarrer la session
        session_start();
        $_SESSION['email'] = $email; // Stocker l'email dans la session

        // Rediriger vers une page d'accueil ou un tableau de bord
        header("Location: ../index.php");
        exit();
    } else {
        // Informations d'identification invalides
        echo "Email ou mot de passe incorrect.";
    }
} else {
    // Affichage d'un message en cas d'échec de la connexion
    echo "Échec de la connexion à la base de données : " . mysqli_connect_error();
}

// Fermeture de la connexion à la base de données
mysqli_close($connexion);
?>
