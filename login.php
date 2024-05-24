<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baosem";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<script>alert("La connexion a échoué : ' . $e->getMessage() . '")</script>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM role WHERE username = :username";
        $st = $conn->prepare($sql);
        $st->bindParam(':username', $username);
        $st->execute();
        $result = $st->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            session_start();
            $_SESSION['username'] = $result['username'];
            $_SESSION['role'] = $result['role'];
            $_SESSION['approbation'] = $result['approbation'];
            $_SESSION['id'] = $result['id'];
			if (($_SESSION['role'] == "admin") || ($_SESSION['role'] == "superadmin") || ($_SESSION['role'] == "user" && $_SESSION['approbation'] != "0")){
              header('Location: structure.php');}
            else if ($_SESSION['role'] == "user" && $_SESSION['approbation'] == "0") {
			  header('Location: employee.php');
           exit; } } else {
            echo '<script>alert("Nom d\'utilisateur ou mot de passe incorrect")</script>';
           }
			
          
           }
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="login.php" method="POST">
                    <div class="login__field">
                        <ion-icon name="person" style="font-size:25px; color:#3d25f5;"></ion-icon>
                        <input type="text" name="username" class="login__input" placeholder="Nom d'utilisateur">
                    </div>
                    <div class="login__field">
                    <ion-icon name="bag" style="font-size:25px; color:#3d25f5;"></ion-icon>
                        <input type="password" name="password" class="login__input" placeholder="Mot de passe">
                    </div>
                    <button class="button login__submit" type="submit" name="connexion">
                        <span class="button__text">Se connecter</span>
                        <ion-icon name="enter" style="margin-left:70px;font-size:35px; color:#3d25f5;"></ion-icon>
                    </button>
                </form>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
