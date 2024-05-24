<?php

session_start();
require_once('config.php');

$db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');

$conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");

$query4 = "SELECT * FROM demande_conge";
$result4 = mysqli_query($con, $query4);


$requete44 = "SELECT * FROM direction WHERE nom_direction = :nom_direction";
$state = $conn->prepare($requete44);
$state->bindParam(':nom_direction', $_SESSION['approbation']);
$state->execute();
$resultat44 = $state->fetchAll(PDO::FETCH_ASSOC);

if (!empty($resultat44)) {
    foreach ($resultat44 as $line44) {
        $id_receive = $line44['id_direction'];
        $sql = "SELECT * FROM notifications WHERE id_receive = ? ORDER BY is_read ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_receive]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



// get all notifications sorting by unread goes first
$requete444 = "SELECT * FROM direction WHERE nom_direction = :nom_direction";
$statement = $conn->prepare($requete444);
$statement->bindParam(':nom_direction', $_SESSION['approbation']); // Correction du nom du paramètre
$statement->execute();
$resultat444 = $statement->fetchAll(PDO::FETCH_ASSOC);
$total_unread_notifications = 0;

if (!empty($resultat444)) {
    foreach ($resultat444 as $line444) {
        $id_receive = $line444['id_direction'];
        $receiv_num = "SELECT COUNT(*) AS total_unread_notifications FROM notifications WHERE id_receive = ? AND is_read = 0";
        $statement = $conn->prepare($receiv_num);
        $statement->execute([$id_receive]);
        $row = $statement->fetch();
        $total_unread_notifications += $row['total_unread_notifications'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link rel="stylesheet" href="superadmin.css">
    <link rel="stylesheet" href="conge.css">
    <link rel="stylesheet" href="conge_depar.css">
    <link rel="stylesheet" href="notification.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <title>Gestion des conges</title>
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
            <div class="info--wrapper">
                <div class="main-title">
                <p class="font-weight-bold">Gestion des Congés</p>
                
            <div class="wrap" style="width:30%;">
            <div class="search">
                <input type="text"  id="searchInput" oninput="searchTable()" class="searchTerm" placeholder="Recherche....">
                <button type="submit" class="searchButton">
                 <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
        </div>
        <div class="button-section">
                <a onclick="openPopup()" class="button-34">notification <i class='bx bx-bell'></i> (<?php echo $total_unread_notifications; ?>)</a>
                <a href="employee.php" class="button-34">mon espace</a>
            </div>    
                </div>
                      
            </div> 
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
                   
            <style>
                

                .button-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    justify-content: center;
                    margin-left: 35%
                }


                .button-36 {
                    margin-right:30px;
                    background-image: linear-gradient(92.88deg, #455EB5 9.16%, #5643CC 43.89%, #673FD7 64.72%);
                    border-radius: 8px;
                    border-style: none;
                    box-sizing: border-box;
                    color: #FFFFFF;
                    cursor: pointer;
                    flex-shrink: 0;
                    font-family: "Inter UI","SF Pro Display",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Open Sans","Helvetica Neue",sans-serif;
                    font-size: 16px;
                    font-weight: 500;
                    height: 4rem;
                    padding: 0 1.6rem;
                    text-align: center;
                    text-shadow: rgba(0, 0, 0, 0.25) 0 3px 8px;
                    transition: all .5s;
                    user-select: none;
                    -webkit-user-select: none;
                    touch-action: manipulation;
                    }

                .button-36:hover {
                    box-shadow: rgba(80, 63, 205, 0.5) 0 1px 30px;
                    transition-duration: .1s;
                    }

                @media (min-width: 768px) {
                .button-36 {
                    padding: 0 2.6rem;
                }
                }
            </style>
            <div class="info--wrapper">
             
                
             
             <div class="info1">
             
<div id="table1" >            
    <?php
    while ($row4 = mysqli_fetch_assoc($result4)) {
        $query44 = "SELECT * FROM employees WHERE id_employees = " . $row4['d_employee'];
        $result44 = mysqli_query($con, $query44);
        if ($result44 && mysqli_num_rows($result44) > 0) {
            while ($row44 = mysqli_fetch_assoc($result44)) {
                $query444 = "SELECT * FROM departement WHERE id_departement = " . $row44['d_departement'];
                $result444 = mysqli_query($con, $query444);
                if ($result444 && mysqli_num_rows($result444) > 0) {
                    while ($row444 = mysqli_fetch_assoc($result444)) {
                        $query4444 = "SELECT * FROM direction WHERE id_direction = " . $row444['d_direction'];
                        $result4444 = mysqli_query($con, $query4444);
                        if ($result4444) {
                            while ($row4444 = mysqli_fetch_assoc($result4444)) {
                                if ($row4444['nom_direction'] == $_SESSION['approbation']) {
                                    if ($row4['type_conge'] == 'annuel detente' && $row4['val_departement'] == '1' && $row4['val_direction'] == '0') {
                                        ?>
                            <h2> Demandes des congés Annuels</h2>
                            <table class="table-container">
                                <thead>
                                    <tr>
                                        <th>ID_Employe</th>
                                        <th>Nom</th>
                                        <th>Prenom</th>
                                        <th>Nbr_jours</th>
                                        <th>Date_debut</th>
                                        <th>Date_fin</th>
                                        <th>Date_reprise</th>
                                        <th>Exercice</th>
                                        <th>validation</th>
                                        <th>Observation</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: white;">
                                    <tr>
                                        <td><?php echo $row44['id_employees']; ?></td>
                                        <td><?php echo $row44['nom']; ?></td>
                                        <td><?php echo $row44['prenom']; ?></td>
                                        <td><?php echo $row4['nbr_jours']; ?></td>
                                        <td><?php echo $row4['date_d']; ?></td>
                                        <td><?php echo $row4['date_f']; ?></td>
                                        <td><?php echo $row4['reprise']; ?></td>
                                        <td><?php echo $row4['exercice']; ?></td>
                                        <?php if ($row4['val_direction'] == "0") {
                                                ?>
                                                <form action="conge_direct.php" method="post"><td>
                                        
                                            
                                                    <input type="hidden" name="id" value="<?php echo $row4['id_demande']; ?>">
                                                    <input type="hidden" name="emp" value="<?php echo $row44['id_employees']; ?>">
                                                    <input type="hidden" name="type" value="<?php echo $row4['type_conge']; ?>">
                                                    <label for="eff">Valider ?</label>
                                                    <select name="eff" id="eff">
                                                        <option value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select></td>
                                                    <td>
                                                    <input type="text" name="observation">    
                                                    <input type="submit" name="submit" value="Envoyer"></td>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                        } else if ($row4['type_conge'] == 'recuperation' && $row4['val_direction'] == '0') {
                            ?>
                            <h2> Demandes des congés de recuperation</h2>
                            <table class="table-container">
                                <thead>
                                    <tr>
                                        <th>ID_Employe</th>
                                        <th>Nom</th>
                                        <th>Prenom</th>
                                        <th>Nbr_jours</th>
                                        <th>Date_debut</th>
                                        <th>Date_fin</th>
                                        <th>Date_reprise</th>
                                        <th>validation</th>
                                        <th>Observation</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: white;">
                                    <tr>
                                        <td><?php echo $row44['id_employees']; ?></td>
                                        <td><?php echo $row44['nom']; ?></td>
                                        <td><?php echo $row44['prenom']; ?></td>
                                        <td><?php echo $row4['nbr_jours']; ?></td>
                                        <td><?php echo $row4['date_d']; ?></td>
                                        <td><?php echo $row4['date_f']; ?></td>
                                        <td><?php echo $row4['reprise']; ?></td>
                                        <?php if ($row4['val_direction'] == "0") {
                                                ?>
                                                <form action="conge_direct.php" method="post"><td>
                                        
                                            
                                                    <input type="hidden" name="id" value="<?php echo $row4['id_demande']; ?>">
                                                    <input type="hidden" name="emp" value="<?php echo $row44['id_employees']; ?>">
                                                    <input type="hidden" name="type" value="<?php echo $row4['type_conge']; ?>">
                                                    <label for="eff">Valider ?</label>
                                                    <select name="eff" id="eff">
                                                        <option value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select></td>
                                                    <td>
                                                    <input type="text" name="observation">    
                                                    <input type="submit" name="submit" value="Envoyer"></td>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                               }else if ($row4['type_conge'] == 'exceptionel' && $row4['val_departement'] == '1' && $row4['val_direction'] == '0') {
                                ?>
                                <h2> Demandes des congés exceptionel</h2>
                                <table class="table-container">
                                    <thead>
                                        <tr>
                                            <th>ID_Employe</th>
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Nbr_jours</th>
                                            <th>Date_debut</th>
                                            <th>Date_fin</th>
                                            <th>Date_reprise</th>
                                            <th>Reclamation</th>
                                            <th>validation</th>
                                            <th>Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: white;">
                                        <tr>
                                            <td><?php echo $row44['id_employees']; ?></td>
                                            <td><?php echo $row44['nom']; ?></td>
                                            <td><?php echo $row44['prenom']; ?></td>
                                            <td><?php echo $row4['nbr_jours']; ?></td>
                                            <td><?php echo $row4['date_d']; ?></td>
                                            <td><?php echo $row4['date_f']; ?></td>
                                            <td><?php echo $row4['reprise']; ?></td>
                                            <td><?php echo $row4['reclamation']; ?></td>
                                            <?php if ($row4['val_direction'] == "0") {
                                                ?>
                                                <form action="conge_direct.php" method="post"><td>
                                        
                                            
                                                    <input type="hidden" name="id" value="<?php echo $row4['id_demande']; ?>">
                                                    <input type="hidden" name="emp" value="<?php echo $row44['id_employees']; ?>">
                                                    <input type="hidden" name="type" value="<?php echo $row4['type_conge']; ?>">
                                                    <label for="eff">Valider ?</label>
                                                    <select name="eff" id="eff">
                                                        <option value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select></td>
                                                    <td>
                                                    <input type="text" name="observation">    
                                                    <input type="submit" name="submit" value="Envoyer"></td>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                             }
                          }
                        }
                    }
                }
            }
        }
      }
    }
    ?>
    </div>
    <?php
if (isset($_POST['submit'])) {
    $selectedOption = $_POST['eff'];
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        if (isset($_POST['observation'])) {
            $obser = $_POST['observation'];
            // Proceed with your code that uses $obser
        } else {
            $obser = NULL;
        }
        

        // Si l'option sélectionnée est "oui"
        if ($selectedOption == 'oui') {
            $query = "UPDATE demande_conge SET val_direction = 1 WHERE id_demande = ?";
            $query1 = "UPDATE demande_conge SET observation = ? WHERE id_demande = ?";

            $stmt = $con->prepare($query);
            $stmt1 = $con->prepare($query1);

            if ($stmt && $stmt1) {
                $stmt->bind_param('i', $id);
                $stmt->execute();

                $stmt1->bind_param('si', $obser, $id);
                $stmt1->execute();
            }
        } elseif ($selectedOption == 'non') {
            $query = "UPDATE demande_conge SET val_direction = 2 WHERE id_demande = ?";
            $query1 = "UPDATE demande_conge SET observation = ? WHERE id_demande = ?";

            $stmt = $con->prepare($query);
            $stmt1 = $con->prepare($query1);

            if ($stmt && $stmt1) {
                $stmt->bind_param('i', $id);
                $stmt->execute();

                $stmt1->bind_param('si', $obser, $id);
                $stmt1->execute();
            }
        }

        if ($selectedOption == 'oui') {
            $requete4444 = "SELECT * FROM direction WHERE nom_direction = :nom_direction";
            $stmt4444 = $db->prepare($requete4444);
            $stmt4444->bindParam(':nom_direction', $_SESSION['approbation']);
            $stmt4444->execute();

            if ($stmt4444->rowCount() > 0) {
                while ($line4444 = $stmt4444->fetch(PDO::FETCH_ASSOC)) {
                    if (isset($_POST['emp'])) {
                        $emp= $_POST['emp'];
                        $id_receive = $_POST['emp'];
                        $id_send = $line4444['id_direction'];
                        

                        $current_datetime = get_current_datetime();
                        if (isset($_POST['type'])) {
                            $type = $_POST['type'];
                            $message = "Votre demande de conge " . $type . " est validee au niveau de votre direction";
                         
                            $check_query = "SELECT COUNT(*) as count FROM notifications WHERE id_send = :id_send AND id_receive = :id_receive AND message = :message AND created_at = :created_at";
                            $check_stmt = $db->prepare($check_query);
                            $check_stmt->bindParam(':id_send', $id_send);
                            $check_stmt->bindParam(':id_receive', $id_receive);
                            $check_stmt->bindParam(':message', $message);
                            $check_stmt->bindParam(':created_at', $current_datetime);
                            $check_stmt->execute();
                            $row = $check_stmt->fetch(PDO::FETCH_ASSOC);

                            if ($row['count'] == 0) {
                                // L'entrée n'est pas déjà présente, procéder à l'insertion
                                $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                                $not_stmt = $db->prepare($notif);
                                $not_stmt->bindParam(':id_send', $id_send);
                                $not_stmt->bindParam(':id_receive', $id_receive);
                                $not_stmt->bindParam(':message', $message);
                                $not_stmt->bindParam(':current_datetime', $current_datetime);
                                $not_stmt->execute();
                                
                            }

                            $role = "superadmin";
                            $requete = "SELECT * FROM role WHERE role = :role";
                            $stmt = $db->prepare($requete);
                            $stmt->bindParam(':role', $role);
                            $stmt->execute();
                            while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $id_receive2 = $line['id'];
                            $employee = "SELECT * FROM employees WHERE id_employees = :id";
                            $employee44 = $db->prepare($employee);
                            $employee44->bindParam(':id', $emp);
                            $employee44->execute();
                    
                            if ($employee44->rowCount() > 0) {
                                while ($line44 = $employee44->fetch(PDO::FETCH_ASSOC)) {
                            $message2 = "Vous avez recu une demande de conge " . $type . "  de direction " . $_SESSION['approbation'] . "de" .$line44['nom']." ".$line44['prenom'];
                            $check_query2 = "SELECT COUNT(*) as count FROM notifications WHERE id_send = :id_send AND id_receive = :id_receive AND message = :message AND created_at = :created_at";
                            $check_stmt2 = $db->prepare($check_query2);
                            $check_stmt2->bindParam(':id_send', $id_send);
                            $check_stmt2->bindParam(':id_receive', $id_receive2);
                            $check_stmt2->bindParam(':message', $message2);
                            $check_stmt2->bindParam(':created_at', $current_datetime);
                            $check_stmt2->execute();
                            $row2 = $check_stmt2->fetch(PDO::FETCH_ASSOC);
                            if ($row2['count'] == 0) {
                                // L'entrée n'est pas déjà présente, procéder à la deuxième insertion
                                $notif2 = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                                $not_stmt2 = $db->prepare($notif2);
                                $not_stmt2->bindParam(':id_send', $id_send);
                                $not_stmt2->bindParam(':id_receive', $id_receive2);
                                $not_stmt2->bindParam(':message', $message2);
                                $not_stmt2->bindParam(':current_datetime', $current_datetime);
                                $not_stmt2->execute();
                            } 
                                }
                            }
                            }
                            }
                        }
                    }
                }
            }if ($selectedOption == 'non') {
                $requete4444 = "SELECT * FROM direction WHERE nom_direction = :nom_direction";
                $stmt4444 = $db->prepare($requete4444);
                $stmt4444->bindParam(':nom_direction', $_SESSION['approbation']);
                $stmt4444->execute();
    
                if ($stmt4444->rowCount() > 0) {
                    while ($line4444 = $stmt4444->fetch(PDO::FETCH_ASSOC)) {
                        if (isset($_POST['emp'])) {
                            $id_receive = $_POST['emp'];
                            $id_send = $line4444['id_direction'];
                            
    
                            $current_datetime = get_current_datetime();
                            if (isset($_POST['type'])) {
                                $type = $_POST['type'];
                                $message = "Votre demande de conge " . $type . " est refusee au niveau de votre direction";
                             
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
    }


function get_current_datetime() {
    return date('Y-m-d H:i:s');
}
?>



<div id="table2" > 
                        <p>Etat des demandes :</p>
                        <table class="table-container" >
                            <thead>
                                <tr>
                                    <th>ID_Employe</th>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Type_demande</th>
                                    <th>Niveau</th>
                                    <th>Etat de traitement</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white;">
                            <?php
                        $stm = $db->prepare('SELECT * FROM demande_conge');
                        $stm->execute();
                        $r = $stm->fetchAll();
                        foreach ($r as $line4) {
                            $requete44 = "SELECT * FROM employees WHERE id_employees = " . $line4['d_employee'];
                            $resultat44 = mysqli_query($con, $requete44);
                            if ($resultat44 && mysqli_num_rows($resultat44) > 0) {
                                while ($line44 = mysqli_fetch_assoc($resultat44)) {
                                    $requete444 = "SELECT * FROM departement WHERE id_departement = " . $line44['d_departement'];
                                    $resultat444 = mysqli_query($con, $requete444);
                                    if ($resultat444 && mysqli_num_rows($resultat444) > 0) {
                                        while ($line444 = mysqli_fetch_assoc($resultat444)) {
                                            $query4444 = "SELECT * FROM direction WHERE id_direction = " . $line444['d_direction'];
                                            $result4444 = mysqli_query($con, $query4444);
                                            if ($result4444) {
                                                while ($row4444 = mysqli_fetch_assoc($result4444)) {
                                                    if ($row4444['nom_direction'] == $_SESSION['approbation']) {
                                                        
                                                if ( $line4['val_direction'] == '1' ) { 
                                                        ?>

                                            <tr>
                                                <td><?php echo $line44['id_employees']; ?></td>
                                                <td><?php echo $line44['nom']; ?></td>
                                                <td><?php echo $line44['prenom']; ?></td>
                                                <td><?php echo $line4['type_conge']; ?></td>
                                                <?php if ($line4['val_direction']== '1' && $line4['val1_rh']== '0') { ?>
                                                    <td>Ressources Humaines</td>
                                                    <td>En attente</td> 
                                                    <td style="background-color:#e6e8ed; border:none"><form method = "POST" action="conge_direct.php">
                                                        <input type="hidden" name="id" value="<?php echo $line4['id_demande']; ?>"/>
                                                        <button type="submit" name="modifier" style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                                                            <i class='bx bxs-edit'></i> 
                                                        </button>
                                                       </form>
                                                    </td>
                                                <?php } else if ($line4['val_direction'] == '2') { ?>
                                                    <td>Direction</td>
                                                    <td>Refuse</td>
                                                    <td style="background-color:#e6e8ed; border:none"><form method = "POST" action="conge_direct.php">
                                                        <input type="hidden" name="id" value="<?php echo $line4['id_demande']; ?>"/>
                                                        <button type="submit" name="modifier" style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                                                            <i class='bx bxs-edit'></i> 
                                                        </button>
                                                       </form>
                                                    </td> 
                                                <?php } else if ($line4['val1_rh'] == '2') { ?>
                                                    <td>Ressources Humaines</td>
                                                    <td>Refuse</td> 
                                                <?php } else if ( $line4['val1_rh'] == '1'  && $line4['val_direct_general'] == '0') { ?> 
                                                    <td>DG</td>
                                                    <td>En attente</td>  
                                                <?php } else if ( $line4['val1_rh'] == '1'  && $line4['val_direct_general'] == '2') { ?> 
                                                    <td>DG</td>
                                                    <td>Refuse</td>   
                                                
                                                <?php } else if ( $line4['val_direct_general'] == '1'  && $line4['val2_rh'] == '0') { ?> 
                                                    <td>Ressource Humaines</td>
                                                    <td>En attente</td>   
                                                
                                                <?php } else if ( $line4['val_direct_general'] == '1'  && $line4['val2_rh'] == '2') { ?> 
                                                    <td>Ressource Humaines</td>
                                                    <td>Refuse</td>   
                                                <?php } else if ( $line4['val_direct_general'] == '1'  && $line4['val2_rh'] == '1') { ?> 
                                                    <td>Ressource Humaines</td>
                                                    <td>Acceptee</td>   
                                                <?php } ?>  
                                            </tr>
                        <?php
                                            }
                                        
                                        }
                                        }
                                        }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
   </tbody>
</table>
                    </div>

            <style>
            p{
                color:#000;
            }
            .my-table {
                border-collapse: collapse;
                width: 100%;
                }

                .my-table th,
                .my-table td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
                }

                .my-table th {
                background-color: #000;
                color : #fff;
                }
                .my-table tbody{
                    background-color: #fff; 
                }

        </style>


                </div>
            </div>

                    <style>
                       .button-40 {
                        margin-top: 23px;
                        /* top: 16px; */
                        margin-bottom: 46px;
                        left: 900px;  
    background-color: #111827;
    border: 1px solid transparent;
    border-radius: .75rem;
    box-sizing: border-box;
    color: #FFFFFF;
    cursor: pointer;
    flex: 0 0 auto;
    font-family: "Inter var",ui-sans-serif,system-ui,-apple-system,system-ui,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    font-size: 1.125rem;
    font-weight: 600;
    line-height: 1.5rem;
    padding: .75rem 1.2rem;
    text-align: center;
    text-decoration: none #6B7280 solid;
    text-decoration-thickness: auto;
    transition-duration: .2s;
    transition-property: background-color,border-color,color,fill,stroke;
    transition-timing-function: cubic-bezier(.4, 0, 0.2, 1);
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    width: auto;
    }

    .button-40:hover {
    background-color: #374151;
    }

    .button-40:focus {
    box-shadow: none;
    outline: 2px solid transparent;
    outline-offset: 2px;
    }

    @media (min-width: 768px) {
    .button-40 {
        padding: .75rem 1.5rem;
    }
    }
                    </style>  
            
        </main>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="superadmin.js"></script>
        <script>
  function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("employeesTable");
    let rows = table.rows;

    for (let i = 1; i < rows.length; i++) {
      let cells = rows[i].cells;
      let match = false;

      for (let j = 0; j < cells.length - 1; j++) {
        if (cells[j].innerText.toLowerCase().includes(input)) {
          match = true;
          break;
        }
      }

      if (match) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
</script>
<div id="popup">
        <div class="container">
                <div class="notificationContainer" >
                    <header>
                        <button  onclick="closePopup()">fermer</button>
                        <div class="notificationHeader">
                            <h1>Notification</h1>
                            <span id="num-of-notif"><?php echo $total_unread_notifications;?></span>

                        </div>
                        <p id="mark-as-read">Marquer tout comme lu</p>
                    </header>
                    <main>
                    <?php if (!empty($notifications)) {
                    foreach ($notifications as $notification) {
                        ?>
                        <div class="notificationCard unread">
                            <div class="description">
                                <p >Notification:</p>
                                <p ><?php echo $notification['message']; ?></p>
                                <p id="notif-time"><?php echo $notification['created_at']; ?></p>
                                <?php if (!$notification['is_read']): ?>
                                    <form action="read-notification.php" method="post" onsubmit="return markAsRead();">
                                        <input type="hidden" name="id" value="<?php echo $notification['id']; ?>" />
                                        <input type="hidden" name="user_id" value="<?php echo $notification['id_receive']; ?>" />
                                        <button type="submit"  name="read" ><i class='bx bx-check-double'></i></button>
                                        </button>
                                    </form>
                                    <script>
                                        
                                        var unreadNotification = document.querySelector('.notificationCard.unread');
                                        if (unreadNotification) {
                                            if (<?php echo $notification['is_read']; ?> == 0) {
                                                unreadNotification.style.backgroundColor= 'rgba(42, 132, 213, 0.49)';
                                            } else if (<?php echo $notification['is_read']; ?> == 1) {
                                                unreadNotification.style.backgroundColor = 'rgb(20, 171, 227)';
                                            }
                                        }    
                                            
                                    </script>
                                <?php endif; ?>
                            </div>
                        </div><input type="hidden" id="total_unread_notifications" value="<?php echo $total_unread_notifications;?>" />
    <input type="hidden" id="user-id" value="<?php echo $notification['id_receive'];?>"/>
                        <?php
                    }
                }
            
        ?>
                        
                    </main>
                </div>
            </div>
            
</div>

    <input type="hidden" id="total_unread_notifications" value="<?php echo $total_unread_notifications;?>" />
    <input type="hidden" id="user-id" value="<?php if (!empty($notification)) { echo $notification['id_receive'];}?>"/>
<script>
  
    var totalUnreadNotifications = document.getElementById("total_unread_notifications").value;
    totalUnreadNotifications = parseInt(totalUnreadNotifications);

    showTitleBarNotifications(){
    var pattern = /^\(\d+\)/;

    if (totalUnreadNotifications == 0) {
        document.title = document.title.replace(pattern, "");
        return;
    }

    if (pattern.test(document.title)) {
        document.title = document.title.replace(pattern, "(" + totalUnreadNotifications + ")");
    } else {
        document.title = "(" + totalUnreadNotifications + ")" + document.title;
    }
}

</script>
<script>
   
    
    // when the read button is clicked
    function markAsRead() {
        // prevent the form from submitting
        event.preventDefault();
 
        // get the form node
        var form = event.target;
 
        // create AJAX object
        var ajax = new XMLHttpRequest();
 
        // set method and URL of request
        ajax.open("POST", "read-notification.php", true);
 
        // when the status of request changes
        ajax.onreadystatechange = function () {
 
            // when the response is received from server
            if (this.readyState == 4) {
 
                // if the response is successful
                if (this.status == 200) {
 
                    // convert the JSON string into Javascript object
                    var data = JSON.parse(this.responseText);
                    console.log(data);
 
                    // if there is no error
                    if (data.status == "success") {
 
                        // remove the 'read' button
                        form.remove();
 
                        // [emit read notification event here]
                        
                    }
                }
            }
        };
 
        // create form data object with the form
        var formData = new FormData(form);
 
        // send the AJAX request with the form data
        ajax.send(formData);
    }
    function openPopup() {
    const popup = document.getElementById("popup");
    popup.style.display = "block"; 
    
  
  }
function closePopup() {
    const popup = document.getElementById("popup");
    popup.style.display = "none";
  }
</script>

<style>
    .popup_v{
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

.popup_v p{
    margin-bottom: 10px;
}

.popup_v button{
    font-size: medium;
    background: #673fd7;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 10px;
}



</style>
<script>
function openPopupConfirmation_v() {
    document.getElementById('confirmation_s').style.display = 'block';
}



function closePopup_v() {
    document.getElementById('confirmation_s').style.display = 'none';
}

// Appeler la fonction openPopup pour afficher le popup
window.onload = function() {
    openPopupConfirmation_v();
    
};

</script>
<?php



if(isset($_POST['modifier'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    
    if($id) {
        // Afficher le popup de confirmation
        echo '<div id="confirmation_s" class="popup_v">
            <form id="confirmation-form" method="post" action="conge_direct.php">
                 <table style="background-color:#fff;">
                 <tr>
                <td style="background-color:#fff;"><input type="hidden" name="id" value="'.$id.'"></td>
                <td><label for="super"class="formbold-form-label">Nouvelle reponse :</label>
                <select name="validation" class="formbold-form-input" id="validation" required>
                    <option value="">Veuillez choisir :</option>
                    <option value="1">Oui</option>
                    <option value="2">Non</option>
                </select><br><br>
                <label for="observation" class="formbold-form-label">observation :</label>
                <input type="text" name="observation" class="formbold-form-input" id="validation" >
            
                      </td>
                </tr>
                </table>
                <button type="submit" name="modifierpns">Modifier</button>
                <button type="button" onclick="closePopup_v()">Annuler</button>
            </form>
        </div>';
    }

}

if(isset($_POST['modifierpns'])) {
    $id = $_POST['id'];
    $validation = $_POST['validation'];
    $observation = $_POST['observation'];
    
        
    if($validation != null){
        $sql2 = "UPDATE demande_conge SET  val_direction= ? where id_demande= ?";
        $stmt2 = $con->prepare($sql2);
        if (!$stmt2) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt2->bind_param("sd", $validation, $id);
         if (!$stmt2->execute()) {
         // Execute failed
         echo "Error: " . $stmt2->error;
         exit;
        } }
        if($observation != null){
            $sql8 = "UPDATE demande_conge SET observation = ? WHERE observation = ?";
            $stmt8 = $con->prepare($sql8);
            
            if (!$stmt8) {
                // Prepare failed
                echo "Error: " . $con->error;
                exit;
            }
            
            $stmt8->bind_param("ii", $observation, $id); 
            if (!$stmt8->execute()) {
                // Execute failed
                echo "Error: " . $stmt8->error;
                exit;
            }
        }
    

    }

?>
<script>
    function showTable(tableNumber) {
            // Masquer tous les tableaux
            document.getElementById('table1').style.display = 'none';
            document.getElementById('table2').style.display = 'none';
            document.getElementById('table3').style.display = 'none';
            document.getElementById('table4').style.display = 'none';
            
           
            // Afficher le tableau correspondant au bouton cliqué
            document.getElementById('table' + tableNumber).style.display = 'block';
        }
</script>
<style>
    /* Style for the popup */
    .notificationContainer {
      position: fixed;
    top: 0;
    right: 35%;
    width: 400px;
    height: 100%;
    background-color: #f5f5f5;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    
  }

  .notificationContainer header {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #ccc;
  }

  .notificationContainer header button {
    margin-bottom: 10px;
    color: #fff;
      font-family:inherit;
    font-size: inherit;
    background-color:rgba(42, 132, 213, 0.49);
    padding: 0.3rem 3.4rem;
    border: 3px solid #ffffff;
    box-shadow: 0 0 0 rgba(42, 132, 213, 0.49);
    transition: all 0.2s;
    border-radius: 0.5rem;
  }

  .notificationContainer .notificationHeader {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
  }

  .notificationContainer .notificationHeader h1 {
    font-size: 24px;
    font-weight: bold;
  }

  .notificationContainer .notificationHeader span {
    background-color: red;
    color: #fff;
    padding: 5px 10px;
    border-radius: 50%;
    font-size: 14px;
  }

  .notificationContainer main {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
  }

  .notificationCard {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
  }

  .notificationCard.unread {
    background-color: rgba(255, 0, 0, 0.39);
  }

  .notificationCard .description {
    display: flex;
    flex-direction: column;
  }

  .notificationCard .description p {
    margin-bottom: 10px;
  }

  .notificationCard .description .time {
    margin-top: 10px;
    font-size: 14px;
    color: #999;
  }

  .notificationCard .actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
  }

  .notificationCard .actions button {
    background-color: rgba(42, 132, 213, 0.49);
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .notificationCard .actions button:hover {
    background-color: rgba(42, 132, 213, 0.49);
  }

  .notificationCard .actions button i {
    margin-right: 5px;
  }

  .mark-as-read {
    background-color: rgba(42, 132, 213, 0.49);
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
  }

  .mark-as-read:hover {
    background-color: rgba(42, 132, 213, 0.49);
  }
  #num-of-notif{
      background-color: rgba(42, 132, 213, 0.49);
  }
  </style>
  
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
