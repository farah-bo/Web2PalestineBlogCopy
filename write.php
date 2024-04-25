<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
// Set the 'X-Content-Type-Options' header
header("X-Content-Type-Options: nosniff");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $image = $_FILES['image']['name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    
    // Connexion à la base de données
    $dsn = 'mysql:host=localhost;dbname=blog';
    $utilisateur = 'root';
    $mot_de_passe = '';
    
    try {
        $connexion = new PDO($dsn, $utilisateur, $mot_de_passe);
        
        //Préparer la requête d'insertion
        $requete = $connexion->prepare("INSERT INTO articles (titre, contenu ,image_path) VALUES (:titre, :contenu , :imagePath)");
        
        // Exécuter la requête
        $requete->execute(array(':titre' => $titre, ':contenu' => $contenu, ':imagePath' => $image));
        
        // Return response
        echo 'Article publié avec succès!';
    } catch (PDOException $e) {
        echo 'Erreur lors de l\'insertion de l\'article : ' . $e->getMessage();
    }
    
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Blog</title>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body{
            background-image: url("pall.jpg");
            background-size: cover;
            background-attachment: fixed;
        }
        .container {
            max-width: 800px; 
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .container h3 {
            margin-top: 0; 
        }

        .container p {
            margin-bottom: 0; 
        }
        h2{
            text-align:center;
            color:white;
        }
        h1{
            text-align:center;
            color:white;
        }
        a{
            text-decoration:none;
            color:white;
        }
        img{
            max-width:600px;
        }
    </style>
</head>
<body>
    
    <h1 class="mt-5 mb-4">Welcome To Your Blog</h1>

    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        <a href="write.php">Create an Article</a>
    </button><br><br>
    
    <button type="button" class="btn btn-primary">
    <a href="chat.php">Chat</a>
    </button><br><p></p><br>
    <button type="button" class="btn btn-danger">
        <a href="home.html">Disconnect</a>
    </button><br><br>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nouvel article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for creating a new article -->
                    <form id="blogForm" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="titre" class="alert alert-info">Titre de l'article :</label>
                            <input type="text" class="form-control" id="titre" name="titre">
                        </div>
                        <div class="form-group">
                            <label for="contenu">Contenu :</label>
                            <textarea class="form-control" id="contenu" name="contenu" rows="4" cols="50"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image :</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Publier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2>Articles</h2>
    <?php
    // Connexion à la base de données
    $dsn = 'mysql:host=localhost;dbname=blog';
    $utilisateur = 'root';
    $mot_de_passe = '';
    
    try {
        $connexion = new PDO($dsn, $utilisateur, $mot_de_passe);
        
        // Sélection des articles
        $requete = "SELECT * FROM articles";
        $resultat = $connexion->query($requete);
        
        // Affichage des articles
        while ($article = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="container">';
            echo "<h3>" . $article['titre'] . "</h3>";
            echo "<p>" . $article['contenu'] . "</p>";
            echo "<img class='img' src='" . $article['image_path'] . "'><br>";
            echo "<button class='btn btn-primay'>Like</button>";
            echo '</div>';

        }
    } catch (PDOException $e) {
        echo 'Échec de la connexion : ' . $e->getMessage();
    }
    ?>
    
    <!-- JavaScript for handling form submission -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("blogForm");
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                var formData = new FormData(form);
                var xhr = new XMLHttpRequest();
                xhr.open(form.method, form.action);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Reset form after successful submission
                        form.reset();
                        // Close modal after submission
                        var modal = document.getElementById("exampleModal");
                        var bootstrapModal = new bootstrap.Modal(modal);
                        bootstrapModal.hide();
                        // Reload page to display new articles
                        location.reload();
                    } else {
                        console.error('Request failed. Status: ' + xhr.status);
                    }
                };
                xhr.send(formData);
            });

            // Open modal automatically
            var modal = document.getElementById("exampleModal");
            var bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();

            var closeBtn = document.getElementsByClassName("close")[0];
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }
            window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
    

</body>
</html>
