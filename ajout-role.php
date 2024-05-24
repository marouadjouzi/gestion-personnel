<?php
 session_start();
 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "baosem";
 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
 if(isset($_POST['Ajouter'])){
  $id = $_POST['id'];
  $role = $_POST['role'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm-password'];
  if ($password != $confirm_password) {
    die('Passwords do not match.');  }
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  if ($role == 'user') {
    $stmt = $conn->prepare("SELECT * FROM responsabl WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $rowp = $stmt->fetch(); // Fetch the row from the query result
        $approbation = $rowp['approbation'];
    } else {
        $approbation = "0";
    }
} else {
    $approbation = "0";
}

$sql = "INSERT INTO role (id, username, password, role, approbation) VALUES (:id, :username, :hashed_password, :role, :approbation)";
$st = $conn->prepare($sql);
$st->bindParam(':id', $id);
$st->bindParam(':username', $username);
$st->bindParam(':hashed_password', $hashed_password);
$st->bindParam(':role', $role);
$st->bindParam(':approbation', $approbation);
$st->execute();

header('Location: role.php');
exit;
 }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ===== CSS ===== -->
      
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>superadmin menu</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css /">
    </head>
    <body> <div class="formbold-main-wrapper">
      <!-- Author: FormBold Team -->
      <!-- Learn More: https://formbold.com -->
      <div class="formbold-form-wrapper">
        
        <img src="baosem_logo.png" style=" margin-bottom: 60px;
    width: 51%;
    margin-left: 108px;">
   <?php if (isset($_POST['ajouter'])){
  $id = $_POST['id'];
  $nom = $_POST['nom'];
  $prenom =$_POST['prenom'];
  ?>
 
        <form action="ajout-role.php" method="POST">
          <div class="formbold-form-title">
            <h2 class="">Définir son Role</h2>
            <p>
              Ce formulaire vous permet de donner un role a cet element
            </p>
          </div>
          <div>
              <label for="id" class="formbold-form-label">
                ID:
              </label>
              <input
                type="text"
                name="id"
                id="id"
                value=' <?php echo $id; ?>'
                class="formbold-form-input" 
                />
            </div>
            <div class="formbold-input-flex">
            <div>
              <label for="prenom" class="formbold-form-label">
                Prenom :
              </label>
              <input
                type="text"
                name="prenom"
                id="prenom"
                value=' <?php echo $prenom; ?>'
                class="formbold-form-input" 
              />
            </div>
            <div>
              <label for="nom" class="formbold-form-label"> Nom : </label>
              <input
                type="text"
                name="nom"
                id="nom"
                value=' <?php echo $nom; ?>'
                class="formbold-form-input" 
              />
            </div> <?php }?>
          </div>
          <label for="super"class="formbold-form-label">Role :</label>
          <select name="role" id="role"  required>
                    <option value="">Veuillez choisir :</option>
                    <option value="superadmin">Administrateur principal</option>
                    <option value="admin">Administrateur</option>
                    <option value="user">Utilisateur</option>
                </select>
              <br><br>
          
          <style>
            input[type=radio] {
  --s: 1em;     /* control the size */
  --c: #009688; /* the active color */
  
  height: var(--s);
  aspect-ratio: 1;
  border: calc(var(--s)/8) solid #939393;
  padding: calc(var(--s)/8);
  background: 
     radial-gradient(farthest-side,var(--c) 94%,#0000) 
     50%/0 0 no-repeat content-box;
  border-radius: 50%;
  outline-offset: calc(var(--s)/10);
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  cursor: pointer;
  font-size: inherit;
  transition: .3s;
}
input[type=radio]:checked {
  border-color: var(--c);
  background-size: 100% 100%;
}

input[type=radio]:disabled {
  background: 
     linear-gradient(#939393 0 0) 
     50%/100% 20% no-repeat content-box;
  opacity: .5;
  cursor: not-allowed;
}

@media print {
  input[type=radio] {
    -webkit-appearance: auto;
    -moz-appearance: auto;
    appearance: auto;
    background: none;
  }
}         
          </style>
          
       <div>
          <?php if (isset($_POST['ajouter'])){
              $nom = $_POST['nom'];
              $prenom =$_POST['prenom'];
  ?>
              <label for="nom" class="formbold-form-label">
                Nom du compte:
              
              </label>
              <input 
                type="text"
                name="username"
                id="nom"
                value = "<?php echo $nom.".".$prenom; }?>"
                class="formbold-form-input" required
              />
            </div>
            <div class="formbold-input-flex">
            <div>
              <label for="password" class="formbold-form-label">
                Mot de passe:
              </label>
              <input
                type="password"
                name="password"
                id="password"
                class="formbold-form-input" required
              />
            </div>
            <div>
              <label for="confirm-password" class="formbold-form-label"> Confirmer votre mot de passe: </label>
              <input
                type="password"
                name="confirm-password"
                id="confirm-password"
                class="formbold-form-input" required
              />
            </div>
            </div>
            
          <button class="formbold-btn" name ="Ajouter">Enregistrer</button>
          <a href="role.php" class="formbold-btn" style="text-decoration:none;">Retour</a>
          </div>
         
          
        </form>
      </div>
    </div>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      body {
        font-family: 'Inter', sans-serif;
        background-color: aliceblue;
      }
      .formbold-mb-3 {
        margin-bottom: 15px;
      }
      .formbold-relative {
        position: relative;
      }
      .formbold-opacity-0 {
        opacity: 0;
      }
      .formbold-stroke-current {
        stroke: currentColor;
      }
      #supportCheckbox:checked ~ div span {
        opacity: 1;
      }
    
      .formbold-main-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
      }
    
      .formbold-form-wrapper {
        margin: 0 auto;
        max-width: 570px;
        width: 100%;
        background: white;
        padding: 40px;
      }
    
      
    
      .formbold-form-title {
        margin-bottom: 30px;
      }
      .formbold-form-title h2 {
        font-weight: 600;
        font-size: 28px;
        line-height: 34px;
        color: #07074d;
        margin-left: 43px;
      }
      .formbold-form-title p {
        font-size: 16px;
        line-height: 24px;
        color: #536387;
        margin-top: 12px;
      }
    
      .formbold-input-flex {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
      }
      .formbold-input-flex > div {
        width: 50%;
      }
      .formbold-form-input {
        text-align: center;
        width: 100%;
        padding: 13px 22px;
        border-radius: 5px;
        border: 1px solid #dde3ec;
        background: #ffffff;
        font-weight: 500;
        font-size: 16px;
        color: #536387;
        outline: none;
        resize: none;
      }
      .formbold-form-input:focus {
        border-color: #6a64f1;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
      }
      .formbold-form-label {
        color: #536387;
        font-size: 14px;
        line-height: 24px;
        display: block;
        margin-bottom: 10px;
      }
    
      .formbold-checkbox-label {
        display: flex;
        cursor: pointer;
        user-select: none;
        font-size: 16px;
        line-height: 24px;
        color: #536387;
      }
      .formbold-checkbox-label a {
        margin-left: 5px;
        color: #6a64f1;
      }
      .formbold-input-checkbox {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
      }
      .formbold-checkbox-inner {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        margin-right: 16px;
        margin-top: 2px;
        border: 0.7px solid #dde3ec;
        border-radius: 3px;
      }
      select {
  /* Reset */
  appearance: none;
  border: 0;
  outline: 0;
  font: inherit;
  /* Personalize */
  width: 20rem;
  padding: 1rem 4rem 1rem 1rem;
  background: var(--arrow-icon) no-repeat right 0.8em center / 1.4em,
    linear-gradient(to left, var(--arrow-bg) 3em, var(--select-bg) 3em);
  color:#536387 ;
  border-radius: 0.25em;
  box-shadow: 0 0 1em 0 rgba(0, 0, 0, 0.2);
  cursor: pointer;
  /* Remove IE arrow */
  &::-ms-expand {
    
  }
  /* Remove focus outline */
  &:focus {
    outline: none;
  }
  /* <option> colors */
  option {
    color: inherit;
    background-color: var(--option-bg);
  }
}

      .formbold-btn {
        font-size: 16px;
        border-radius: 5px;
        padding: 14px 25px;
        border: none;
        font-weight: 500;
        background-color: #6a64f1;
        color: white;
        cursor: pointer;
        margin-top: 25px;
      }
      .formbold-btn:hover {
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
      }
    </style>
    

        </body>
</html>