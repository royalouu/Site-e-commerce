<?php
// Connexion à la base de données via MySQLi
$host = "localhost"; 
$user = "cvgmfvga_commerce";      
$password = "Sio2LesGoat";      
$database = "cvgmfvga_site_e_commerce"; 

$connexion = mysqli_connect($host, $user, $password, $database);
$message = '';

if ($connexion) {
    mysqli_set_charset($connexion, "utf8");

    // Récupération des données du formulaire
    $email = mysqli_real_escape_string($connexion, $_POST['email']);
    $motdepasse = mysqli_real_escape_string($connexion, $_POST['motdepasse']);
    $nom = mysqli_real_escape_string($connexion, $_POST['nom']);
    $prenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
    $adresse = mysqli_real_escape_string($connexion, $_POST['adresse']);
    $datenaissance = mysqli_real_escape_string($connexion, $_POST['datenaissance']);

// Gestion de l'upload de la photo de profil
$photo_profil = NULL; // Valeur par défaut

if (!empty($_FILES['photo_profil']['name'])) {
    $targetDir = "../uploads/"; // Dossier de stockage
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Créer le dossier s'il n'existe pas
    }

    // Récupérer l'extension du fichier téléchargé
    $fileType = strtolower(pathinfo($_FILES["photo_profil"]["name"], PATHINFO_EXTENSION)); 

    // Types de fichiers autorisés
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        // Renommer le fichier pour éviter les collisions
        $fileName = time() . "_" . basename($_FILES["photo_profil"]["name"]);
        $targetFilePath = $targetDir . $fileName; // Chemin complet du fichier

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $targetFilePath)) {
            $photo_profil = $fileName; // Enregistrer uniquement le nom du fichier dans la base de données
        } else {
            $message = "Erreur lors de l'upload de l'image.";
        }
    } else {
        $message = "Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.";
    }
}


    // Requête d'insertion avec la photo de profil
    $requete = "INSERT INTO clients (email, mdp, nom, prenom, adresse, datenaiss, photo_profil) 
                VALUES ('$email', '$motdepasse', '$nom', '$prenom', '$adresse', '$datenaissance', '$photo_profil')";

    // Exécution de la requête
    if (mysqli_query($connexion, $requete)) {
        $message = 'Inscription réussie !';
    } else {
        $message = 'Erreur lors de l\'inscription : ' . mysqli_error($connexion);
    }
} else {
    $message = 'Échec de la connexion à la base de données : ' . mysqli_connect_error();
}

// Fermeture de la connexion
mysqli_close($connexion);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script>
        alert("<?php echo $message; ?>");
        setTimeout(function() {
            window.location.href = '../html/inscription.html';  
        }, 3000);
    </script>
</head>
<body>
</body>
</html>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script>
        // Affichage de la pop-up avec le message PHP
        alert("<?php echo $message; ?>");
        // Optionnel : rediriger après 3 secondes
        setTimeout(function() {
            window.location.href = '../html/inscription.html';  // Redirige vers le formulaire d'inscription
        }, 3000);
    </script>
</head>
<body>
</body>
</html>
