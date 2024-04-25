<?php
session_start();
   
$host = "localhost";
$username = "root";
$password = "";
$database = "blog";

$message = ""; 

if (isset($_POST['titre'])){
    try {
        $pdo = new PDO("mysql:host=$host; dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        $titre = $_POST['titre'];
        
        
        $requete = $pdo->prepare("DELETE FROM Articles WHERE titre = :titre");
        $requete->bindParam(':titre', $titre);
        $requete->execute();

        $count = $requete->rowCount();

        if ($count > 0) {
            echo '<script>alert("Article deleted successfully");</script>';
            //  sleep(3);
            header("location:crud.html");
            
        } else {
            echo '<script>alert("No article found with that username");</script>';
        }
       
    } catch (PDOException $error) {
        $message = $error->getMessage();
    }
}
?>


 






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Sign Up Form</title>
        <link rel="icon" type="image/jpg" href="icon.jpg">
        <script language="javascript" src=""></script>

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
            <li><a href="home.html">Disconnect</a></li>
            
        </ul>
    </nav>
    <div class="ins">
    <div class="form-ins sign-up">
        <form method="POST">
            <h2>Delete an Article</h2>
            <div class="input-group">
                <input type="text" name="titre" required>
                <label for="titre+">Article's Title</label>
            </div>
            
            
            <button type="submit" onclick="return check()">Delete</button>
            <button><a href ="crud.html">Cancel</a></button>

            
        </div>
</body>
</html>



