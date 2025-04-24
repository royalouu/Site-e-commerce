<?php
// Connexion à la base de données
$host = "localhost"; // Remplace par ton hôte MySQL
$user = "cvgmfvga_commerce";      // Remplace par ton nom d'utilisateur MySQL
$password = "Sio2LesGoat";      // Remplace par ton mot de passe MySQL
$database = "cvgmfvga_site_e_commerce"; // Remplace par le nom de ta base de données

$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Variables pour les messages
$successMessage = "";
$errorMessage = "";

// Gérer le téléchargement de la photo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier_infos'])) {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $datenaissance = $_POST['datenaissance'];
    $photo = $_FILES['photo']; // Récupérer le fichier téléchargé

    // Vérification de la photo
if ($_FILES['photo']['error'] == 0) {
    $photoName = $_FILES['photo']['name'];
    $photoTmpName = $_FILES['photo']['tmp_name'];
    $photoSize = $_FILES['photo']['size'];
    $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Vérifier l'extension du fichier
    if (in_array(strtolower($photoExtension), $allowedExtensions)) {
        // Définir un nouveau nom de fichier pour la photo
        $photoNewName = uniqid() . '.' . $photoExtension;
        $photoDestination = '../uploads/' . $photoNewName;

        // Déplacer le fichier vers le dossier de destination
        if (move_uploaded_file($photoTmpName, $photoDestination)) {
            $photoPath = $photoDestination;
        } else {
            $errorMessage = "Erreur lors du téléchargement de la photo.";
        }
    } else {
        $errorMessage = "Le format de la photo n'est pas autorisé. Seuls les fichiers JPG, JPEG, PNG et GIF sont acceptés.";
    }
}


    // Vérifier si l'email existe dans la base de données
    $sql_check = "SELECT email FROM clients WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        $errorMessage = "L'email fourni n'existe pas dans notre base de données.";
    } else {
        // Mise à jour des informations
        if (isset($photoPath)) {
            // Si une nouvelle photo a été téléchargée, mettre à jour le chemin de la photo dans la base de données
            $sql_update = "UPDATE clients SET nom = ?, prenom = ?, adresse = ?, datenaiss = ?, photo = ? WHERE email = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssssss", $nom, $prenom, $adresse, $datenaissance, $photoPath, $email);
        } else {
            // Si aucune photo n'est téléchargée, ne pas modifier le champ photo
            $sql_update = "UPDATE clients SET nom = ?, prenom = ?, adresse = ?, datenaiss = ? WHERE email = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("sssss", $nom, $prenom, $adresse, $datenaissance, $email);
        }

        if ($stmt->execute()) {
            $successMessage = "Les informations ont été mises à jour avec succès.";
        } else {
            $errorMessage = "Erreur lors de la mise à jour : " . $stmt->error;
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les informations personnelles</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="../index.php"><img src="../img/logo.png" alt="logo"></a>
        <h1>Céramique</h1>
        <div class="navright">
            <a href="../php/panier.php"><img src="../img/shopping-cart.svg" alt="panier"></a>
            <a href="../html/inscription.html">Inscription</a>
            <a href="../html/connexion.html">Connexion</a>
        </div>
    </nav>

    <div class="login-page">
        <div class="login-box">
            <h2>Modifier les informations personnelles</h2>

            <!-- Affichage des messages de succès ou d'erreur -->
            <?php if (!empty($successMessage)) { ?>
                <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
            <?php } ?>
            <?php if (!empty($errorMessage)) { ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <div class="user-box">
                    <input type="email" id="email" name="email" required>
                    <label for="email">Email :</label>
                </div>

                <div class="user-box">
                    <input type="text" id="nom" name="nom" required>
                    <label for="nom">Nom :</label>
                </div>

                <div class="user-box">
                    <input type="text" id="prenom" name="prenom" required>
                    <label for="prenom">Prénom :</label>
                </div>

                <div class="user-box">
                    <input type="text" id="adresse" name="adresse" required>
                    <label for="adresse">Adresse :</label>
                </div>

                <div class="user-box">
                    <input type="date" id="datenaissance" name="datenaissance" required>
                    <label for="datenaissance"></label>
                </div>

                <!-- Champ pour télécharger une nouvelle photo de profil -->
                <div class="user-box">
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <label for="photo">Photo de profil :</label>
                </div>

                <input type="hidden" name="modifier_infos" value="1">

                <a href="#" onclick="this.closest('form').submit()">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Modifier
                </a>
            </form>

        </div>
    </div>

    <footer>
        <a class="contact" href="../html/contact.html">Contact</a>
        <a class="apropos" href="../html/aProrpos.html">À propos</a>
    </footer>
</body>
</html>
