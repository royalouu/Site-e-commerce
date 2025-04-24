<?php
// Connexion à la base de données
$host = "localhost"; 
$user = "cvgmfvga_commerce"; 
$password = "Sio2LesGoat"; 
$database = "cvgmfvga_site_e_commerce"; 

$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$successMessage = "";
$errorMessage = "";

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'], $_POST['nouveau_mdp'], $_POST['confirmation_mdp'])) {
        $email = $_POST['email'];
        $nouveau_mdp = $_POST['nouveau_mdp'];
        $confirmation_mdp = $_POST['confirmation_mdp'];

        // Vérifier si l'email existe dans la base de données
        $sql = "SELECT email FROM clients WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // L'email existe, vérifier si les mots de passe correspondent
            if ($nouveau_mdp === $confirmation_mdp) {
                // Mettre à jour le mot de passe
                $sql_update = "UPDATE clients SET mdp = ? WHERE email = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ss", $nouveau_mdp, $email);

                if ($stmt_update->execute()) {
                    // Pop-up de confirmation et redirection
                    echo "<script>alert('Votre mot de passe a été modifié avec succès !');</script>";
                    echo "<script>window.location.href = '../html/connexion.html';</script>";
                    exit();
                } else {
                    $errorMessage = "Erreur lors de la mise à jour du mot de passe.";
                }
            } else {
                $errorMessage = "Les mots de passe ne correspondent pas.";
            }
        } else {
            $errorMessage = "L'email fourni n'existe pas dans notre base de données.";
        }
    } else {
        $errorMessage = "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
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
            <h2>Réinitialisation de votre mot de passe</h2>

            <!-- Affichage des messages d'erreur -->
            <?php if (!empty($errorMessage)) { ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
            <?php } ?>

            <!-- Formulaire HTML -->
            <form method="post" action="">
                <div class="user-box">
                    <input type="email" id="email" name="email" required>
                    <label for="email">Email :</label>
                </div>

                <div class="user-box">
                    <input type="password" id="nouveau_mdp" name="nouveau_mdp" required>
                    <label for="nouveau_mdp">Nouveau mot de passe :</label>
                </div>

                <div class="user-box">
                    <input type="password" id="confirmation_mdp" name="confirmation_mdp" required>
                    <label for="confirmation_mdp">Confirmation du nouveau mot de passe :</label>
                </div>

                <a href="#" onclick="this.closest('form').submit()">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Modifier le mot de passe
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
