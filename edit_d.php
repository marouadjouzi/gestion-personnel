<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baosem";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['ajouter'])){
      $nom_description = $_POST['nom'];
      $valeur = $_POST['valeur'];
      if ($nom_description != NULL && $valeur != NULL){
      
      $sql = "INSERT INTO description (nom_description, valeur) 
              SELECT :nom_description, :valeur 
              FROM dual 
              WHERE NOT EXISTS (
                  SELECT 1 
                  FROM description 
                  WHERE nom_description = :nom_description 
                  AND valeur = :valeur
              )";
      
      $stmt = $conn->prepare($sql);
      
      $stmt->bindParam(':nom_description', $nom_description);
      $stmt->bindParam(':valeur', $valeur);
      if ($stmt->execute()) {
          if ($stmt->rowCount() > 0) {
          } else {
              echo "<script>alert('La valeur existe déjà dans le tableau.');</script>";
          }
      }
      
      $stmt->closeCursor();}
      
  }
require_once('config.php');
$querry1 = "select * from description";
$result1 = mysqli_query($con,$querry1);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="superadmin.css">
    <title>Modifier Descrption_societe</title>

     <style>
        body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
}

header {
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

h1 {
    font-size: 2em;
    color: #333;
    margin-right: 60px;
}

.container {
    max-width: 600px;
    margin: 0 auto;
}


        .form-element {
            margin-bottom: 20px;
        }

input[type="text"] {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

input[type="submit"] {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #0075c0;
    color: #fff;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    background: #0075c0;
}

.image-preview {
    width: 200px;
    height: 200px;
    border: 1px solid #ccc;
    margin-top: 10px;
    background-size: cover;
    background-position: center;
}
input[type="file"]::-webkit-file-upload-button {
    visibility: hidden;
}
input[type="file"]::before {
    content: 'Choisissez votre nouveau logo';
    display: inline-block;
    background: #007bff;
    color: #fff;
    padding: 6px 12px;
    cursor: pointer;
}

     </style>



</head>
<body>
    <div class="container ">
    <header class="d-flex ">

    
            <h1>Modifier La description</h1>
            <a href="#" onclick="togglePopup()"class="btn btn-primary">Ajouter une discription</a>

            
        </header>
        <!-- form-add description-->
         <div class="popup1" id="popup1">
                    <div class="close-btn">&times;</div>
                    <div class="form"><form action="edit_d.php" method="POST" id="popup1">
                        <label for="nom">Nom_Description:</label>
                        <input type="text" id="nom" name="nom"  value="" placeholder="Description...."><br><br>
                        <label for="valeur">Valeur_Description:</label>
                        <input type="text" id="valeur" name="valeur"  value="" placeholder="Valeur....."><br><br><br>
                        <div class="envoi"><input type="submit" value="Ajouter" name="ajouter" style="width:100%;height:40px;border:none;font-size:16px;background:#0C5DF4;color:#f5f5f5;border-radius:10px;cursor:pointer;"></div>
                    </form></div>
                    </div>
           <form action="edit_d.php" method="POST">
           <input  type="file" id="fileInput" accept="image/*" title="Choisir une photo">
            
            <div class="image-preview"></div>
           
        
            <?php while($row1 = mysqli_fetch_assoc($result1)) { ?>
        <div class="form-element my-4">
            <label for="valeur"><?php echo $row1['nom_description']; ?>:</label>
            <input type="text" class="form-control" name="valeur" value="<?php echo $row1['valeur']; ?>">
            <input type="hidden" name="id" value="<?php echo $row1['id']; ?>">
            
        </div>
    <?php } ?>
    
    <div class="form-element">
        <input type="submit" name="edit" value="Modifier" class="btn">
        <a href="admin_sup.php" class="btn btn-primary">Retour</a>
    </div>
       </form> 


          
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        function togglePopup() {
        document.getElementById("popup1").classList.toggle("active");
        document.querySelector(" .close-btn").addEventListener("click", function() {
        document.querySelector("#popup1").classList.remove("active");
         });
        }
</script>
    <script>
        const fileInput = document.getElementById('fileInput');
const imagePreview = document.querySelector('.image-preview');

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.style.backgroundImage = `url(${e.target.result})`;
        }
        reader.readAsDataURL(file);
    }
});
function out(){
document.querySelector(" .btn").addEventListener("click",(function() {
    window.location.href = "admin_sup.php";
}));}
    </script><?php if(isset($_POST['edit'])) {
    $id = $_POST['id'];
    $valeur = $_POST['valeur'];
     
    
        $sql = "UPDATE description SET valeur = :valeur WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':valeur', $valeur);
        $stmt->bindParam(':id', $id);
}?>
</body>
</html>