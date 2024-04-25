<?php
session_start();

class User {
    private $nom;
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "blog";

    public function __construct($nom) {
        $this->nom = $nom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function fetchUserFromDatabase() {
        try {
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $requete = $pdo->prepare("SELECT * FROM users WHERE nom=:nom");
            $requete->bindParam(':nom', $this->nom);
            $requete->execute();
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            return $user;
        } catch (PDOException $error) {
            echo 'Erreur de connexion : ' . $error->getMessage();
        }
    }
}

if (isset($_POST['buttonM'])) {
    $nom = $_POST['nom'];
    $user = new User($nom);
    $user_data = $user->fetchUserFromDatabase();
    if ($user_data) {
        echo "<script>alert('Nom: " . $user_data['nom'] . "\\nMail: " . $user_data['mail'] . "\\nMot de passe: " . $user_data['pass1'] . "');</script>";
        
    } else {
        echo '<script>alert("Aucun utilisateur trouvé avec ce nom")</script>';
    }
}

       
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Sign Up Form</title>
    <style>
        body { 
            background-image: url("img1.jpg") ;
            background-size: cover;
            background-attachment: fixed;
            background-attachment: fixed;
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    
        h2 {
            text-align: center;
            font-size: 45px;
            color: white;
            margin-top: 30px;
            margin-bottom: 30px;
        }



       .input-group{
        position: relative;
        margin:30px 0;
        border-bottom: 2px solid #fff;
        }
        .input-group label{
            position: absolute;
            top:50%;
            left:5px;
            transform: translateY(-50%);
            font-size: 16px;
            color:#fff;
            pointer-events: none;
            transform: .5s;
        }

        .input-group input{
            width: 320px; 
            height:40px;
            font-size: 16px;
            color: #fff;
            padding: 0 5px;
            background: transparent;
            border:none;
            outline: none;
        }
        .input-group input:focus~label,
        .input-group input:valid~label{
            top:-5px
        }
        .remember{
            margin:-5px 0 15px 5px;
        }
        .remember label {
             color: #fff;
             font-size: 14px;
        }
        .remember label input {
            accent-color: #0ef;
        }
        button{
            position: relative;
            width: 100%;
            height: 40px;
            background: #0ef;
            box-shadow: 0 0 10px #0ef;
            font-size: 16px;
            color: #000;
            font-weight: 500;
            cursor: pointer;
            border-radius: 30px;
            border: none;
            outline: none;
        }
        .ins{
            position: relative;
            width: 400px;
            height: 550px;
            background: transparent;
            box-shadow: 0 0 50px #0ef;
            border-radius: 20px;
            padding: 40px;
          /* overflow: hidden;*/
        }
        .form-ins{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            transition: 1s ease-in-out ;
        }
        .sign-up{
            font-size: 14px;
            text-align: center;
            margin: 15px 0;
        }
        .sign-up p{
            color: #fff;
        }
        .sign-up p a {
            color: #0ef;
            text-decoration: none;
            font-weight: 500;
        }
        .sign-up p a:hover {
            text-decoration: underline;
        }


    </style>
</head>
<body>
    <div class="menu">
        <nav>
            <img src="icon.jpg" class="logo">
            <ul>
                <li><a href="home.html">Déconnexion</a></li>
            </ul>
        </nav>
        <div class="ins">
            <div class="form-ins sign-up">
                <form method="POST">
                    <h2>Entrer le nom d'utilisateur</h2>
                    <div class="input-group">
                        <input type="text" name="nom" required>
                        <label for="nom">Nom d'utilisateur</label>
                    </div>
                    <button type="submit" name="buttonM">Voir</button>
                    <button>Annuler</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
