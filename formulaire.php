<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baosem";
$db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<script>alert("La connexion a échoué : ' . $e->getMessage() . '")</script>';
}


if (isset($_POST['send'])) {
    $type_conge = $_POST['type_conge'];
    $nbr_jours = $_POST['nbr_jours'];
    $date_d = $_POST['date_d'];
    $date_f = $_POST['date_f'];
    $date_r = $_POST['date_r'];
    $d_employee = $_SESSION['id'];
    if(isset($_POST['reclamation'])){
    $reclamation = $_POST['reclamation'];}
    else{
      $reclamation = '';
    }
      $exercice = $_POST['exercice'];
      
    if($_SESSION['approbation'] != 0){
      $approbation = $_SESSION['approbation'];
    $requete = "SELECT * FROM departement WHERE nom_departement = :nom_departement";
    $statment = $conn->prepare($requete);
    $statment->bindParam(":nom_departement", $approbation);
    $statment->execute();
    $resultat = $statment->fetchAll(PDO::FETCH_ASSOC);

    $requete1 = "SELECT * FROM direction WHERE nom_direction = :nom_direction";
    $statment1 = $conn->prepare($requete1);
    $statment1->bindParam(":nom_direction", $approbation);
    $statment1->execute();
    $resultat1 = $statment1->fetchAll(PDO::FETCH_ASSOC);
      if (count($resultat) > 0) { 
        $val_direct_general = '0';
        $val_departement = '1';
        $val_direction = '0';
        $val1_rh = '0';
        $val2_rh = '0';
        

        $requete44 = "SELECT * FROM employees WHERE id_employees = :id";
        $stmt44 = $db->prepare($requete44);
        $stmt44->bindParam(':id', $_SESSION['id']);
        $stmt44->execute();
        if ($stmt44->rowCount() > 0) {
        while ($line44 = $stmt44->fetch(PDO::FETCH_ASSOC)) {
        $requete55 = "SELECT * FROM departement WHERE nom_departement = :nom_departement";
        $stmt55 = $db->prepare($requete55);
        $stmt55->bindParam(':nom_departement', $_SESSION['approbation']);
        $stmt55->execute();
        if ($stmt55->rowCount() > 0) {
            while ($line55 = $stmt55->fetch(PDO::FETCH_ASSOC)) {
                $requete555 = "SELECT * FROM direction WHERE id_direction = :id";
                $stmt555 = $db->prepare($requete555);
                $stmt555->bindParam(':id', $line55['d_direction']);
                $stmt555->execute();
                if ($stmt555->rowCount() > 0) {
                    while ($line555 = $stmt555->fetch(PDO::FETCH_ASSOC)) {
                        $id_send = $line55['id_departement'];
                        $id_receive = $line555['id_direction'];
                        $message = "Vous avez recu une demande de conge " . $type_conge . " de la part de " . $line44['nom'] . " " . $line44['prenom'];
                        
                        $current_datetime = get_current_datetime();

                        $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                        $not_stmt = $db->prepare($notif);
                        $not_stmt->bindParam(':id_send', $id_send);
                        $not_stmt->bindParam(':id_receive', $id_receive);
                        $not_stmt->bindParam(':message', $message);
                        $not_stmt->bindParam(':current_datetime', $current_datetime);
                        $not_stmt->execute();
                    }
                    }
                }
            }
        }
      }
    }else if (count($resultat1) > 0){
      $val_direct_general = '0';
      $val_departement = '1';
      $val_direction = '1';
      $val1_rh = '0';
      $val2_rh = '0';

      $requete44 = "SELECT * FROM employees WHERE id_employees = :id";
        $stmt44 = $db->prepare($requete44);
        $stmt44->bindParam(':id', $_SESSION['id']);
        $stmt44->execute();
        if ($stmt44->rowCount() > 0) {
        while ($line44 = $stmt44->fetch(PDO::FETCH_ASSOC)) {
        $requete55 = "SELECT * FROM  WHERE nom_direction = :nom_direction";
        $stmt55 = $db->prepare($requete55);
        $stmt55->bindParam(':nom_direction', $_SESSION['approbation']);
        $stmt55->execute();
        if ($stmt55->rowCount() > 0) {
            while ($line55 = $stmt55->fetch(PDO::FETCH_ASSOC)) {
                $role = "superadmin";
                $requete555 = "SELECT * FROM role WHERE role = :role";
                $stmt555 = $db->prepare($requete555);
                $stmt555->bindParam(':role', $role);
                $stmt555->execute();
                if ($stmt555->rowCount() > 0) {
                    while ($line555 = $stmt555->fetch(PDO::FETCH_ASSOC)) {
                        $id_send = $line55['id_direction'];
                        $id_receive = $line555['id'];
                        $message = "Vous avez recu une demande de conge " . $type_conge . " de la part de " . $line44['nom'] . " " . $line44['prenom'];
                        
                        $current_datetime = get_current_datetime();

                        $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                        $not_stmt = $db->prepare($notif);
                        $not_stmt->bindParam(':id_send', $id_send);
                        $not_stmt->bindParam(':id_receive', $id_receive);
                        $not_stmt->bindParam(':message', $message);
                        $not_stmt->bindParam(':current_datetime', $current_datetime);
                        $not_stmt->execute();
                    }
                    }
                }
            }
        }
      }

  }else if ($_SESSION['role'] == 'superadmin'){
        $val_direct_general = '0';
        $val_departement = '1';
        $val_direction = '1';
        $val1_rh = '1';
        $val2_rh = '0';

        
      $requete44 = "SELECT * FROM employees WHERE id_employees = :id";
      $stmt44 = $db->prepare($requete44);
      $stmt44->bindParam(':id', $_SESSION['id']);
      $stmt44->execute();
      if ($stmt44->rowCount() > 0) {
      while ($line44 = $stmt44->fetch(PDO::FETCH_ASSOC)) {
      $role = 'superadmin';  
      $requete55 = "SELECT * FROM  WHERE role = :role";
      $stmt55 = $db->prepare($requete55);
      $stmt55->bindParam(':role', $role);
      $stmt55->execute();
      if ($stmt55->rowCount() > 0) {
          while ($line55 = $stmt55->fetch(PDO::FETCH_ASSOC)) {
              $approbation = "dg";
              $requete555 = "SELECT * FROM role WHERE approbation = :approbation";
              $stmt555 = $db->prepare($requete555);
              $stmt555->bindParam(':approbation', $approbation);
              $stmt555->execute();
              if ($stmt555->rowCount() > 0) {
                  while ($line555 = $stmt555->fetch(PDO::FETCH_ASSOC)) {
                      $id_send = $line55['id'];
                      $id_receive = $line555['id'];
                      $message = "Vous avez recu une demande de conge " . $type_conge . " de la part de " . $line44['nom'] . " " . $line44['prenom'];
                      
                      $current_datetime = get_current_datetime();

                      $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                      $not_stmt = $db->prepare($notif);
                      $not_stmt->bindParam(':id_send', $id_send);
                      $not_stmt->bindParam(':id_receive', $id_receive);
                      $not_stmt->bindParam(':message', $message);
                      $not_stmt->bindParam(':current_datetime', $current_datetime);
                      $not_stmt->execute();
                  }
                  }
              }
          }
      }
    }


    }else if ($_SESSION['approbation'] == 'dg'){
      $val_direct_general = '1';
      $val_departement = '1';
      $val_direction = '1';
      $val1_rh = '1';
      $val2_rh = '0';

      $requete44 = "SELECT * FROM employees WHERE id_employees = :id";
        $stmt44 = $db->prepare($requete44);
        $stmt44->bindParam(':id', $_SESSION['id']);
        $stmt44->execute();
        if ($stmt44->rowCount() > 0) {
        while ($line44 = $stmt44->fetch(PDO::FETCH_ASSOC)) {
        $role = 'superadmin';  
        $requete55 = "SELECT * FROM  WHERE role = :role";
        $stmt55 = $db->prepare($requete55);
        $stmt55->bindParam(':role', $role);
        $stmt55->execute();
        if ($stmt55->rowCount() > 0) {
            while ($line55 = $stmt55->fetch(PDO::FETCH_ASSOC)) {
                $approbation = "dg";
                $requete555 = "SELECT * FROM role WHERE approbation = :approbation";
                $stmt555 = $db->prepare($requete555);
                $stmt555->bindParam(':approbation', $approbation);
                $stmt555->execute();
                if ($stmt555->rowCount() > 0) {
                    while ($line555 = $stmt555->fetch(PDO::FETCH_ASSOC)) {
                        $id_send = $line555['id'];
                        $id_receive = $line55['id'];
                        $message = "Vous avez recu une demande de conge " . $type_conge . " de la part de " . $line44['nom'] . " " . $line44['prenom'];
                        
                        $current_datetime = get_current_datetime();

                        $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                        $not_stmt = $db->prepare($notif);
                        $not_stmt->bindParam(':id_send', $id_send);
                        $not_stmt->bindParam(':id_receive', $id_receive);
                        $not_stmt->bindParam(':message', $message);
                        $not_stmt->bindParam(':current_datetime', $current_datetime);
                        $not_stmt->execute();
                    }
                    }
                }
            }
        }
      }

  }}
  else{

      $val_direct_general = '0';
      $val_departement = '0';
      $val_direction = '0';
      $val1_rh = '0';
      $val2_rh = '0';

  }
  
  
    // Check if the end date is after the start date and if the number of days corresponds to the difference between the end and start dates
    //if(strtotime($date_f) == strtotime("+$nbr_jours days", strtotime($date_d)) && strtotime($date_r) > strtotime($date_f) && strtotime($date_f) > strtotime($date_d)) {
        try {
            // Préparation et exécution de la requête d'insertion
            $sql = "INSERT INTO demande_conge (type_conge, nbr_jours, date_d, date_f, reprise, reclamation,exercice, d_employee, val_departement, val_direction, val1_rh, val_direct_general, val2_rh) 
            SELECT :type_conge, :nbr_jours, :date_d, :date_f, :date_r, :reclamation,:exercice, :d_employee, :val_departement, :val_direction, :val1_rh, :val_direct_general, :val2_rh  
            FROM dual 
            WHERE NOT EXISTS (
                SELECT 1 
                FROM demande_conge 
                WHERE type_conge = :type_conge 
                AND nbr_jours = :nbr_jours
                AND date_d = :date_d
                AND date_f = :date_f
                AND reprise = :date_r
                AND reclamation = :reclamation
                AND exercice = :exercice
                AND d_employee = :d_employee
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':type_conge', $type_conge);
            $stmt->bindParam(':nbr_jours', $nbr_jours); 
            $stmt->bindParam(':date_d', $date_d);
            $stmt->bindParam(':date_f', $date_f);
            $stmt->bindParam(':date_r', $date_r);
            $stmt->bindParam(':reclamation', $reclamation);
            $stmt->bindParam(':exercice', $exercice);
            $stmt->bindParam(':d_employee', $d_employee);
            $stmt->bindParam(':val_departement', $val_departement);
            $stmt->bindParam(':val_direction', $val_direction);
            $stmt->bindParam(':val1_rh', $val1_rh); 
            $stmt->bindParam(':val_direct_general', $val_direct_general);
            $stmt->bindParam(':val2_rh', $val2_rh);
            $stmt->execute();
            // $reponse = $requete->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($reponse);
            
if ($stmt) {
    $requete44 = "SELECT * FROM employees WHERE id_employees = :id";
    $stmt44 = $db->prepare($requete44);
    $stmt44->bindParam(':id', $_SESSION['id']);
    $stmt44->execute();
    if ($stmt44->rowCount() > 0) {
        while ($line44 = $stmt44->fetch(PDO::FETCH_ASSOC)) {
            $requete444 = "SELECT * FROM departement WHERE id_departement = :id";
            $stmt444 = $db->prepare($requete444);
            $stmt444->bindParam(':id', $line44['d_departement']);
            $stmt444->execute();
            if ($stmt444->rowCount() > 0) {
                while ($line444 = $stmt444->fetch(PDO::FETCH_ASSOC)) {
                    $id_send = $_SESSION['id'];
                    $id_receive = $line444['id_departement'];
                    $message = "Vous avez recu une demande de conge " . $type_conge . " de la part de " . $line44['nom'] . " " . $line44['prenom'];
                    
                  $current_datetime = get_current_datetime();
                    $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                    $not_stmt = $db->prepare($notif);
                    $not_stmt->bindParam(':id_send', $id_send);
                    $not_stmt->bindParam(':id_receive', $id_receive);
                    $not_stmt->bindParam(':message', $message);
                    $not_stmt->bindParam(':current_datetime', $current_datetime);
                    $not_stmt->execute();
                }
            }
        }
    }
}
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        
      }
      if (isset($_POST['envoie'])) {
        $exercice = $_POST['exerce'];
        echo'<script>console.log('. $exercice.');</script>';
        $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
        $stm = $db->prepare('SELECT * FROM conge_annuel WHERE d_employe = :id AND exercice = :exercice');
        $stm->bindParam(':id', $_SESSION['id']);
        $stm->bindParam(':exercice', $exercice);
        $stm->execute();
        $r = $stm->fetchAll();
    }
    $stm1 = $db->prepare('SELECT * FROM conge_requisition Where d_employee = :id');
    $stm1->bindParam(':id', $_SESSION['id']);
    $stm1->execute();
    $r1 = $stm1->fetchAll();
    function get_current_datetime() {
      return date('Y-m-d H:i:s');
      
      }
?>
<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
 
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
    <link rel="stylesheet" href="formulaire.css" />
  </head>
  <body>


    <section class="container" style="display: block;">
      <header>demande de conge</header>
      <form  class="form" action="" method="post">
       <div class="gender-box">
          <div class="gender-option">
            <div class="gender" >
              <input type="radio" id="check-male"  name="type_conge" value="annuel detente"  onchange="showFields()" />
              <label> annuel detente</label>
            </div>
            <div class="gender">
              <input type="radio" id="check-female"  name="type_conge"value="recuperation" onchange="showFields()" />
              <label >recuperation</label>
            </div>
            <div class="gender">
              <input type="radio" id="check-other"  name="type_conge" value="exceptionel"   onchange="showFields()"/>
              <label >exceptionel</label>
            </div>
          </div>
        </div>
        <div id="dynamicFields"></div> 
        <div class="btn">
        <button  name="send">Envoyer</button>
      </div>
      </form>
    </section>
    <script>

</script>
    <script>
      function preventNumber30(event) {
        // Récupérer la valeur saisie dans le champ
        let inputValue = event.target.value;

       // Vérifier si la valeur saisie est égale à 30
      if (inputValue > 30) {
        // Empêcher la saisie du nombre 30 en réinitialisant la valeur du champ
        event.target.value = "";
              
            }
          }
      
      <?php  if (!empty($r)) {
    foreach ($r as $row) { ?>
    function preventNumber(event) {
        // Récupérer la valeur saisie dans le champ
        let inputValue = event.target.value;

       // Vérifier si la valeur saisie est égale à 30
      if (inputValue > <?php echo $row['jrs_restant']; ?>) {
        // Empêcher la saisie du nombre 30 en réinitialisant la valeur du champ
        event.target.value = "";
              
            }
          }
      <?php
          }
        }
      ?>
      <?php  if (!empty($r1)) {
          foreach ($r1 as $row1) {
              ?>
      function preventNumber2(event) {
          // Récupérer la valeur saisie dans le champ
          let inputValue = event.target.value;

          // Vérifier si la valeur saisie est égale à 30
          if (inputValue > <?php echo $row1['jrs_rest']; ?>) {
              // Empêcher la saisie du nombre 30 en réinitialisant la valeur du champ
              event.target.value = "";
              
          }
      }
      <?php
          }
        }
      ?>
      function showFields() {
      var type = document.querySelector('input[name="type_conge"]:checked').value;
      var dynamicFields = document.getElementById("dynamicFields");
  
      dynamicFields.innerHTML = ""; // Clear previous fields
      if (type == "annuel detente") {
        
          dynamicFields.innerHTML += `
          <div class="input-box address">
          
        <?php  if (!empty($r)) {
    foreach ($r as $row) { ?>
      
       
        <label>nombre de jours demande</label>
        <button type="submit" style="background: none; border: none; color:#000; " title="vous avez <?php echo $row['jrs_restant']; ?> jours de l'exercice <?php echo $row['exercice']; ?>">
            <i class='bx bx-info-circle'></i>
        </button>
        <input type="hidden" name="exercice" name="exercice" value="<?php echo $row['exercice']; ?>">
        <input type="number" id="nbr_jours" name="nbr_jours" min="0" oninput="preventNumber(event)" max="<?php echo $row['jrs_restant']; ?>" required oninput="calculerDateFin()">
        
        <?php
    }
  }
    ?>


          <div class="column">
            <div class="input-box">
            <label> date de depart</label>
            <input type="date" id="date_d" name="date_d" required oninput="calculerDateFin()" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
           
            </div>
            <div class="input-box">
            <label> date de fin de conge</label>
            <input type="date" id="date_f" name="date_f" required readonly lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
          </div>
            <div class="input-box">
            <label> date de reprise</label>
            <input type="date" placeholder=""name="date_r" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
          </div>
        </div>
      </div>
       `
      
      ;
          } 
        else 
          if (type === "recuperation") {
            


          dynamicFields.innerHTML += `
          <div class="input-box address">
          
          <?php  if (!empty($r1)) {
          foreach ($r1 as $row1) {
              ?>
              
              <label>nombre de jours demande</label>
              <button type="submit" style="background: none; border: none; color:#000; " title="vous avez <?php echo $row1['jrs_rest']; ?> de recuperartion">
            <i class='bx bx-info-circle'></i>
        </button>
              <input type="number" id="nbr_jours" name="nbr_jours" min="0" oninput="preventNumber2(event)" max="<?php echo $row1['jrs_rest']; ?>" required oninput="calculerDateFin()">
              <?php
          }
        }
          ?>

          <div class="column">
            <div class="input-box">
            <label> date de depart</label>
            <input type="date" id="date_d" name="date_d" required oninput="calculerDateFin()" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
           
            </div>
            <div class="input-box">
            <label> date de fin de conge</label>
            <input type="date" id="date_f" name="date_f" required readonly lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
          </div>
            <div class="input-box">
            <label> date de reprise</label>
            <input type="date" placeholder=""name="date_r" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
          </div>
        </div>
      </div>  `;
}else 
          if (type === "exceptionel") {
          dynamicFields.innerHTML += `
          <div class="input-box address">
          
          <label>nombre de jours demande</label>
          <input type="number" id="nbr_jours" name="nbr_jours" min="0" required oninput="calculerDateFin()" oninput="preventNumber30(event)">


          <div class="column">
            <div class="input-box">
            <label> date de depart</label>
            <input type="date" id="date_d" name="date_d" required oninput="calculerDateFin()"lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
           
            </div>
            <div class="input-box">
            <label> date de fin de conge</label>
            <input type="date" id="date_f" name="date_f" required readonly lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
          </div>
            <div class="input-box">
            <label> date de reprise</label>
            <input type="date" placeholder=""name="date_r" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa">
          </div>
        </div>
        <label>Reclamation</label>
          <input type="text"  name="reclamation"/>
      </div>  `;
}
}  

function calculerDateFin() {
  // Get the start date and number of days from the input fields
  const dateDebutInput = document.querySelector('input[name="date_d"]');
  const nbrJoursInput = document.querySelector('input[name="nbr_jours"]');

  const dateDebut = new Date(dateDebutInput.value);
  const nbrJours = parseInt(nbrJoursInput.value, 10);

  // Calculate the end date by adding the number of days to the start date
  const dateFin = new Date(dateDebut);
  dateFin.setDate(dateDebut.getDate() + nbrJours - 1);

  // Format the end date as "YYYY-MM-DD" and set the value of the date_f input field
  const year = dateFin.getFullYear();
  const month = String(dateFin.getMonth() + 1).padStart(2, '0');
  const day = String(dateFin.getDate()).padStart(2, '0');
  const dateFInput = document.querySelector('input[name="date_f"]');
  dateFInput.value = `${year}-${month}-${day}`;
}
    </script>
    
  </body>
</html>