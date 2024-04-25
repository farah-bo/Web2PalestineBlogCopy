<?php
session_start();
   
$host = "localhost";
$username = "root";
$password = "";
$database = "blog";

$message = ""; // Déclarer la variable $message

if (isset($_POST['new_nom'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupérer les valeurs des champs 'nom' et 'new_nom' depuis $_POST
        $nom = $_GET['username'];
        echo $nom;
        $new_nom = $_POST['new_nom'];
        $new_mail = $_POST['new_mail'];
        $new_pass = $_POST['new_pass'];
        echo $new_nom;
        echo $new_mail;
        echo $new_pass;

        // Utiliser les valeurs dans la requête UPDATE
        $requete = $pdo->prepare("UPDATE users SET nom = :new_nom, mail = :new_mail, pass1 = :new_pass , type = 'user' WHERE nom = :nom");
        
        $requete->bindParam(':new_nom', $new_nom);
        $requete->bindParam(':new_mail', $new_mail);
        $requete->bindParam(':new_pass', $new_pass);
        $requete->bindParam(':nom', $nom); // Utilisation de la variable $nom pour filtrer par nom
        
        $requete->execute();
        
        $count = $requete->rowCount();

        //if ($count > 0) {
            echo '<script>alert("User updated successfully")</script>';
            sleep(3);
            header("location:crud.html");
            exit(); // Arrêt de l'exécution après la redirection
        /*} else {
            echo '<script>alert("No user found with that username");</script>';
        }*/
    } catch (PDOException $error) {
        $message = $error->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="vendor\twbs\bootstrap\dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Update User Form</title>
    <style>
        a{
            color:white;
        }
    </style>

</head>
<body>
    <h2 class="alert alert-info">Update User</h2>
    <form method="POST">

        <label for="new_nom">New Username:</label>
        <input type="text" name="new_nom" required><br>

        <label for="new_pass">New password:</label>
        <input type="password" name="new_pass" required><br>

        <label for="new_mail">New mail:</label>
        <input type="email" name="new_mail" required><br>

        <button type="submit" class="btn btn-primary">Update</button>
        <button class="btn btn-primary"><a href="crud.html">Cancel</a></button>
    </form>
</body>
</html>
