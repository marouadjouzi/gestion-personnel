<?php
session_start();

require_once('config.php'); 
if($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin' || $_SESSION['approbation'] == 'dg'){ 
$sql = "SELECT * FROM absence_retard";
$result = $con->query($sql);

$sql0 = "SELECT * FROM conge_maladie";
$result0 = $con->query($sql0);
}
    else if ($_SESSION['role'] == 'user' && $_SESSION['approbation'] != '0') {
        $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
        $st = $db->prepare('SELECT id_direction FROM direction WHERE nom_direction = :name ');
        $st->bindParam(':name', $_SESSION['approbation']);
        $st->execute();
        $id = $st->fetchColumn();
    
        $stm = $db->prepare('SELECT * FROM direction WHERE id_direction = :id');
        $stm->bindParam(':id', $id);
        $stm->execute();
        $id_direction = $stm->fetchColumn();
        $count = $stm->rowCount();
        if ($count > 0) {
            $sql9 = "SELECT e.* 
            FROM employees e
            JOIN departement d ON e.d_departement = d.id_departement
            WHERE d.d_direction = :id_direction";
                $stmt = $db->prepare($sql9);
                $stmt->bindParam(':id_direction', $id_direction);
                $stmt->execute();
                $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($employees)) {
                    $employeeId = $employees[0]['id_employees']; // Supposons que le premier employé de la liste est sélectionné
                
                    $sql = "SELECT * FROM absence_retard WHERE d_employee =" . $employeeId;
                    $result = $con->query($sql);

                    $sql0 = "SELECT * FROM conge_maladie WHERE d_employe = " . $employeeId;
                    $result0 = $con->query($sql0);
                }
           
        }else{
            $st = $db->prepare('SELECT id_departement FROM departement WHERE nom_departement = :name');
$st->bindParam(':name', $_SESSION['approbation']);
$st->execute();
$id_departement = $st->fetchColumn();

if ($id_departement) {
    $sql9 = "SELECT e.* 
            FROM employees e
            JOIN departement d ON e.d_departement = d.id_departement
            WHERE d.id_departement = :id_departement";
    $stmt = $db->prepare($sql9);
    $stmt->bindParam(':id_departement', $id_departement);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);


                    if (!empty($employees)) {
                        $employeeId = $employees[0]['id_employees']; // Supposons que le premier employé de la liste est sélectionné
                    
                        $sql = "SELECT * FROM absence_retard WHERE d_employee =" . $employeeId;
                        $result = $con->query($sql);

                        $sql0 = "SELECT * FROM conge_maladie WHERE d_employe = " . $employeeId;
                        $result0 = $con->query($sql0);
                    }
        }

        }
}


$sql3 = "SELECT * FROM pret";
$result3 = $con->query($sql3);


$sql4 = "SELECT a.d_employee, SUM(a.duree) AS total_duree, e.nom, e.prenom 
        FROM absence_retard a
        JOIN employees e ON a.d_employee = e.id_employees
        GROUP BY a.d_employee, e.nom, e.prenom";
$result4 = $con->query($sql4);

// Préparer les données pour le graphique
$labels1 = array();
$data1 = array();
while($row4 = $result4->fetch_assoc()) {
    $fullName = $row4["prenom"] . " " . $row4["nom"];
    $labels1[] = $fullName;
    $data1[] = $row4["total_duree"];
}


// Afficher le graphique



// Parcours des résultats de la requête
$sql5 = "SELECT e.nom, e.prenom, p.rtn_electro, p.rtn_vehicule, p.rtn_admin
         FROM employees e
         JOIN pret p ON e.id_employees = p.id_employees";
$result5 = $con->query($sql5);

if ($result5->num_rows > 0) {
    $data = array();
    $labels = array();

    while($row5 = $result5->fetch_assoc()) {
        $fullName = $row5["nom"] . " " . $row5["prenom"];

        if (!in_array($fullName, $labels)) {
            $labels[] = $fullName;
            $data[$fullName] = array(
                "rtn_electro" => 0,
                "rtn_vehicule" => 0,
                "rtn_admin" => 0
            );
        }

        $data[$fullName]["rtn_electro"] += $row5["rtn_electro"];
        $data[$fullName]["rtn_vehicule"] += $row5["rtn_vehicule"];
        $data[$fullName]["rtn_admin"] += $row5["rtn_admin"];
    }

    // Préparer les données pour le graphique
    $electromenager = array();
    $vehicule = array();
    $administratif = array();

    foreach ($labels as $label) {
        $electromenager[] = $data[$label]["rtn_electro"];
        $vehicule[] = $data[$label]["rtn_vehicule"];
        $administratif[] = $data[$label]["rtn_admin"];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link rel="stylesheet" href="superadmin.css">
    <link rel="stylesheet" href="absence.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <title>Gestion des absences</title>
</head>
<body id="body-pd">
   <!--SIDE BAR-->
   <div class="l-navbar" id="navbar">
            <nav class="nav">
                <div>
                    <div class="nav__brand">
                        <ion-icon name="menu-outline" class="nav__toggle" id="nav-toggle"></ion-icon>
                        <a href="#" class="nav__logo">BAOSEM</a>
                    </div>
                    <div class="nav__list">
                        <a href="profil.php" class="nav__link ">
                            <ion-icon name="home-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Acceuil</span>
                        </a>
                        <div  class="nav__link collapse" >
                            <ion-icon name="business-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Définition</span>

                            <ion-icon name="chevron-down-outline" class="collapse__link"></ion-icon>

                            <ul class="collapse__menu">
                                <?php if($_SESSION['role']== 'superadmin' || $_SESSION['role']== 'admin' ){?>
                                <a href="admin_sup.php"class="nav__link " class="collapse__sublink" >Société</a>
                                <a href="role.php" class="collapse__sublink">Roles</a>
                                <?php }?>
                                <a href="structure.php" class="collapse__sublink">Structure</a>
                                
                            </ul>
                        </div>
                    
                       <?php if($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin'){?>
                        <a href="conge.php" class="nav__link ">
                            <ion-icon name="calendar-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Congés</span>
                        </a>
                        <?php }else  if ($_SESSION['role'] == 'user' && $_SESSION['approbation'] != '0') {
                                $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
                                $st = $db->prepare('SELECT id_direction FROM direction WHERE nom_direction = :name ');
                                $st->bindParam(':name', $_SESSION['approbation']);
                                $st->execute();
                                $id = $st->fetchColumn();
                            
                                $stm = $db->prepare('SELECT * FROM direction WHERE id_direction = :id');
                                $stm->bindParam(':id', $id);
                                $stm->execute();
                                $id_direction = $stm->fetchColumn();
                                $count = $stm->rowCount();
                                if ($count > 0) {?>
                            <a href="conge_direct.php" class="nav__link ">
                                <ion-icon name="calendar-outline" class="nav__icon"></ion-icon>
                                <span class="nav__name">Congés</span>
                            </a>
                        <?php }else {
                            $st = $db->prepare('SELECT id_departement FROM departement WHERE nom_departement = :name');
                            $st->bindParam(':name', $_SESSION['approbation']);
                            $st->execute();
                            $id_departement = $st->fetchColumn();

                            if ($id_departement) {
                                ?>
                                <a href="conge_depar.php" class="nav__link ">
                                    <ion-icon name="calendar-outline" class="nav__icon"></ion-icon>
                                    <span class="nav__name">Congés</span>
                                </a>
                                <?php
                            }
                        }  if($_SESSION['approbation'] == 'dg'){?>
                        <a href="conge_dg.php" class="nav__link ">
                                    <ion-icon name="calendar-outline" class="nav__icon"></ion-icon>
                                    <span class="nav__name">Congés</span>
                                </a>
                        <?php }}?>
                        <a href="absence.php" class="nav__link active">
                        <ion-icon name="stats-chart-outline" style="font-size:25px;"></ion-icon>
                            <span class="nav__name">Suivi Abscences</span>
                        </a>
                    </div>
                </div>

                <a onclick="logout()" class="nav__link">
                    <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                    <span class="nav__name">DECONNECTER</span>
                </a>
            </nav>
        </div>
        
        <main class="main-container">
          <div class="info--wrapper" style='background-color:"#e1d9ff"'>
          <div style='margin-left:60%;'>
           
           </div>
           <div class="main-title" style="justify-content:">
            <button class="button-36" role="button" onclick="showTable(1)">Les absences / retards</button>
            <button class="button-36" role="button" onclick="showTable(2)">conge de maladie</button>
            <button class="button-36" role="button" onclick="showTable(3)">Les retenus des prets</button>
    
              <div class="wrap" style="width:30%;">
                    <div class="search">
                        <input type="text"  id="searchInput" oninput="searchTable()" class="searchTerm" placeholder="Recherche....">
                        <button type="submit" class="searchButton">
                        <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </div>
             
           </div>
          
         </div>
        </main> 
       
<br><br><div id="table11" style="display:none;">
        <h1>Table des absence et retard</h1>
        <br>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0" >
      <thead>
        <tr>
          <th>ID_Employé</th>
          <th>Nom et Prenom</th>
          <th>Departement</th>
          <th>Fonction</th>
          <th>Nombre d'heure retard/absence</th>
          <th>piece</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    
    <table cellpadding="0" cellspacing="0" border="0"id="table1">
      <tbody>
      <?php 
        while($row = $result->fetch_assoc()) {
            $sql1 = "SELECT * FROM employees WHERE id_employees = " . $row['d_employee'];
            $result1 = $con->query($sql1);
            while($row1 = $result1->fetch_assoc()) {
                $sql2 = "SELECT * FROM departement WHERE id_departement = " . $row1['d_departement'];
                $result2 = $con->query($sql2);
                while($row2 = $result2->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row1['id_employees'];?></td>
            <td><?php echo $row1['nom']." ".$row1['prenom'];?></td>
            <td><?php echo $row2['nom_departement'];?></td>
            <td><?php echo $row1['fonction'];?></td>
            <td>
                <span id="duration1-<?php echo $row['id']; ?>"><?php echo $row['duree']; ?> heures</span>
            </td>
            <script>
                convertHoursToDay2(document.getElementById('duration1-<?php echo $row['id']; ?>'));
            </script>
             <td>
                <button type="button" style="border: none; border-radius: 3px;font-size:20px; background-color: black; color: white;" onclick="openPopup_i2(<?php echo $row1['id_employees']; ?>)" title="Ouvrir le dossier des justificatifs">Voir <i class='bx bx-folder-open'></i></button>
            <div class="popup_i12" style="display:none;" data-id="<?php echo $row1['id_employees']; ?>">
                <div id="confirmation_s" class="popup_c">
                    <button onclick="closePopup_i2(<?php echo $row1['id_employees']; ?>)" style="margin-left: 380px; border-radius: 16px; border: none; background-color: red; font-size: large; color: #fff;"><i class='bx bx-x'></i></button>
                    <div class="justificatif-form">
                        <h1 style='font-size:20px;color:#000; text-decoration:underline;'>Liste des justificatifs de <?php echo $row1['nom']." ".$row1['prenom']; ?></h1><br><br>
                        <?php
                        $sql62 = "SELECT * FROM justificatif";
                        $stmt62 = $con->prepare($sql62);
                        if ($stmt62 === false) {
                            die("Error preparing statement: " . mysqli_error($con));
                        }

                        if ($stmt62->execute()) {
                            $result62 = $stmt62->get_result();
                            while ($row62 = $result62->fetch_assoc()) {
                                if($row62['Type'] == '0' && $row62['d_employee'] == $row1['id_employees']){
                        ?>
                           <div style="display:flex;"> <a href="./img/<?php echo $row62['justificatif']; ?>"><?php echo $row62['nom']; ?></a>
                           <?php if($_SESSION['role'] == 'superadmin'){?>
                           <button  onclick="openPopupConfirmation_ss2(<?php echo $row1['id_employees']; ?>)" style="margin-left:10px;background-color:#fff; color:red; border:none; font-size:large">
                                                <ion-icon name="trash-outline"></ion-icon> 
                                            </button>
                                            <?php }?>
                                            </div>
                                            <div id="confirmation_ss2" class="popup_c" style="display:none;" data3-id="<?php echo $row1['id_employees']; ?>">
                                            <form class="justificatif-form" id="confirmation" method="post" action="absence.php">
                                                <input type="hidden" name="id" value="<?php echo $row62['id']; ?>">
                                                <p>Voulez vous vraiment supprimer cet element?</p>
                                                <button type="submit" name="confirmer_supp" style="background-color:red; border:none; font-size:14px;">Confirmer</button>
                                                <button type="button" onclick="closePopup_7(<?php echo $row1['id_employees']; ?>) "style="background-color:gray; border:none; font-size:14px;">Annuler</button>
                                            </form>
            </div>
                        
                     <?php
                                }
                            ?>
                            
                      <?php  }
                      }
                        ?>
                        <?php if($_SESSION['role'] == 'superadmin'){?>
                       <p>----------------------------------------------------------</p>
                        <form class="justificatif-form" id="confirmation-form" method="post" action="absence.php" autocomplete="off" enctype="multipart/form-data">
                            <p>inserer un justificatif pour <?php echo $row1['nom']." ".$row1['prenom']; ?></p>
                            <input type="hidden" name="idp" value="<?php echo $row1['id_employees']; ?>">
                            <label for="text" class="formbold-form-label">donner le nom de la piece :</label><br><br>
                            <input type="text" id="text" name="nom" required><br><br><br>
                            <label for="validation" class="formbold-form-label">Insérer une pièce jointe :</label><br><br>
                            <input type="file" id="photo" name="photo" required><br><br><br>
                            <button class="button-34" type="submit" name="in2">Insérer</button>
                            </form>
                            <?php }?>
                    </div>
                </div>
            </div>
</td>
            </tr>

           
        <?php 
                }
            }
        }
        ?>
      </tbody>
    </table> 
    
  </div>
    <style>
        .bar {
            height: 20px;
            background-color: #4CAF50;
            margin-bottom: 10px;
        }
        .label {
            display: inline-block;
            width: 200px;
            text-align: right;
            margin-right: 10px;
        }
    </style>
    <br><br><br>
    <?php if ($_SESSION['role']== 'superadmin'){ ?> 
    <h1>Graphique des heures d'absence par employé</h1>
    <canvas id="myChart"></canvas>
    <?php }?>
</div>

<div id="table12" style="">
        <h1>Table des conges de maladie</h1>
        <br>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0" >
      <thead>
        <tr>
          <th>ID_Employé</th>
          <th>Nom et Prenom</th>
          <th>Departement</th>
          <th>Fonction</th>
          <th>Nombre d'heure absence maladie</th>
          <th>piece</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0"id="table2">
      <tbody>
      <?php 
        while($row0 = $result0->fetch_assoc()) {
            $sql10 = "SELECT * FROM employees WHERE id_employees = " . $row0['d_employe'];
            $result10 = $con->query($sql10);
            while($row10 = $result10->fetch_assoc()) {
                $sql20 = "SELECT * FROM departement WHERE id_departement = " . $row10['d_departement'];
                $result20 = $con->query($sql20);
                while($row20 = $result20->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row10['id_employees'];?></td>
            <td><?php echo $row10['nom']." ".$row10['prenom'];?></td>
            <td><?php echo $row20['nom_departement'];?></td>
            <td><?php echo $row10['fonction'];?></td>
            <td>
                <span id="duration-<?php echo $row0['id']; ?>"><?php echo $row0['duree']; ?> heures</span>
            </td>
            <script>
                convertHoursToDay(document.getElementById('duration-<?php echo $row0['id']; ?>'));
            </script>
            <td>
                <button type="button" style="border: none; border-radius: 3px;font-size:20px; background-color: black; color: white;" onclick="openPopup_i(<?php echo $row10['id_employees']; ?>)" title="Ouvrir le dossier des justificatifs">Voir <i class='bx bx-folder-open'></i></button>
            </td>
            </tr>

            <div class="popup_i1" style="display:none;" data2-id="<?php echo $row10['id_employees']; ?>">
                <div id="confirmation_s" class="popup_c">
                    <button onclick="closePopup_i(<?php echo $row10['id_employees']; ?>)" style="margin-left: 380px; border-radius: 16px; border: none; background-color: red; font-size: large; color: #fff;"><i class='bx bx-x'></i></button>
                    <div class="justificatif-form">
                        <h1 style='font-size:20px;color:#000; text-decoration:underline;'>Liste des justificatifs de <?php echo $row10['nom']." ".$row10['prenom']; ?></h1>
                        <?php
                        $sql6 = "SELECT * FROM justificatif WHERE d_employee = ?";
                        $stmt6 = $con->prepare($sql6);
                        if ($stmt6 === false) {
                            die("Error preparing statement: " . mysqli_error($con));
                        }

                        $stmt6->bind_param('i', $row10['id_employees']);
                        if ($stmt6->execute()) {
                            $result6 = $stmt6->get_result();
                            while ($row6 = $result6->fetch_assoc()) {
                                if($row6['Type'] == '1'){
                        ?>
                            <div style="display:flex;">
                                <a href="./img/<?php echo $row6['justificatif']; ?>"><?php echo $row6['nom']; ?></a>
                                <?php if($_SESSION['role'] == 'superadmin'){?>
                                <button onclick="openPopupConfirmation_ss2(<?php echo $row10['id_employees']; ?>)" style="margin-left:10px;background-color:#fff; color:red; border:none; font-size:large">
                                    <ion-icon name="trash-outline"></ion-icon> 
                                </button>
                                <?php }?>
                            </div>

                            <div id="confirmation_ss2" class="popup_c4" style="display:none;" data4-id="<?php echo $row10['id_employees']; ?>">
                                <form class="justificatif-form" id="confirmation" method="post" action="absence.php">
                                    <input type="hidden" name="id" value="<?php echo $row6['id']; ?>">
                                    <p>Voulez-vous vraiment supprimer cet élément?</p>
                                    <button type="submit" name="confirmer_supp" style="background-color:red; border:none; font-size:14px;">Confirmer</button>
                                    <button type="button" onclick="closePopup_7(<?php echo $row10['id_employees']; ?>)" style="background-color:gray; border:none; font-size:14px;">Annuler</button>
                                </form>
                            </div>
                        <?php
                                }
                            }
                        }
                        ?>
                        <?php if($_SESSION['role'] == 'superadmin'){?>
                     <p>--------------------------------------------------</p>
                        <form class="justificatif-form" id="confirmation-form" method="post" action="absence.php" autocomplete="off" enctype="multipart/form-data">
                            <p>inserer un justificatif pour <?php echo $row10['nom']." ".$row10['prenom']; ?></p>
                            <input type="hidden" name="idp" value="<?php echo $row10['id_employees']; ?>">
                            <label for="text" class="formbold-form-label">donner le nom de la piece :</label><br><br>
                            <input type="text" id="text" name="nom" required><br><br><br>
                            <label for="validation" class="formbold-form-label">Insérer une pièce jointe :</label><br><br>
                            <input type="file" id="photo" name="photo" required><br><br><br>
                            <button class="button-34" type="submit" name="in">Insérer</button>
                            <button class="button-34" type="button" onclick="closePopup_j()">Annuler</button>
                    </form>
                  <?php }?>
                </div>
            </div>
                    </div>
        <?php 
                }
            }
        }
        ?>
      </tbody>
    </table>
  </div>
    <style>
         .popup_i1, .popup_i11,.popup_i12, .popup_i112 {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #f9f9f9;
    padding: 20px;
    border: 2px solid #333;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    max-width: 80%;
    max-height: 80%;
    overflow: auto;
}

.popup_i1 p, .popup_i11 p,.popup_i12 p, .popup_i112 p {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

.popup_i1 a, .popup_i11 a,.popup_i12 a, .popup_i112 a {
    display: block;
    margin-bottom: 5px;
    text-decoration: none;
    color: #333;
    font-size:14px
}

.popup_i1 a:hover, .popup_i11 a:hover,.popup_i12 a:hover, .popup_i112 a:hover {
    color: #007bff;
}
        .bar {
            height: 20px;
            background-color: #4CAF50;
            margin-bottom: 10px;
        }
        .label {
            display: inline-block;
            width: 200px;
            text-align: right;
            margin-right: 10px;
        }
    </style>
<style>
 .button-34 {
 background: #5E5DF0;
 border-radius: 999px;
 box-shadow: #5E5DF0 0 10px 20px -10px;
 box-sizing: border-box;
 color: #FFFFFF;
 cursor: pointer;
 font-family: Inter,Helvetica,"Apple Color Emoji","Segoe UI Emoji",NotoColorEmoji,"Noto Color Emoji","Segoe UI Symbol","Android Emoji",EmojiSymbols,-apple-system,system-ui,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans",sans-serif;
 font-size: 16px;
 font-weight: 700;
 line-height: 24px;
 opacity: 1;
 outline: 0 solid transparent;
 padding: 8px 18px;
 user-select: none;
 -webkit-user-select: none;
 touch-action: manipulation;
 width: fit-content;
 word-break: break-word;
 border: 0;
 margin-bottom:30px;
  }
</style>       
    <br><br><br>
</div>

<div id="table13" style="display:none;">
        <h1>Table des retenus des prets</h1>
        <br>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0" >
      <thead>
        <tr>
          <th>ID_Employé</th>
          <th>Nom et Prenom</th>
          <th>Departement</th>
          <th>Fonction</th>
          <th>electro(DA)</th>
          <th>vehicule(DA)</th>
          <th>administratif(DA)</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0"id="table2">
      <tbody>
      <?php 
        while($row3 = $result3->fetch_assoc()) {
            $sql30 = "SELECT * FROM employees WHERE id_employees = " . $row3['id_employees'];
            $result30 = $con->query($sql30);
            while($row30 = $result30->fetch_assoc()) {
                $sql203 = "SELECT * FROM departement WHERE id_departement = " . $row30['d_departement'];
                $result203 = $con->query($sql20);
                while($row203 = $result203->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row3['id_employees'];?></td>
            <td><?php echo $row30['nom']." ".$row30['prenom'];?></td>
            <td><?php echo $row203['nom_departement'];?></td>
            <td><?php echo $row30['fonction'];?></td>
            <td><?php echo $row3['rtn_electro'];?></td>
            <td><?php echo $row3['rtn_vehicule'];?></td>
            <td><?php echo $row3['rtn_admin'];?></td>
        </tr>
        <?php 
                }
            }
        }
        ?>
      </tbody>
    </table>

    
  </div>
  <br><br><br>
    <h1>Graphique des retenus des prets par employé</h1>
  <canvas id="myChart4"></canvas>
</div>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels1); ?>,
        datasets: [{
            label: "Heures d'absences/retards",
            data: <?php echo json_encode($data1); ?>,
            backgroundColor: '#007bff',
            borderColor: '#007bff',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            x: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.2)' // Couleur de la grille de l'axe x
                },
                ticks: {
                    color: 'rgba(255, 255, 255, 1)' // Couleur des étiquettes de l'axe x
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(255, 255, 255, 0.2)' // Couleur de la grille de l'axe y
                },
                ticks: {
                    color: 'rgba(255, 255, 255, 1)' // Couleur des étiquettes de l'axe y
                }
            }
        }
    }
});
</script>
<script>
  function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table1 = document.getElementById("table1");
    let table2 = document.getElementById("table2");
    let rows1 = table1.getElementsByTagName("tr");
    let rows2 = table2.getElementsByTagName("tr");

    for (let i = 1; i < rows1.length; i++) {
        let cells = rows1[i].getElementsByTagName("td");
        let match = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j].innerText.toLowerCase().includes(input)) {
                match = true;
                break;
            }
        }

        if (match) {
            rows1[i].style.display = "";
        } else {
            rows1[i].style.display = "none";
        }
    }

    for (let i = 1; i < rows2.length; i++) {
        let cells = rows2[i].getElementsByTagName("td");
        let match = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j].innerText.toLowerCase().includes(input)) {
                match = true;
                break;
            }
        }

        if (match) {
            rows2[i].style.display = "";
        } else {
            rows2[i].style.display = "none";
        }
    }
}
</script>
<script>
    function showTable(tableNumber) {
            // Masquer tous les tableaux
            document.getElementById('table11').style.display = 'none';
            document.getElementById('table12').style.display = 'none';
            document.getElementById('table13').style.display = 'none';
            
            
           
            // Afficher le tableau correspondant au bouton cliqué
            document.getElementById('table1' + tableNumber).style.display = 'block';
        }
        function convertHoursToDay(hoursElement) {
    let hoursText = hoursElement.innerText;
    let hours = parseFloat(hoursText.replace(" heures", ""));

    if (!isNaN(hours) && hours > 8) {
        let days = Math.floor(hours / 8);
        let remainingHours = hours % 8;
        hoursElement.innerText = `${days} jour(s) ${remainingHours} heure(s)`;
    }
}

// Appel de la fonction pour chaque élément
document.addEventListener("DOMContentLoaded", function() {
    let durationElements = document.querySelectorAll("[id^='duration-']");
    durationElements.forEach(function(element) {
        convertHoursToDay(element);
    });
});
function convertHoursToDay2(hoursElement) {
    let hoursText = hoursElement.innerText;
    let hours = parseFloat(hoursText.replace(" heures", ""));

    if (!isNaN(hours) && hours > 8) {
        let days = Math.floor(hours / 8);
        let remainingHours = hours % 8;
        hoursElement.innerText = `${days} jour(s) ${remainingHours} heure(s)`;
    }
}

// Appel de la fonction pour chaque élément
document.addEventListener("DOMContentLoaded", function() {
    let durationElements = document.querySelectorAll("[id^='duration1-']");
    durationElements.forEach(function(element) {
        convertHoursToDay2(element);
    });
});

function openPopup_i(employeeId2) {
    var popup = document.querySelector(`.popup_i1[data2-id="${employeeId2}"]`);
    popup.style.display = "block";
}

function closePopup_i(employeeId2) {
    var popup = document.querySelector(`.popup_i1[data2-id="${employeeId2}"]`);
    popup.style.display = "none";
}




function openPopup_i2(employeeId) {
  var popup2 = document.querySelector(`.popup_i12[data-id="${employeeId}"]`);
  popup2.style.display = "block";
}

function closePopup_i2(employeeId) {
  var popup2 = document.querySelector(`.popup_i12[data-id="${employeeId}"]`);
  popup2.style.display = "none";
}

function openPopupConfirmation_ss2(employeeId3) {
    var popup3 = document.querySelector(`.popup_c[data3-id="${employeeId3}"]`);
    popup3.style.display = "block";
}

function closePopup_7(employeeId3) {
    var popup3 = document.querySelector(`.popup_c[data3-id="${employeeId3}"]`);
    popup3.style.display = "none";
}

function openPopupConfirmation_ss22(employeeId4) {
    var popup4 = document.querySelector(`.popup_c4[data4-id="${employeeId4}"]`);
    popup4.style.display = "block";
}

function closePopup_72(employeeId4) {
    var popup4 = document.querySelector(`.popup_c4[data4-id="${employeeId4}"]`);
    popup4.style.display = "none";
}



</script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="superadmin.js"></script>
       <?php
        $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
       if (isset($_POST['in'])) {
        $id = $_POST['idp'];
    
        if ($id) {
            echo '<script>console.log('.$id.')</script>'; // Correction ici
    
            $name = $_POST['nom'];
            echo '<script>console.log('.$name.')</script>';

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 && $_FILES['photo']['size'] <= 1000000) {
                $photo = $_FILES['photo']['name'];
                $fileTmpName = $_FILES['photo']['tmp_name'];
                $fileSize = $_FILES['photo']['size'];
                $folder = "./img/";
    
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }
    
                $newImageName = uniqid();
                $newImageName .= "." . pathinfo($photo, PATHINFO_EXTENSION);
                $type= '1';
                move_uploaded_file($fileTmpName, $folder . $newImageName);
    
                $sql_check = "SELECT COUNT(*) FROM justificatif WHERE nom = :name";
                $stmt_check = $db->prepare($sql_check);
                $stmt_check->bindParam(':name', $name);
                $stmt_check->execute();
                $count = $stmt_check->fetchColumn();

                if ($count == '0') {
                    // L'élément n'existe pas, procéder à l'insertion
                    $sql7 = "INSERT INTO justificatif (nom, justificatif, d_employee, Type) VALUES (:name, :piece, :id, :type)";
                    $stmt7 = $db->prepare($sql7);
                    $stmt7->bindParam(':name', $name);
                    $stmt7->bindParam(':piece', $newImageName);
                    $stmt7->bindParam(':id', $id);
                    $stmt7->bindParam(':type', $type);
                    $stmt7->execute();
                    exit;
                    }
            }
        }
    }

    if (isset($_POST['in2'])) {
       echo"<script> document.getElementById(table11').style.display = 'none';
            document.getElementById('table12').style.display = 'block';</script>";
        $id2 = $_POST['idp'];
    
        if ($id2) {
            echo '<script>console.log('.$id2.')</script>'; // Correction ici
    
            $name2 = $_POST['nom'];
            echo '<script>console.log('.$name2.')</script>';

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 && $_FILES['photo']['size'] <= 1000000) {
                $photo = $_FILES['photo']['name'];
                $fileTmpName = $_FILES['photo']['tmp_name'];
                $fileSize = $_FILES['photo']['size'];
                $folder = "./img/";
    
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }
    
                $newImageName2 = uniqid();
                $newImageName2 .= "." . pathinfo($photo, PATHINFO_EXTENSION);
                $type= '0';
                move_uploaded_file($fileTmpName, $folder . $newImageName2);
    
                $sql_check2 = "SELECT COUNT(*) FROM justificatif WHERE nom = :name";
                $stmt_check2 = $db->prepare($sql_check2);
                $stmt_check2->bindParam(':name', $name2);
                $count2 = $stmt_check2->fetchColumn();

                if ($count2 == '0') {
                    // L'élément n'existe pas, procéder à l'insertion
                    $sql72 = "INSERT INTO justificatif (nom, justificatif, d_employee, Type) VALUES (:name, :piece, :id, :type)";
                    $stmt72 = $db->prepare($sql72);
                    $stmt72->bindParam(':name', $name2);
                    $stmt72->bindParam(':piece', $newImageName2);
                    $stmt72->bindParam(':id', $id2);
                    $stmt72->bindParam(':type', $type);
                    $stmt72->execute();
                    exit;
                    }
            }
        }
    }

    if(isset($_POST['confirmer_supp'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
    
        if($id) {
        
            $sql_delete = "DELETE FROM justificatif WHERE id = $id";
            if ($con->query($sql_delete) === TRUE) {
                            
            } else {
                echo "Erreur lors de la suppression de l'élément.";
            }
            
        } 
}  

if(isset($_POST['confirmer_supp2'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if($id) {
    
        $sql_delete = "DELETE FROM justificatif WHERE id = $id";
        if ($con->query($sql_delete) === TRUE) {
                        
        } else {
            echo "Erreur lors de la suppression de l'élément.";
        }
        
    } 
}  
       ?>

<script>
    var ctx4 = document.getElementById('myChart4').getContext('2d');
    var myChart4 = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: [<?php echo "'" . implode("', '", $labels) . "'"; ?>],
            datasets: [
                {
                    label: 'Électroménager',
                    data: [<?php echo implode(", ", $electromenager); ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Véhicule',
                    data: [<?php echo implode(", ", $vehicule); ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Administratif',
                    data: [<?php echo implode(", ", $administratif); ?>],
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            indexAxis: 'y', // Afficher les étiquettes sur l'axe des Y
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
        function logout() {
  Swal.fire({
    title: "Voulez-vous vous déconnecter ?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Oui, certainement !",
  }).then((result) => {
    if (result.isConfirmed) {
    // Appel AJAX pour déconnecter côté serveur
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "logout.php", true);
    xhr.send();

    // Redirection vers la page de connexion après déconnexion
    xhr.onload = function() {
        if (xhr.status == 200) {
            window.location.href = "login.php";
        } else {
            console.error("Une erreur s'est produite lors de la déconnexion.");
        }
    };
}
  });
}
    </script><script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="superadmin.js"></script>
</body>

</html>