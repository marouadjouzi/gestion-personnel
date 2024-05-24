<?php
 session_start();

 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "baosem";
 
 if(isset($_POST['Ajouter'])){
     $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     $id =  $_POST['id'];
     $type = $_POST['type'];
     $date = $_POST['date'];
     $sql = "INSERT INTO requisition (d_employe, type, jour) VALUES (:id, :type, :date)";
     $stmt = $conn->prepare($sql);
     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
     $stmt->bindParam(':type', $type, PDO::PARAM_STR);
     $stmt->bindParam(':date', $date, PDO::PARAM_STR);
     $stmt->execute();
     
     $requete1 = "SELECT COUNT(*) as count FROM conge_requisition WHERE d_employee = :id";
      $stat = $conn->prepare($requete1);
      $stat->bindParam(':id', $id, PDO::PARAM_INT);
      $stat->execute();
      $resultat = $stat->fetch(PDO::FETCH_ASSOC);
     
     if ($resultat['count'] > 0) {
         $requete2 = "SELECT * FROM conge_requisition WHERE d_employee = ?";
         $stat2 = $conn->prepare($requete2);
         $stat2->bind_param('i', $id);
         $stat2->execute();
         $resultat2 = $stat2->fetch(PDO::FETCH_ASSOC);
         $jrs_requisition = $resultat2['jrs_requisition'];
     
         if ($type == "vendredi") {
             $auto = $jrs_requisition + 2;
         } else {
             $auto = $jrs_requisition + 1;
         }
         $rest = $auto;
     
         $requete3 = "UPDATE conge_requisition SET jrs_rest = ?, jrs_requisition = ? WHERE d_employee = ?";
         $stat3 = $conn->prepare($requete3);
         $stat3->bind_param('iii', $rest, $auto, $id);
         $stat3->execute();
     
         header('Location: conge.php');
         exit;
     } else {
         if ($type == "vendredi") {
             $auto1 = 2;
             $rest1 = $auto1;
         } else {
             $auto1 = 1;
             $rest1 = $auto1;
         }
     
         $requete31 = "INSERT INTO conge_requisition (jrs_rest, jrs_requisition, d_employee) VALUES (:rest1, :auto1, :id)";
          $stat31 = $conn->prepare($requete31);
          $stat31->execute(array(':rest1' => $rest1, ':auto1' => $auto1, ':id' => $id));
              
         header('Location: requisition.php');
         exit;
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
 
        <form action="ajout-requisition.php" method="POST">
          <div class="formbold-form-title">
            <h2 class="">Définir une requisition</h2>
            <p>
              Ce formulaire vous permet d'ajouter une requisition a cet element
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
          <label for="type"class="formbold-form-label">Choisir le type de requisition:</label>
          <select name="type" id="type"  required>
                    <option value="">Veuillez choisir :</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                    <option value="autre">Autre</option>
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
            <label for="date" class="formbold-form-label">
                Date de requision:
              
                <span class="datepicker-toggle">
                    <span class="datepicker-toggle-button"></span>
                    <input type="date" name="date" id="date" class="datepicker-input" required>
                </span>
            </div>
            
           
            
          <button class="formbold-btn" name ="Ajouter">Enregistrer</button>
          <a href="requisition.php" class="formbold-btn" style="text-decoration:none;">Retour</a>
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
      .datepicker-toggle {
        display: inline-block;
        position: relative;
        width: 18px;
        height: 19px;
        }
      .datepicker-toggle input{
        height: 142%;
        font-size: 17px;
        font-family: 'Inter';
        border: none;
        color: #353333;
      }
      .datepicker-toggle-button {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml;base64,...');
        }
    </style>
    

        </body>
</html>