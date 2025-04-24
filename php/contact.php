<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données et validation
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        // Préparation des données pour l'email
        $to = "timothe25140@gmail.com"; // Remplacez par votre adresse email
        $subject = "Nouveau message via le formulaire de contact";
        $body = "Vous avez reçu un message de $email :\n\n$message";
        $headers = "From: $email";

        // Envoi de l'email
        if (mail($to, $subject, $body, $headers)) {
            echo "Message envoyé avec succès.";
        } else {
            echo "Une erreur est survenue lors de l'envoi.";
        }
    } else {
        echo "Veuillez fournir une adresse email valide et un message.";
    }
} else {
    echo "Méthode de requête non autorisée.";
}
?>
