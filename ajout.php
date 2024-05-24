 <?php
 session_start();
 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "baosem";
 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,$password);
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $stmt = $conn->query("SELECT * FROM direction");
 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
 $stmte = $conn->query("SELECT * FROM departement");
 $resulte = $stmte->fetchAll(PDO::FETCH_ASSOC); 


$stmtp = $conn->query("SELECT * FROM employees Where id_employees NOT IN (SELECT id FROM responsabl) ");
$resultp = $stmtp->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['dg'])){
  $id = $_POST['dg'];

$sql_check = "SELECT id_employees FROM employees WHERE id_employees = :id";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':id', $id);
$stmt_check->execute();
$employee = $stmt_check->fetch(PDO::FETCH_ASSOC);

if ($employee) {
    // L'employé existe, continuez avec la mise à jour de la table "responsabl"
    $sql_dg = "UPDATE responsabl SET id = :id WHERE approbation = 'dg'";
    $stmt_dg = $conn->prepare($sql_dg);
    $stmt_dg->bindParam(':id', $id);
    try {
        $stmt_dg->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // L'employé n'existe pas, affichez un message d'erreur
    echo "Error: L'ID de l'employé n'existe pas dans la table 'employees'.";
}
}
 

 if(isset($_POST['Ajouter'])){
  $type = $_POST['type'];
  if ($type == 'direction'){
    $nom = $_POST['nom'];
    $d_dg = '1';
    $id_rep = $_POST['responsable'];
    
// Find the employee with the specified id_rep
$respon = "SELECT * FROM employees WHERE id_employees = :id_rep";
$stmtrespon = $conn->prepare($respon);
$stmtrespon->bindParam(':id_rep', $id_rep);
$stmtrespon->execute();
$employee = $stmtrespon->fetch(PDO::FETCH_ASSOC); // Use $stmtrespon instead of $stmt

// Check if the employee exists
if ($employee) {
    $responsable_direction = $employee['nom'] . ' ' . $employee['prenom'];

    // Insert the new direction and responsable into the direction and responsabl tables
    $sql = "INSERT INTO direction (nom_direction, responsable, d_dg) 
            SELECT :nom, :responsable_direction, :d_dg 
            FROM dual 
            WHERE NOT EXISTS (
                SELECT 1 
                FROM direction 
                WHERE nom_direction = :nom 
                AND responsable = :responsable_direction
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':responsable_direction', $responsable_direction); // Use :responsable_direction
    $stmt->bindParam(':d_dg', $d_dg);

    


    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) { 
            $id = $conn->lastInsertId();

            $sql_0 = "INSERT INTO responsabl (id, approbation, d_app) VALUES (:id_rep, :nom, :id)";
            $stmt_0 = $conn->prepare($sql_0);
            $stmt_0->bindParam(':id_rep', $id_rep);
            $stmt_0->bindParam(':nom', $nom);
            $stmt_0->bindParam(':id', $id);
            $stmt_0->execute();
        }
    }
  }
}
if ($type == 'departement'){
  $nom = $_POST['nom'];
$responsable = $_POST['responsable'];
$d_direction = $_POST['super'];

$respon = "SELECT * FROM employees WHERE id_employees = :responsable";
$stmtrespon = $conn->prepare($respon);
$stmtrespon->bindParam(':responsable', $responsable);
$stmtrespon->execute();
$employee = $stmtrespon->fetch(PDO::FETCH_ASSOC);

if ($employee) {
    $responsable_departement = $employee['nom'] . ' ' . $employee['prenom'];

    if ($nom != NULL && $responsable_departement != NULL) {
        $sql = "INSERT INTO departement (nom_departement, responsable, d_direction) 
                SELECT :nom, :responsable_departement, :d_direction 
                FROM dual 
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM departement 
                    WHERE nom_departement = :nom 
                    AND responsable = :responsable_departement
                )";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':responsable_departement', $responsable_departement);
        $stmt->bindParam(':d_direction', $d_direction);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $id = $conn->lastInsertId();

            $sql_00 = "INSERT INTO responsabl (id, approbation, d_app) VALUES (?, ?, ?)";
            $stmt_00 = $conn->prepare($sql_00);
            $stmt_00->execute([$responsable, $nom, $id]);

            $d_departement = intval($id);

            $sql3 = "UPDATE employees SET d_departement = ? WHERE id_employees = ?";
            $stmt3 = $conn->prepare($sql3);
            if (!$stmt3) {
                // Prepare failed
                echo "Error: " . $conn->errorInfo();
                exit;
            }
            $stmt3->execute([$d_departement, $responsable]);
        } else {
            echo "<script>alert('La valeur existe déjà dans le tableau.');</script>";
        }
    }
}

}
if ($type == 'employee') {
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $date_n = $_POST['date_n'];
  $lieu_n = $_POST['lieu_n'];
  $phone = $_POST['phone'];
  $adresse = $_POST['addresse'];
  $email = $_POST['email'];
  $nss = $_POST['nss'];
  $d_departement = $_POST['super'];
  $fonction = $_POST['fonction'];
  $affectation = $_POST['affectation'];

  // Assurez-vous d'avoir une connexion à votre base de données $conn
  if ($nom != NULL && $prenom != NULL && $phone != NULL && $adresse != NULL && $email != NULL && $nss != NULL) {
      $sql = "INSERT INTO employees (nom, prenom, date_n, lieu_n, phone, email, adresse, nss, fonction, affectation, d_departement) 
              SELECT :nom, :prenom, :date_n, :lieu_n, :phone, :email, :adresse, :nss, :fonction, :affectation, :d_departement 
              FROM dual 
              WHERE NOT EXISTS (
                  SELECT 1 
                  FROM employees 
                  WHERE nom = :nom 
                  AND prenom = :prenom 
                  AND date_n = :date_n 
                  AND lieu_n = :lieu_n 
                  AND phone = :phone 
                  AND email = :email 
                  AND adresse = :adresse
                  AND nss = :nss
                  AND fonction = :fonction 
                  AND affectation = :affectation
              )";

      $stmt = $conn->prepare($sql);

      $stmt->bindParam(':nom', $nom);
      $stmt->bindParam(':prenom', $prenom);
      $stmt->bindParam(':date_n', $date_n);
      $stmt->bindParam(':lieu_n', $lieu_n);
      $stmt->bindParam(':phone', $phone);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':adresse', $adresse);
      $stmt->bindParam(':nss', $nss);
      $stmt->bindParam(':fonction', $fonction);
      $stmt->bindParam(':affectation', $affectation);
      $stmt->bindParam(':d_departement', $d_departement);

      if ($stmt->execute()) {
          if ($stmt->rowCount() > 0) {
              // L'employé a été ajouté avec succès
          } else {
              $error[] = 'La valeur existe déjà dans le tableau.';
          }
      } else {
          $error[] = 'Une erreur s\'est produite lors de l\'ajout de l\'employé.';
      }
  } else {
      $error[] = 'Veuillez renseigner tous les champs obligatoires.';
  }

  if (!empty($error)) {
      // Afficher les messages d'erreur
      foreach ($error as $message) {
          echo "<script>alert('$message');</script>";
      }
  }

} 
header('Location: ' . $_SERVER['PHP_SELF']);
exit;
}
 
if(isset($_POST['responsable_direction'])){
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $date_n = $_POST['date_n'];
  $lieu_n = $_POST['lieu_n'];
  $phone = $_POST['phone'];
  $adresse = $_POST['addresse'];
  $email = $_POST['email'];
  $nss = $_POST['nss'];
  $d_departement = '30';
  $fonction = $_POST['fonction'];
  $affectation = $_POST['affectation'];
 
  // Assurez-vous d'avoir une connexion à votre base de données $conn
  if($nom != NULL && $prenom != NULL &&  $phone != NULL && $adresse != NULL && $email != NULL &&  $nss != NULL ){
  $sql = "INSERT INTO employees (nom, prenom, date_n, lieu_n, phone, email, adresse, nss, fonction, affectation,  d_departement) 
          SELECT :nom, :prenom, :date_n, :lieu_n, :phone, :email, :adresse, :nss,  :fonction, :affectation,  :d_departement 
          FROM dual 
          WHERE NOT EXISTS (
              SELECT 1 
              FROM employees 
              WHERE nom = :nom 
              AND prenom = :prenom 
              AND date_n = :date_n 
              AND lieu_n = :lieu_n 
              AND phone = :phone 
              AND email = :email 
              AND adresse = :adresse 
              AND nss = :nss
              AND fonction = :fonction 
              AND affectation = :affectation 
          )";
  
  $stmt = $conn->prepare($sql);
  
  $stmt->bindParam(':nom', $nom);
  $stmt->bindParam(':prenom', $prenom);
  $stmt->bindParam(':date_n', $date_n);
  $stmt->bindParam(':lieu_n', $lieu_n);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':adresse', $adresse);
  $stmt->bindParam(':nss', $nss);
  $stmt->bindParam(':fonction', $fonction);
  $stmt->bindParam(':affectation', $affectation);
  $stmt->bindParam(':d_departement', $d_departement);
  
  if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        $id_rep = $conn->lastInsertId();
        $responsable = $nom . ' ' . $prenom;
        $nom = $_POST['nom_direct'];
        $d_dg = '1';
        $id_rep = $id_rep;
        if ($nom != NULL && $responsable != NULL ){
        // Assurez-vous d'avoir une connexion à votre base de données $conn
        
        $sql = "INSERT INTO direction (nom_direction, responsable, d_dg) 
                SELECT :nom, :responsable, :d_dg 
                FROM dual 
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM direction 
                    WHERE nom_direction = :nom 
                    AND responsable = :responsable
                )";
        
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam('responsable', $responsable);
        $stmt->bindParam(':d_dg', $d_dg);
        
        
        $sql_0 = "INSERT INTO responsabl (id, approbation) VALUES (:id_rep, :nom)";
    $stmt_0 = $conn->prepare($sql_0);
    $stmt_0->bindParam(':id_rep', $id_rep);
    $stmt_0->bindParam(':nom', $nom);
    $stmt_0->execute();
    
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
            } else {
                echo "<script>alert('La valeur existe déjà dans le tableau.');</script>";
            }
        }
        
        $stmt->closeCursor();}else{
          $error[] = 'Veillez renseigner tous les champs!';
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
      }
      }
}
}
if(isset($_POST['responsable_departement'])){
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $date_n = $_POST['date_n'];
  $lieu_n = $_POST['lieu_n'];
  $phone = $_POST['phone'];
  $adresse = $_POST['addresse'];
  $email = $_POST['email'];
  $nss = $_POST['nss'];
  $d_departement = '30';
  $fonction = $_POST['fonction'];
  $affectation = $_POST['affectation'];
 
   // Assurez-vous d'avoir une connexion à votre base de données $conn
   if($nom != NULL && $prenom != NULL &&  $phone != NULL && $adresse != NULL && $email != NULL &&  $nss != NULL ){
    $sql_dep = "INSERT INTO employees (nom, prenom, date_n, lieu_n, phone, email, adresse, nss, fonction, affectation,  d_departement) 
            SELECT :nom, :prenom, :date_n, :lieu_n, :phone, :email, :adresse, :nss,  :fonction, :affectation,  :d_departement 
            FROM dual 
            WHERE NOT EXISTS (
                SELECT 1 
                FROM employees 
                WHERE nom = :nom 
                AND prenom = :prenom 
                AND date_n = :date_n 
                AND lieu_n = :lieu_n 
                AND phone = :phone 
                AND email = :email 
                AND adresse = :adresse 
                AND nss = :nss
                AND fonction = :fonction 
                AND affectation = :affectation 
            )";
  $stmt = $conn->prepare($sql_dep);
  
  $stmt->bindParam(':nom', $nom);
  $stmt->bindParam(':prenom', $prenom);
  $stmt->bindParam(':date_n', $date_n);
  $stmt->bindParam(':lieu_n', $lieu_n);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':adresse', $adresse);
  $stmt->bindParam(':nss', $nss);
  $stmt->bindParam(':fonction', $fonction);
  $stmt->bindParam(':affectation', $affectation);
  $stmt->bindParam(':d_departement', $d_departement);
  
  if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        $id_rep = $conn->lastInsertId();
        $responsable = $nom . ' ' . $prenom;
        $nom = $_POST['nom_depart'];
        $d_direction = $_POST['super'];

  // Assurez-vous d'avoir une connexion à votre base de données $conn
 if($nom != NULL && $responsable != NULL){  
  $sql = "INSERT INTO departement (nom_departement, responsable, d_direction) 
  SELECT :nom, :responsable, :d_direction 
  FROM dual 
  WHERE NOT EXISTS (
      SELECT 1 
      FROM departement 
      WHERE nom_departement = :nom 
      AND  responsable = :responsable
  )";
  
  $stmt = $conn->prepare($sql);
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':responsable', $responsable);
$stmt->bindParam(':d_direction', $d_direction);

if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        // The values were successfully inserted
    } else {
        echo "<script>alert('La valeur existe déjà dans le tableau.');</script>";
    }
}

        
        $sql_0 = "INSERT INTO responsabl (id, approbation) VALUES (:id_rep, :nom)";
    $stmt_0 = $conn->prepare($sql_0);
    $stmt_0->bindParam(':id_rep', $id_rep);
    $stmt_0->bindParam(':nom', $nom);
    $stmt_0->execute();
    
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
            } else {
                echo "<script>alert('La valeur existe déjà dans le tableau.');</script>";
            }
        }
        
        $stmt->closeCursor();}else{
          $error[] = 'Veillez renseigner tous les champs!';
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
      }
      }
}
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
    <body> 
   <!-- <button style='margin-left:22%;' onclick="openDG()" class='formbold-btn'>definir le directeur general</button> -->
    <div class="formbold-main-wrapper">
      <!-- Author: FormBold Team -->
      <!-- Learn More: https://formbold.com -->
      <div class="formbold-form-wrapper">
        
        <img src="baosem_logo.png" style=" margin-bottom: 60px;
    width: 51%;
    margin-left: 108px;">
    
        <form action="ajout.php" method="POST">
          <div class="formbold-form-title">
            <h2 class="">Paramétrer votre structure</h2>
            <p>
              Ce formulaire vous permet d'ajouter un nouvel element a votre structure
            </p>
          </div>
          <label for="type"class="formbold-form-label"><b>Type element:</b></label><br>
          
            
          <div class="flex" style="display: flex;"><div class="choix" style="display: flex">
          <label for="direction" class="formbold-form-label" style="">Direction</label><br>
          <input class="demo5" type="radio" name="type" value="direction" id="direction" onchange="showFields()" style="margin-left: 22px;
          margin-top: 6px;
          margin-right: 57px;" required></div>
          <div class="choix" style="display: flex">
          <label for="departement" class="formbold-form-label">Département</label><br>
          <input class="demo5" type="radio" name="type" value="departement" id="departement" onchange="showFields()" style="margin-left: 20px;
          margin-top: 6px;
          margin-right: 52px;" ></div>
          <div class="choix" style="display: flex">
          <label for="employee" class="formbold-form-label">Employé</label><br>
          <input class="demo5" type="radio" name="type" value="employee" id="employee" onchange="showFields()" style="margin-left: 27px;
          margin-top: 6px;"></div></div>
          
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
          <div id="dynamicFields"></div>
          <?php
            if(isset($error)){
              foreach($error as $error){
                  echo '<span style="color:red;">'.$error.'</span><br>';
              };
            };
            ?>
          <button class="formbold-btn" name ="Ajouter">Enregistrer</button>
          <a href="structure.php" class="formbold-btn" style="text-decoration:none;">Retour</a>
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
      select, #searchResults {
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
       .plus{
          font-size: x-large;
          width: 10%;
          background-color: #6a64f1;
          border: none;
          color: #fff;
        }
        .plus:hover{
          opacity: 0.5;
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
    <script>   
      function showFields() {
      var type = document.querySelector('input[name="type"]:checked').value;
      var dynamicFields = document.getElementById("dynamicFields");
  
      dynamicFields.innerHTML = ""; // Clear previous fields
  
      if (type === "direction") {
          dynamicFields.innerHTML += `
          <div>
              <label for="nom" class="formbold-form-label">
                NOM-Direction:
              </label>
              <input 
                type="text"
                name="nom"
                id="nom"
                class="formbold-form-input" required
              />
            </div>
            <br>
            <label for="responsable" class="formbold-form-label">Responsable :</label>
            <input style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput" oninput="searchSelect(event)" placeholder="Rechercher son nom..">
            <div id="searchResults" style="display:none;"></div>
            <div style="display:flex;">
            <input type="text" name="responsable" id="responsable" class="formbold-form-input" readonly required  style="width:100%;">
           <button class="plus" id="toggle" onclick="openPopupConfirmation()"><ion-icon name="add-outline"></ion-icon ></button>
           </div>
            <select  id="responsable" name="responsable" style="display:none;">
              <?php foreach($resultp as $rowp) { ?>
                <option value="<?php echo $rowp['id_employees']; ?>"><?php echo $rowp['nom']." ".$rowp['prenom']; ?></option>
             <?php } ?>
              
            </select>
            
            

            
             
            
            </div>
          </div>
          
            
          </div>`;
      } else if (type === "departement") {
          dynamicFields.innerHTML += `
          <div>
          <div>
              <label for="nom" class="formbold-form-label">
                NOM-Departement:
              </label>
              <input 
                type="text"
                name="nom"
                id="nom_departement"
                class="formbold-form-input" required
              />
            </div>
            
            <div class="formbold-input-flex">
            <div>
            
            <label for="super"class="formbold-form-label">Direction :</label>
            <select name="super" id="super">
              <?php foreach($result as $row){ ?><option value="<?php echo $row['id_direction']?>"><?php echo $row['nom_direction']?></option>
              <?php }?> 
            </select><br><br>  
            <label for="responsable" class="formbold-form-label">Responsable :</label>
            <input style="width: 450px; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput" oninput="searchSelect(event)" placeholder="Rechercher son nom..">
            <div id="searchResults" style="display:none;"></div>
            <div style="display:flex;width:815px;">
            <input type="text" style="width: 367px;" name="responsable" id="responsable" class="formbold-form-input" readonly required style=''>
            <button class="plus" id="toggle" onclick="openPopupConfirmation_b()"><ion-icon name="add-outline"></ion-icon ></button>
           </div>
            <select  id="responsable" name="responsable" style="display:none;">
              <?php foreach($resultp as $rowp) { ?>
                <option value="<?php echo $rowp['id_employees']; ?>"><?php echo $rowp['nom']." ".$rowp['prenom']; ?></option>
              <?php } ?>
            </select>
          
              `;
      }else if (type === "employee") {
          dynamicFields.innerHTML += `
            <div class="formbold-input-flex">
            <div>
              <label for="prenom" class="formbold-form-label">
                Prenom
              </label>
              <input
                type="text"
                name="prenom"
                id="prenom"
                class="formbold-form-input" required
              />
            </div>
            <div>
              <label for="nom" class="formbold-form-label"> Nom </label>
              <input
                type="text"
                name="nom"
                id="nom"
                class="formbold-form-input" required
              />
            </div>
          </div>
          <div class="formbold-input-flex">
            <div>
              <label for="affectation" class="formbold-form-label">
                Affectaion:
              </label>
              <input
                type="text"
                name="affectation"
                id="affectation"
                class="formbold-form-input" required
              />
            </div>
            <div>
              <label for="fonction" class="formbold-form-label"> Fonction: </label>
              <input
                type="text"
                name="fonction"
                id="fonction"
                class="formbold-form-input" required
              />
            </div>
          </div>
          
          <div class="formbold-input-flex">
            <div>
              <label for="date_n" class="formbold-form-label"> Date de Naissance: </label>
              <input
                type="date"
                name="date_n"
                id="date_n"
                class="formbold-form-input" 
                lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa" required
              />
            </div>
            <div>
              <label for="lieu_n" class="formbold-form-label"> Lieu de Naissance: </label>
              <input
                type="text"
                name="lieu_n"
                id="lieu_n"
                class="formbold-form-input" required
              />
            </div>
            
          </div>

          <div class="formbold-input-flex">
            <div>
              <label for="email" class="formbold-form-label"> Email </label>
              <input
                type="email"
                name="email"
                id="email"
                class="formbold-form-input" required
              />
            </div>
            <div>
              <label for="phone" class="formbold-form-label"> Num de Telephone </label>
              <input
                type="text"
                name="phone"
                id="phone"
                class="formbold-form-input" required
              />
            </div>
            
          </div>
    
          <div class="formbold-mb-3">
            <label for="addresse" class="formbold-form-label">
              Address
            </label>
            <input
              type="text"
              name="addresse"
              id="addresse"
              class="formbold-form-input" required
            />
          </div>
    
            <div>
              <label for="nss" class="formbold-form-label"> Numéro de sécurité sociale: </label>
              <input
                type="text"
                name="nss"
                id="post"
                class="formbold-form-input" required
              />
            </div>
           
          <label for="super" class="formbold-form-label">Département :</label>
            <select name="super" id="super">
            <?php foreach($resulte as $rowe){ ?><option value="<?php echo $rowe['id_departement']?>"><?php echo $rowe['nom_departement']?></option>
            <?php }?> 
           </select>
          
    
              
              `;
              
      }
  } 
  </script>
  <style>
    .popup_a, .popup_b, .popup_c  {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 9999;
}

.popup_a p, .popup_b p, .popup_c p{
    margin-bottom: 10px;
}

.popup_a button, .popup_b button, .popup_c button {
    font-size: medium;
    background: #673fd7;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 10px;
}



</style>

  
  <div id="confirmation" class="popup_a">
  <form action="ajout.php" method="POST">
    <br><br><br>
    <div class="formbold-form-title">
            <h2 class="">Ajouter le responsable de cette direction</h2>
            
          </div>
          <br><br>
          <div>
              <label for="popup-nom" class="formbold-form-label">
                NOM-Direction:
              </label>
              <input 
                type="text"
                name="nom_direct"
                id="popup-nom"
                class="formbold-form-input" required
              />
            </div>
            <div>
              
    <div class="formbold-input-flex">
      <div>
        <label for="prenom" class="formbold-form-label">
          Prenom
        </label>
        <input
          type="text"
          name="prenom"
          id="prenom"
          class="formbold-form-input" required
        />
      </div>
      <div>
        <label for="nom" class="formbold-form-label"> Nom </label>
        <input
          type="text"
          name="nom"
          id="nom"
          class="formbold-form-input" required
        />
      </div>
    </div>
    <div class="formbold-input-flex">
            <div>
              <label for="affectation" class="formbold-form-label">
                Affectaion:
              </label>
              <input
                type="text"
                name="affectation"
                id="affectation"
                class="formbold-form-input" required
              />
            </div>
            <div>
              <label for="fonction" class="formbold-form-label"> Fonction: </label>
              <input
                type="text"
                name="fonction"
                id="fonction"
                class="formbold-form-input" required
              />
            </div>
          </div>
    <div class="formbold-input-flex">
      <div>
        <label for="date_n" class="formbold-form-label"> Date de Naissance: </label>
        <input
          type="date"
          name="date_n"
          id="date_n"
          class="formbold-form-input"
          lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa" required
        />
      </div>
      <div>
        <label for="lieu_n" class="formbold-form-label"> Lieu de Naissance: </label>
        <input
          type="text"
          name="lieu_n"
          id="lieu_n"
          class="formbold-form-input" required
        />
      </div>
    </div>

    <div class="formbold-input-flex">
      <div>
        <label for="email" class="formbold-form-label"> Email </label>
        <input
          type="email"
          name="email"
          id="email"
          class="formbold-form-input" required
        />
      </div>
      <div>
        <label for="phone" class="formbold-form-label"> Num de Telephone </label>
        <input
          type="text"
          name="phone"
          id="phone"
          class="formbold-form-input" required
        />
      </div>
    </div>

    <div class="formbold-mb-3">
      <label for="addresse" class="formbold-form-label">
        Address
      </label>
      <input
        type="text"
        name="addresse"
        id="addresse"
        class="formbold-form-input" required
      />
    </div>

    <div>
      <label for="nss" class="formbold-form-label"> Numéro de sécurité sociale: </label>
      <input
        type="text"
        name="nss"
        id="nss"
        class="formbold-form-input" required
      />
    </div>
    <br><br>
    <button type="submit" name="responsable_direction">Ajouter</button>
    <button type="button" onclick="closePopup()">Annuler</button>
  </form>
</div>
</div>


<div id="confirmation_b" class="popup_b">
  <form id="form" action="ajout.php" method="POST">
    <br><br><br>
    <div class="formbold-form-title">
            <h2 class="">Ajouter le responsable de ce departement</h2>
            
          </div>
          <br><br>
          <div>
              <label for="popup-nom-b" class="formbold-form-label">
                NOM-Departement:
              </label>
              <input 
                type="text"
                name="nom_depart"
                id="popup-nom-b"
                class="formbold-form-input" required
              />
            </div>
            <label for="popup-nom" class="formbold-form-label">
                Direction:
              </label>
              <input 
                type="text"
                name="super"
                id="popup-super"
                class="formbold-form-input" required
              />
          
    <div class="formbold-input-flex">
      <div>
        <label for="prenom" class="formbold-form-label">
          Prenom
        </label>
        <input
          type="text"
          name="prenom"
          id="prenom"
          class="formbold-form-input" required
        />
      </div>
      <div>
        <label for="nom" class="formbold-form-label"> Nom </label>
        <input
          type="text"
          name="nom"
          id="nom"
          class="formbold-form-input" required
        />
      </div>
    </div>
    <div class="formbold-input-flex">
            <div>
              <label for="affectation" class="formbold-form-label">
                Affectaion:
              </label>
              <input
                type="text"
                name="affectation"
                id="affectation"
                class="formbold-form-input" required
              />
            </div>
            <div>
              <label for="fonction" class="formbold-form-label"> Fonction: </label>
              <input
                type="text"
                name="fonction"
                id="fonction"
                class="formbold-form-input" required
              />
            </div>
          </div>
    <div class="formbold-input-flex">
      <div>
        <label for="date_n" class="formbold-form-label"> Date de Naissance: </label>
        <input
          type="date"
          name="date_n"
          id="date_n"
          class="formbold-form-input"
          lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa" required
        />
      </div>
      <div>
        <label for="lieu_n" class="formbold-form-label"> Lieu de Naissance: </label>
        <input
          type="text"
          name="lieu_n"
          id="lieu_n"
          class="formbold-form-input" required
        />
      </div>
    </div>

    <div class="formbold-input-flex">
      <div>
        <label for="email" class="formbold-form-label"> Email </label>
        <input
          type="email"
          name="email"
          id="email"
          class="formbold-form-input" required
        />
      </div>
      <div>
        <label for="phone" class="formbold-form-label"> Num de Telephone </label>
        <input
          type="text"
          name="phone"
          id="phone"
          class="formbold-form-input" required
        />
      </div>
    </div>

    <div class="formbold-mb-3">
      <label for="addresse" class="formbold-form-label">
        Address
      </label>
      <input
        type="text"
        name="addresse"
        id="addresse"
        class="formbold-form-input" required
      />
    </div>

    <div>
      <label for="nss" class="formbold-form-label"> Numéro de sécurité sociale: </label>
      <input
        type="text"
        name="nss"
        id="nss"
        class="formbold-form-input" required
      />
    </div>
    <br><br>
    <!--<div>
      <label for="date_e" class="formbold-form-label"> Date d'entree: </label>
      <input
        type="date"
        name="date_e"
        id="date_e"
        class="formbold-form-input" required lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa"
      />
    </div>-->
    <br><br>
    <button type="submit" name="responsable_departement">Ajouter</button>
    <button type="button" onclick="closePopup()">Annuler</button>
  </form>

</div>

<div id='dg' class="popup_c">
    <form id="form" action="ajout.php" method="POST">
        <br><br><br>
        <div class="formbold-form-title">
            <h2>Definir le directeur general</h2>
        </div>
        <label for="responsable" class="formbold-form-label">Responsable :</label>
            <input style="width: 367px; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput" oninput="searchSelect(event)" placeholder="Rechercher son nom..">
            <div id="searchResults" style="display:none;"></div>
            <div style="display:flex;width:100%;">
            <input type="text" name="dg" id="responsable" class="formbold-form-input" readonly required>
           </div>
            <select  id="responsable" name="dg" style="display:none;">
              <?php foreach($resultp as $rowp) { ?>
                <option value="<?php echo $rowp['id_employees']; ?>"><?php echo $rowp['nom']." ".$rowp['prenom']; ?></option>
              <?php } ?>
            </select>
          
        <button type="submit" name="dg">Ajouter</button>
        <button type="button" onclick="closePopup()">Annuler</button>
    </form>
</div>

<script>
  function searchSelect(event) {
  document.getElementById('searchResults').style.display = 'block';
  const searchTerm = event.target.value.toLowerCase();
  const options = document.querySelectorAll('#responsable option');
  const searchResults = document.getElementById('searchResults');

  searchResults.innerHTML = '';
  options.forEach(option => {
    if (option.textContent.toLowerCase().includes(searchTerm)) {
      const result = document.createElement('div');
      result.textContent = option.textContent;
      result.addEventListener('click', () => {
        const select = document.getElementById('responsable');
        select.value = option.value;
        searchResults.innerHTML = '';
      });
      searchResults.appendChild(result);
    }
  });
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 
 function openPopupConfirmation() {
    
      var confirmationPopup = document.getElementById('confirmation');
    if (confirmationPopup) {
        confirmationPopup.style.display = 'block';

        // Empêcher le comportement par défaut du formulaire
        var form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });

        var nom = document.getElementById("nom").value;
        localStorage.setItem("category", JSON.stringify(nom));
        var selectedNom = localStorage.getItem("category");
        var categoryValue = JSON.parse(selectedNom);
        document.getElementById("popup-nom").value = categoryValue;
        
      }
       }

       function openPopupConfirmation_b() {
        var confirmationPopup_b = document.getElementById('confirmation_b');
        if (confirmationPopup_b) {
          confirmationPopup_b.style.display = 'block';
          var form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });

        var nomb = document.getElementById("nom_departement").value;
        localStorage.setItem("categoryb", JSON.stringify(nomb));
        var selectedNomb = localStorage.getItem("categoryb");
        var categoryValueb = JSON.parse(selectedNomb);
        document.getElementById("popup-nom-b").value = categoryValueb;;

          var superSelect = document.getElementById("super");
          var superValue = superSelect.options[superSelect.selectedIndex].value;
          localStorage.setItem("direction", JSON.stringify(superValue));

          var selectedDirection = localStorage.getItem("direction");
          var Value = JSON.parse(selectedDirection);
          document.getElementById("popup-super").value = Value;

          
        }
      }
      function openDG(){
        document.getElementById('dg').style.display = 'block';
      }
function closePopup() {
    document.getElementById('confirmation').style.display = 'none';
    document.getElementById('confirmation_b').style.display = 'none';
    document.getElementById('dg').style.display = 'none';
}



</script>

             
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>