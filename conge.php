<?php
session_start();
require_once('config.php');

$query1 = "SELECT * FROM conge_annuel";
$result1 = mysqli_query($con, $query1);


$query2 = "SELECT * FROM conge_requisition";
$result2 = mysqli_query($con, $query2);



$query3 = "SELECT * FROM conge_exept";
$result3 = mysqli_query($con, $query3);

$query4 = "SELECT * FROM demande_conge";
$result4 = mysqli_query($con, $query4);

$stmtp = "SELECT * FROM employees";
$resultp = mysqli_query($con, $query4);

$conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");
$db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');


        $id_receive = $_SESSION['id'];
        $sql = "SELECT * FROM notifications WHERE id_receive = ? ORDER BY is_read ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_receive]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
$conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");

// get all notifications sorting by unread goes first
$requete444 = "SELECT * FROM role WHERE approbation = :approbation";
$statement = $conn->prepare($requete444);
$statement->bindParam(':approbation', $_SESSION['approbation']);
$statement->execute();
$resultat444 = $statement->fetchAll(PDO::FETCH_ASSOC);

$total_unread_notifications = 0;

if (!empty($resultat444)) {
    foreach ($resultat444 as $line444) {
        $id_receive = $line444['id'];
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
                                <a href="compte.php" class="collapse__sublink">Roles</a>
                                <?php }?>
                                <a href="structure.php" class="collapse__sublink">Structure</a>
                                
                            </ul>
                        </div>
                    
                       <?php if($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin'){?>
                        <a href="conge.php" class="nav__link active">
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
                        <a href="absence.php" class="nav__link">
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
            <div style="margin-top:inherit;">
                <button class="button-34" role="button" onclick="openPopup_n()">notification <i class='bx bx-bell'></i> (<?php echo $total_unread_notifications; ?>)</button>
              

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
            </div>
            <div class="main-title">
                <div class="button-grid">
                    <button class="button-36" role="button" onclick="showTable(1)">Les congés annuels</button>
                    <button class="button-36" role="button" onclick="showTable(2)">Gestion des réquisitions</button>
                    <button class="button-36" role="button" onclick="showTable(3)">Les congés exceptionnels</button>
                    <button class="button-36" role="button" onclick="showTable(4)">Les Demandes recus</button>
                    <button class="button-36" role="button" onclick="showTable(5)">Validation finale</button>
                    <button class="button-36" role="button" onclick="showTable(6)">Etat des demandes</button>
                    <button class="button-36" role="button" onclick="showTable(7)">Titre de conge</button>
                    <button class="button-36" role="button" onclick="showTable(8)">Historique des demandes</button>
                    <button class="button-36" role="button" onclick="showTable(9)">Mon espace</button>
                </div>
            </div>
            <style>
                

                .button-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                    justify-content: center;
                    margin-left: 15%
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
        
        <div class="container" id="container">
            <style>
                .table-container {
                    margin-top: 20px;
                    
                }

                .table-container table {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .table-container thead{
                    background-color: #00154d;
                    color: #fff;
                }
                .table-container th, .table-container td {
                    border: 1px solid black;
                    padding: 8px;
                }
            </style>

                <div id="table1" class="table-container" style="display:none;">
                
                     <p style="color:#000;">Les reliquats des congés annuels :<?php if($_SESSION['role'] == 'superadmin'){?> <button style="margin-left:20px;" class="button-34" role="button" onclick="openPopup_a()">Ajouter<i class='bx bx-plus-medical'></i></button><?php }?></p>        
                    <!-- Tableau pour la gestion des congés annuels -->
                    <div class="wrap" style="margin-left:230px;">
                    <div class="search">
                        <input type="text"  id="searchInput" onkeyup="searchTable()" class="searchTerm" style="border: 3px solid #00154d;" placeholder="Recherche....">
                        <button type="submit" class="searchButton" style=" border: 1px solid #00154d; background: #00154d;">
                        <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </div>
                    <table id="table11">
                        <thead>
                            <tr>
                                <th>ID_Employé</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Département</th>
                                <th>Exercice</th>
                                <th>Nbr Jours autorisés</th>
                                <th>Nbr jrs consommés</th>
                                <th>Nbr jrs restants</th>
                                <?php if($_SESSION['role'] == 'superadmin'){?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                        </thead>
                        
                        <tbody style="background:#fff;">
                            <?php
                             while ($row1 = mysqli_fetch_assoc($result1)) {
                                $query11= "SELECT * FROM employees WHERE id_employees = " .$row1['d_employe'];
                                $result11 = mysqli_query($con, $query11);
                                if ($result11 && mysqli_num_rows($result11) > 0) {
                                    while ($row11 = mysqli_fetch_assoc($result11)) {
                                    $query111 = "SELECT * FROM departement WHERE id_departement = " .$row11['d_departement'];
                                    $result111 = mysqli_query($con, $query111);
                                    if ($result111 && mysqli_num_rows($result111) > 0) {
                                        while ($row111 = mysqli_fetch_assoc($result111)) {
                        ?>
                            <tr>
                                <td><?php echo $row11['id_employees'];?></td>
                                <td><?php echo $row11['nom'];?></td>
                                <td><?php echo $row11['prenom'];?></td>
                                <td><?php echo $row111['nom_departement'];?></td>
                                <td><?php echo $row1['exercice'];?></td>
                                <td><?php echo $row1['jrs_autoris'];?></td>
                                <td><?php echo $row1['jrs_consome'];?></td>
                                <td><?php echo $row1['jrs_restant'];?></td>
                                <?php if($_SESSION['role'] == 'superadmin'){?>
                                <td>
                                    <form action="conge.php" method="POST">
                                        <input type="hidden" id="id" name="id" value="<?php echo $row1['id']; ?>">
                                        <input type="hidden" id="nom" name="nom" value="<?php echo $row11['nom']."  ". $row11['prenom'];?>">
                                        <input type="hidden" id="exercice" name="exercice" value="<?php  echo $row1['exercice']; ?>">
                                        <input type="hidden" id="auto" name="auto" value="<?php echo $row1['jrs_autoris']; ?>">
                                        <input type="hidden" id="conso" name="conso" value="<?php  echo $row1['jrs_consome']; ?>">
                                        <input type="hidden" id="rest" name="rest" value="<?php echo $row1['jrs_restant']; ?>">
                                        <button type="submit" onclick="openPopupConfirmation()" name="modifier_1" style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                                            <i class='bx bxs-edit'></i> 
                                        </button>
                                        <button type="submit" name="supprimer_1"  style="background-color:red; color:#fff; border-radius:3px; font-size:xx-large">
                                            <ion-icon name="trash-outline"></ion-icon> 
                                        </button>
                                    </form>
                                </td>
                                <?php }?>
                            </tr>  
                        <?php
                             }
                            }
                        }
                   }
                }      
                    
                        ?>
                        </tbody>

                        
                    </table>
                </div>

                <div id="table2" class="table-container" style="display:none;">
                <?php if($_SESSION['role'] == 'superadmin'){?>
                        <button class="button-40" role="button" onclick="ajouterNouvelleRequisition()">Ajouter une nouvelle réquisition <i class='bx bx-plus-medical'></i></button>
                        <?php }?>
                        <button class="button-40" role="button" onclick="Table(22)">Liste des réquisitions </button>
                        
                        <div id="table22" class="table-container" style="display:none;"> 
                        <p style="color:#000;">Liste des requisitions</p>
                        <table>
                                <thead>
                                    <tr>
                                        <th>ID_Employé</th>
                                        <th>Nom</th>
                                        <th>Prenom</th>
                                        <th>Département</th>
                                        <th>Jour de requisition</th>
                                    </tr>
                                </thead>
                                <?php
                                        $q = "SELECT * FROM requisition";
                                        $r = mysqli_query($con, $q);
                                        if ($r && mysqli_num_rows($r) > 0) {
                                            while ($rw = mysqli_fetch_assoc($r)) {
                                            $q1 = "SELECT * FROM employees WHERE id_employees = " .$rw['d_employe'];
                                            $r1 = mysqli_query($con, $q1);
                                            if ($r1 && mysqli_num_rows($r1) > 0) {
                                                while ($rw1 = mysqli_fetch_assoc($r1)) {
                                                    $q2 = "SELECT * FROM departement WHERE id_departement = " .$rw1['d_departement'];
                                                    $r2 = mysqli_query($con, $q2);
                                                    if ($r2 && mysqli_num_rows($r2) > 0) {
                                                    while ($rw2 = mysqli_fetch_assoc($r2)) {
                                ?>
                                <tbody style="background-color: white;">
                                    
                                    <tr>
                                        <td><?php echo $rw1['id_employees'];?></td>
                                        <td><?php echo $rw1['nom'];?></td>
                                        <td><?php echo $rw1['prenom'];?></td>
                                        <td><?php echo $rw2['nom_departement'];?></td>
                                        <td><?php echo $rw['jour'];?></td>
                                    
                        
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
                                    </style><style>
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

                                    </tr>  
                            
                        <?php
                                    
                                }
                            }
                        }
                        }
                        }   
                    }
        
                
                
            
                                ?>
                        
                                </tbody>

                                
                            </table>
                            <br><br><br><br>

                </div>
                <script>
                    function ajouterNouvelleRequisition() {
                        window.location.href = 'requisition.php';
                    }
               </script>
<style>
    .button-40 {
    margin-bottom:30px;  
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
                     <!--  pour la gestion  -->
                     <p style="color:#000;">Les reliquats des conges de réquisition:<?php if($_SESSION['role'] == 'superadmin'){?><button style="margin-left:20px;" class="button-34" role="button" onclick="openPopup_b()">Ajouter<i class='bx bx-plus-medical'></i></button><?php }?></p>
                     <div class="wrap" style="margin-left:230px;">
                        <div class="search">
                            <input type="text"  id="searchInput2" onkeyup="searchTable2()" class="searchTerm" style="border: 3px solid #00154d;" placeholder="Recherche....">
                            <button type="submit" class="searchButton" style=" border: 1px solid #00154d; background: #00154d;">
                            <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </div>
                     </div>
                     <table id="table222">
                        <thead>
                            <tr>
                                <th>ID_Employé</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Departement</th>
                                <th>Nbr jours requisitionnés</th>
                                <th>Nbr jrs consommés</th>
                                <th>Nbr jrs restants</th>
                                <?php if($_SESSION['role'] == 'superadmin'){?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        <?php
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            $query22 = "SELECT * FROM employees WHERE id_employees = " .$row2['d_employee'];
                            $result22 = mysqli_query($con, $query22);
                            if ($result22 && mysqli_num_rows($result22) > 0) {
                                while ($row22 = mysqli_fetch_assoc($result22)) {
                                $query222 = "SELECT * FROM departement WHERE id_departement = " .$row22['d_departement'];
                                $result222 = mysqli_query($con, $query222);
                                if ($result222 && mysqli_num_rows($result222) > 0) {
                                    while ($row222 = mysqli_fetch_assoc($result222)) {
                                 
                       
                        ?>
                                    <tr>
                                        <td><?php echo $row22['id_employees']; ?></td>
                                        <td><?php echo $row22['nom']; ?></td>
                                        <td><?php echo $row22['prenom']; ?></td>
                                        <td><?php echo $row222['nom_departement']; ?></td>
                                        <td><?php echo $row2['jrs_requisition']; ?></td>
                                        <td><?php echo $row2['jrs_consom']; ?></td>
                                        <td><?php echo $row2['jrs_rest']; ?></td>
                                        <?php if($_SESSION['role'] == 'superadmin'){?>
                                        <td style="display:flex;">
                                            <button  onclick="openPopupConfirmation_2()" style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                                                <i class='bx bxs-edit'></i> 
                                            </button>
                                            <button  onclick="openPopupConfirmation_ss2()" style="background-color:red; color:#fff; border-radius:3px; font-size:xx-large">
                                                <ion-icon name="trash-outline"></ion-icon> 
                                            </button>
                                       
                                    <div id="confirmation_s2" class="popup_a" style="display:none;">
                                        <form id="confirmation" method="post" action="conge.php">
                                            <p>Modifier reliquat des requisition de <b>'<?php echo $row22['nom']." ".$row22['prenom']; ?>'</b></p>
                                            <div class="formbold-mb-5">
                                                <br><br>
                                                <label class="formbold-form-label">nombre de jours autorisés</label>
                                                <input class="formbold-form-input" type="number" id="jrs_auto" name="jrs_autoris" min="1" value="<?php echo $row2['jrs_requisition']; ?> " >

                                                <label class="formbold-form-label">nombre de jours consomés</label>
                                                <input class="formbold-form-input" type="number" id="jrs_consom" name="jrs_consom" min="1" value= "<?php echo $row2['jrs_consom']; ?>">

                                                <label class="formbold-form-label">nombre de jours restants</label>
                                                <input class="formbold-form-input" type="number" id="jrs_rest" name="jrs_rest" min="1" value="<?php echo $row2['jrs_rest']; ?>"><br><br>
                                                <input type="hidden" name="id" value="<?php echo $row2['id']; ?>">
                                                <div style="display:flex;">
                                                <button class="formbold-btn" type="submit" name="modifier_b">Modifier</button>
                                                <button class="formbold-btn" onclick="closePopup_2()" >Annuler</button>
                                                
                                            </div>
                                        </form>
                                    </div>
                                    
                                    </td>
                                    <?php }?>
                                    <div id="confirmation_s22" class="popup_c">
                                        <form id="confirmation" method="post" action="conge.php">
                                            <input type="hidden" name="id" value="<?php echo $row2['id'];?>">
                                            <p>Voulez vous vraiment supprimer cet element?</p>
                                            <button type="submit" name="confirmer_s22">Confirmer</button>
                                            <button type="button" onclick="closePopup_2()">Annuler</button>
                                        </form>
                                    </div>
                                    </tr>
                        <?php
                                 
                                 }
                                }
                            }
                       }
                    }   
                        ?>
                    </tbody>

                        
                    </table>
                </div>

                <div id="table3" class="table-container" style="display:none;">
                <p style="color:black;">Les congés exceptionnels:  </p>
                <div class="wrap" style="margin-left:230px;">
                        <div class="search">
                            <input type="text"  id="searchInput3" onkeyup="searchTable3()" class="searchTerm" style="border: 3px solid #00154d;" placeholder="Recherche....">
                            <button type="submit" class="searchButton" style=" border: 1px solid #00154d; background: #00154d;">
                            <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </div>
                     </div>
                    <!-- Tableau pour la gestion des congés exceptionnels -->
                    <table id="table33">
                        <thead>
                            <tr>
                                <th>ID_Employe</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Département</th>
                                <th>Nombre de jours </th>
                                <th>Délai</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        <?php
                              while ($row3 = mysqli_fetch_assoc($result3)) {
                                $query33 = "SELECT * FROM employees WHERE id_employees = " .$row3['d_emoloyee'];
                                $result33 = mysqli_query($con, $query33);
                                if ($result33 && mysqli_num_rows($result33) > 0) {
                                    while ($row33 = mysqli_fetch_assoc($result33)) {
                                    $query333 = "SELECT * FROM departement WHERE id_departement = " .$row33['d_departement'];
                                    $result333 = mysqli_query($con, $query333);
                                    if ($result333 && mysqli_num_rows($result333) > 0) {
                                        while ($row333 = mysqli_fetch_assoc($result333)) {
                        ?>
                            <tr>
                                <td><?php echo $row33['id_employees'];?></td>
                                <td><?php echo $row33['nom'];?></td>
                                <td><?php echo $row33['prenom'];?></td>
                                <td><?php echo $row333['nom_departement'];?></td>
                                <td><?php echo $row3['jrs_auto'];?></td>
                                <td><?php echo $row3['delai'];?></td>
                            </tr> 
                        <?php
                            }
                        }
                    }
               }
            }   
                        ?>
                        </tbody>
                </table>
                </div>



    <div id="table4" style="display:none;">
        <p>Demande recus</p>            
                <?php
                while ($row4 = mysqli_fetch_assoc($result4)) {
                    $query44 = "SELECT * FROM employees WHERE id_employees = " . $row4['d_employee'];
                    $result44 = mysqli_query($con, $query44);
                    if ($result44 && mysqli_num_rows($result44) > 0) {
                        while ($row44 = mysqli_fetch_assoc($result44)) {
                            $query444 = "SELECT * FROM departement WHERE id_departement = " . $row44['d_departement'];
                            $result444 = mysqli_query($con, $query444);
                            while ($row444 = mysqli_fetch_assoc($result444)) {
                                if ($row4['type_conge'] == 'annuel detente' && $row4['val_direction'] == '1' && $row4['val1_rh'] == '0') {
                    ?>
                    <h2>Demande de Congé Annuel</h2>
                    <table class="table-container" style="width:100%;" >
                        <thead>
                        <tr>
                            <th>ID_Employe</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Departement</th>
                            <th>Nbr_jours</th>
                            <th>Date_debut</th>
                            <th>Date_fin</th>
                            <th>Date_reprise</th>
                            <th>Exercice</th>
                            <?php if($_SESSION['role'] == 'superadmin'){?>
                            <th>Validation</th>
                            <th>Observation</th>
                            <?php }?>
                        </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        <tr>
                            <td><?php echo $row44['id_employees']; ?></td>
                            <td><?php echo $row44['nom']; ?></td>
                            <td><?php echo $row44['prenom']; ?></td>
                            <td><?php echo $row444['nom_departement']; ?></td>
                            <td><?php echo $row4['nbr_jours']; ?></td>
                            <td><?php echo $row4['date_d']; ?></td>
                            <td><?php echo $row4['date_f']; ?></td>
                            <td><?php echo $row4['reprise']; ?></td>
                            <td><?php echo $row4['exercice']; ?></td>
                            <?php if($_SESSION['role'] == 'superadmin'){?>
                            <form action="conge.php" method="post">
                                <td>
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
                                        <input type="submit" name="submit1" value="Envoyer">
                                    </td>
                            </form>
                            <?php }?>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                } else if ($row4['type_conge'] == 'recuperation' && $row4['val_direction'] == '1' && $row4['val1_rh'] == '0') { ?>
                    <h2>Demande de Congé de Récupération</h2>
                    <table class="table-container" style="width:100%;">
                        <thead>
                        <tr>
                            <th>ID_Employe</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Departement</th>
                            <th>Nbr_jours</th>
                            <th>Date_debut</th>
                            <th>Date_fin</th>
                            <th>Date_reprise</th>
                            <?php if($_SESSION['role'] == 'superadmin'){?>
                            <th>Validation</th>
                            <th>Observation</th>
                            <?php }?>
                        </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        <tr>
                            <td><?php echo $row44['id_employees']; ?></td>
                            <td><?php echo $row44['nom']; ?></td>
                            <td><?php echo $row44['prenom']; ?></td>
                            <td><?php echo $row444['nom_departement']; ?></td>
                            <td><?php echo $row4['nbr_jours']; ?></td>
                            <td><?php echo $row4['date_d']; ?></td>
                            <td><?php echo $row4['date_f']; ?></td>
                            <td><?php echo $row4['reprise']; ?></td>
                            <?php if($_SESSION['role'] == 'superadmin'){?>
                            <form action="conge.php" method="post">
                                <td>
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
                                        <input type="submit" name="submit1" value="Envoyer">
                                    </td>
                            </form>
                            <?php }?>
                        </tr>
                        </tbody>
                    </table>
                <?php
                }else if ($row4['type_conge'] == 'exceptionel' && $row4['val1_rh'] == '0') {
                    ?>
                    <h2> Demandes des congés exeptionnels</h2>
                    <table class="table-container" style="width:100%;">
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
                                <?php if($_SESSION['role'] == 'superadmin'){?>
                                <th>validation</th>
                                <th>Observation</th>
                                <?php }?>
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
                                <?php if($_SESSION['role'] == 'superadmin'){?>
                                <form action="conge.php" method="post">
                                <td>
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
                                        <input type="submit" name="submit1" value="Envoyer">
                                    </td>
                            </form>
                            <?php }?>
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

?>
<?php
if (isset($_POST['submit1'])) {
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
            $query = "UPDATE demande_conge SET val1_rh = 1 WHERE id_demande = ?";
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
            $query = "UPDATE demande_conge SET val1_rh = 2 WHERE id_demande = ?";
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
        if (isset($_POST['emp'])) {
            $id_receive = $_POST['emp'];
            $id_send = $_SESSION['id'];
            $emp = $_POST['emp'];
            
    
            $current_datetime = get_current_datetime();
            if (isset($_POST['type'])) {
                $type = $_POST['type'];
                $message = "Votre demande de conge " . $type . " est validee au niveau de DRH";
                $check_query = "SELECT COUNT(*) as count FROM notifications WHERE id_send = :id_send AND id_receive = :id_receive AND message = :message";
                $check_stmt = $db->prepare($check_query);
                $check_stmt->bindParam(':id_send', $id_send);
                $check_stmt->bindParam(':id_receive', $id_receive);
                $check_stmt->bindParam(':message', $message);
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
                $approbation = "dg";
                $requete = "SELECT * FROM role WHERE approbation = :approbation";
                $stmt = $db->prepare($requete);
                $stmt->bindParam(':approbation', $approbation);
                $stmt->execute();
                while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_receive2 = $line['id'];
                $employee = "SELECT * FROM employees WHERE id_employees = :emp";
                $employee44 = $db->prepare($employee);
                $employee44->bindParam(':emp', $emp);
                $employee44->execute();
                if ($employee44->rowCount() > 0) {
                    while ($line44 = $employee44->fetch(PDO::FETCH_ASSOC)) {
                    $message2 = "Vous avez recu une demande de conge " . $type . " de " .$line44['nom']." ".$line44['prenom'];
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
        }elseif ($selectedOption == 'non') {
            if (isset($_POST['emp'])) {
                $id_receive = $_POST['emp'];
                $id_send = $_SESSION['id'];
                

                $current_datetime = get_current_datetime();
                if (isset($_POST['type'])) {
                    $type = $_POST['type'];
                    $message2 = "Votre demande de conge " . $type . " est refusee";
                 
                    $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                    $not_stmt = $db->prepare($notif);
                    $not_stmt->bindParam(':id_send', $id_send);
                    $not_stmt->bindParam(':id_receive', $id_receive);
                    $not_stmt->bindParam(':message', $message2);
                    $not_stmt->bindParam(':current_datetime', $current_datetime);

                    $not_stmt->execute();
                }
            }
                }
                }
            }


    function get_current_datetime() {
      return date('Y-m-d H:i:s');
    }


?>
</div>


<div id="table5" style="display:none;">

<p id="title" >validation finale  de la demande :</p>
<table class="table-container" style="width:100%;" id="employeesTable">

    <thead>
        <tr>
            <th>ID_Employe</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Type_demande</th>
            <th>Departement</th>
            <th>Date Debut</th>
            <th>Date fin</th>
            <?php if($_SESSION['role'] == 'superadmin'){?>
            <th>Derniere Validation</th>
            <th>Observation</th>
            <?php }?>
        </tr>
    </thead>
    
    <?php
$db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
$stm = $db->prepare('SELECT * FROM demande_conge');
$stm->execute();
$r = $stm->fetchAll();
foreach ($r as $line4) {
    if ( $line4['val_direct_general'] == '1' && $line4['val2_rh'] == '0'  ) {
    $requete44 = "SELECT * FROM employees WHERE id_employees = " . $line4['d_employee'];
    $resultat44 = mysqli_query($con, $requete44);
    if ($resultat44 && mysqli_num_rows($resultat44) > 0) {
        while ($line44 = mysqli_fetch_assoc($resultat44)) {
            $requete444 = "SELECT * FROM departement WHERE id_departement = " . $line44['d_departement'];
            $resultat444 = mysqli_query($con, $requete444);
            while ($line444 = mysqli_fetch_assoc($resultat444)) {
                    
                       
?><tbody style="background-color: white;">
                    <tr>
                        <td><?php echo $line44['id_employees']; ?></td>
                        <td><?php echo $line44['nom']; ?></td>
                        <td><?php echo $line44['prenom']; ?></td>
                        <td><?php echo $line4['type_conge']; ?></td>
                        <td><?php echo $line444['nom_departement']; ?></td>
                        <td><?php echo $line4['date_d']; ?></td>
                        <td><?php echo $line4['date_f']; ?></td>
                        <?php if($_SESSION['role'] == 'superadmin'){?>
                        <form action="conge.php" method="post">
                        <td>
                             <input type="hidden" name="id" value="<?php echo $line4['id_demande']; ?>">
                             <input type="hidden" name="emp" value="<?php echo $line44['id_employees']; ?>">
                             <input type="hidden" name="type" value="<?php echo $line4['type_conge']; ?>">
                             <input type="hidden" name="delai" value="<?php echo $line4['date_d'].'/'.$line4['date_f']; ?>">
                             <label for="eff">Valider ?</label>
                                <select name="eff" id="eff">
                                 <option value="oui">Oui</option>
                                 <option value="non">Non</option>
                                </select>
                        </td>
                        <td><input type="text" name="observation">
                             <input type="submit" name="submit2" value="Envoyer"></td>
                             </form>
                             <?php }?>
                    </tr>
            </tbody><?php
                     }
                }
            }
        }
    }

?>

            </table>
                    


<?php
if (isset($_POST['submit2'])) {
    $selectedOption = $_POST['eff'];
    if (isset($_POST['id'])) {
        
        $id = $_POST['id'];
        $emp = $_POST['emp'];
        if (isset($_POST['observation'])) {
            $obser = $_POST['observation'];
            // Proceed with your code that uses $obser
        } else {
            $obser = NULL;
        }
        
        if ($selectedOption == 'oui') {
            $query = "UPDATE demande_conge SET val2_rh = 1 WHERE id_demande = ?";
            $query1 = "UPDATE demande_conge SET observation = ? WHERE id_demande = ?";

            
                            
            }
        
         elseif ($selectedOption == 'non') {
            $query = "UPDATE demande_conge SET val2_rh = 2 WHERE id_demande = ?";
            $query1 = "UPDATE demande_conge SET observation = ? WHERE id_demande = ?";
            
        }
    
        $stmt = $con->prepare($query);
        $stmt1 = $con->prepare($query1);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $query0 = "SELECT * FROM demande_conge WHERE id_demande = :id_demande";
            $stmt0 = $db->prepare($query0);
            if ($stmt0) {
                $stmt0->bindValue(':id_demande', $d_demande, PDO::PARAM_INT);
                $stmt0->execute();
                $result0 = $stmt0->fetch(PDO::FETCH_ASSOC);
                if ($result0) {
                    $etat = $result0['val2_rh'];
                    $type = $result0['type_conge'];
                    $nbr = $result0['nbr_jours'];
                    $exercice = $result0['exercice'];
                    $delai = $result0['date_d'] . "/" . $result0['date_f'];
                    $id_demande = $result0['id_demande'];
                    $c = $result0['calcul'];
                if ($etat == "1") {
                    if ($type == 'annuel detente') {
                        $requete10 = "SELECT COUNT(*) as count FROM conge_annuel WHERE d_employe = ?";
                        $stat0 = $con->prepare($requete10);
                        $stat0->bind_param('i', $emp);
                        $stat0->execute();
                        $resultat0 = $stat0->get_result();
                        $resultat0 = $resultat0->fetch_assoc();
                        if ($resultat0['count'] > 0) {
                            $requete20 = "SELECT COUNT(*) as count FROM conge_annuel WHERE exercice = ? AND d_employe =?";
                            $stat20 = $con->prepare($requete20);
                            $stat20->bind_param('ii', $exercice, $emp);
                            $stat20->execute();
                            $resultat20 = $stat20->get_result();
                            $resultat20 = $resultat20->fetch_assoc();
                            if ($resultat20['count'] > 0) {
                                $requete30 = "SELECT * FROM conge_annuel WHERE exercice = ? AND d_employe = ?";
                                $stat30 = $con->prepare($requete30);
                                $stat30->bind_param('ii', $exercice, $emp);
                                $stat30->execute();
                                $resultat30 = $stat30->get_result();
                                $resultat30 = $resultat30->fetch_assoc();
                                
                                    $jrs_rest0 = $resultat30['jrs_restant'] - $nbr;
                                    $jrs_consome0 = $resultat30['jrs_consome'] + $nbr;
                                    $requete40 = "UPDATE conge_annuel SET jrs_restant = ?, jrs_consome = ? WHERE d_employe = ? AND exercice = ?";
                                    $stat40 = $con->prepare($requete40);
                                    $stat40->bind_param('iiiii', $jrs_rest0, $jrs_consome0, $emp, $exercice);
                                    $stat40->execute();
                                    $requete401 = "UPDATE demande_conge SET calcul = ? WHERE id_demande = ? ";
                                    $stat401 = $con->prepare($requete401);
                                    $stat401->bind_param('ii', $calcul, $id_demande);
                                    $stat401->execute();
                                
                        
                    }}
                } else if ($type == 'recuperation') {
                    $requete10 = "SELECT COUNT(*) as count FROM conge_requisition WHERE d_employee = ?";
                    $stat0 = $con->prepare($requete10);
                    $stat0->bind_param('i', $emp);
                    $stat0->execute();
                    $resultat0 = $stat0->get_result();
                    $resultat0 = $resultat0->fetch_assoc();
                    if ($resultat0['count'] > 0) {
                        $requete20 = "SELECT * FROM conge_requisition WHERE d_employee = ?";
                        $stat20 = $con->prepare($requete20);
                        $stat20->bind_param('i', $emp);
                        $stat20->execute();
                        $resultat20 = $stat20->get_result();
                        $resultat20 = $resultat20->fetch_assoc();
                        
                            $jrs_rest0 = $resultat20['jrs_rest'] - $nbr;
                            $jrs_cons0 = $resultat20['jrs_consom'] + $nbr;
                            $calcul = '1';
                            $requete30 = "UPDATE conge_requisition SET jrs_rest = ?, jrs_consom = ? WHERE d_employee = ?";
                            $stat30 = $con->prepare($requete30);
                            $stat30->bind_param('iii', $jrs_rest0, $jrs_cons0, $emp);
                            $stat30->execute();
                            $requete331 = "UPDATE demande_conge SET calcul = ? WHERE id_demande = ? ";
                            $stat331 = $con->prepare($requete331);
                            $stat331->bind_param('ii', $calcul, $id_demande);
                            $stat331->execute();
                        
                    
                    }
                }
                else if ($type == 'exceptionel') {
                 if (isset($_POST['delai'])) {
                    $delai = $_POST['delai'];
                    $requete310 = "INSERT INTO conge_exept (d_emoloyee, jrs_auto, delai) VALUES (?, ?, ?)";
                    $stat310 = $con->prepare($requete310);
                    if ($stat310) {
                        $stat310->bind_param('iis', $emp, $nbr, $delai);
                        $stat310->execute();
                    } else {
                        echo "Erreur de préparation de la requête: " . $con->error;
                  }
                }
            }
            }
        }
      
        }
            $stm2 = $db->prepare('SELECT * FROM titre WHERE d_demande = :id');
                    $stm2 ->bindParam(':id', $id); 
                    $stm2->execute();
                    $r2 = $stm2->fetchAll();
                    foreach ($r2 as $line42) {?>
                        <div id="imprimer" class="popup_c" style="display:none;">
                            <div class="formbold-form-wrapper">
                                <div class="formbold-main-wrapper">
                                    <div class="formbold-form-wrapper">
                                        <button  onclick="closePopup_m()" style="margin-left: 380px; border-radius: 16px;border: none;background-color: red; font-size: large;color: #fff;"><i class='bx bx-x'></i></button> 
                                        <form action="traitement.php" method="post">
                                            <label class="formbold-form-label" for="nom">Nom:</label>
                                            <input class="formbold-form-input" type="text" id="nom" name="nom" value="<?php echo $line42["nom"]; ?>">
                                            
                                            <label class="formbold-form-label" for="fonction">Fonction:</label>
                                            <input class="formbold-form-input" type="text" id="fonction" name="fonction" value="<?php echo $line42["fonction"]; ?>">
                                            
                                            <label class="formbold-form-label" for="affectation">Affectation:</label>
                                            <input class="formbold-form-input" type="text" id="affectation" name="affectation" value="<?php echo $line42["affectation"]; ?>">
                                            
                                            <label class="formbold-form-label" for="type">Type de congé:</label>
                                            <input class="formbold-form-input" type="text" id="type" name="type" value="<?php echo $line42["type_conge"]; ?>">
                                            
                                            <label class="formbold-form-label" for="periode">Période:</label>
                                            <input class="formbold-form-input" type="text" id="periode" name="periode" value="<?php echo $line42["periode"];?> ">
                                            
                                            <label class="formbold-form-label" for="nbr">Nombre de jours:</label>
                                            <input class="formbold-form-input" type="text" id="nbr" name="nbr" value="<?php echo $line42["nbr_jours"];?> ">
                                            
                                            <label class="formbold-form-label" for="reprise">Reprise:</label>
                                            <input class="formbold-form-input" type="text" id="reprise" name="reprise" value="<?php echo $line42["reprise"];?> ">
                                    
                                            <button type="submit" title="imprimer" name="imprimer" style="background-color:#932d91;height:42px; color:#fff; border-radius:3px; font-size:large">imprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function closePopup_m(){
                                document.getElementById('imprimer').style.display = "none";
                            }
                        </script>
                        <?php
                    
                    }
            echo '<script>
                Swal.fire({
                    title: "Voulez vous imprimer ce titre de conge?",
                    showCancelButton: true,
                    confirmButtonText: "OUI",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        document.getElementById("table5").style.display = "block";
                        document.getElementById("imprimer").style.display = "block"; 
                    }
                });
            </script>';
        } else {
            echo "Erreur de préparation de la requête.";
        }
        if ($stmt1) {
            $stmt1->bind_param('si', $observation, $id);
            $stmt1->execute();
        } else {
            echo "Erreur de préparation de la requête.";
        }
        
    
              
                    if (isset($_POST['emp'])) {
                        $id_receive = $_POST['emp'];
                        $id_send = $_SESSION['id'];
                        

                        $current_datetime = get_current_datetime();
                        if (isset($_POST['type'])) {
                            $type = $_POST['type'];
                            $message = "Votre demande de conge " . $type . " est en attente de signature de titre de conge";
                         
                            $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                            $not_stmt = $db->prepare($notif);
                            $not_stmt->bindParam(':id_send', $id_send);
                            $not_stmt->bindParam(':id_receive', $id_receive);
                            $not_stmt->bindParam(':message', $message);
                            $not_stmt->bindParam(':current_datetime', $current_datetime);

                            $not_stmt->execute();
                        }
                        }
                        if ($selectedOption == 'non') {
                            if (isset($_POST['emp'])) {
                                $id_receive = $_POST['emp'];
                                $id_send = $_SESSION['id'];
                                
        
                                $current_datetime = get_current_datetime();
                                if (isset($_POST['type'])) {
                                    $type = $_POST['type'];
                                    $message2 = "Votre demande de conge " . $type . " est refusee";
                                 
                                    $notif = "INSERT INTO notifications (id_send, id_receive, message, created_at) VALUES (:id_send, :id_receive, :message, :current_datetime)";
                                    $not_stmt = $db->prepare($notif);
                                    $not_stmt->bindParam(':id_send', $id_send);
                                    $not_stmt->bindParam(':id_receive', $id_receive);
                                    $not_stmt->bindParam(':message', $message2);
                                    $not_stmt->bindParam(':current_datetime', $current_datetime);
        
                                    $not_stmt->execute();
                                }
                            }
                                }
                        }
                    

    }


?>

</div>

<div id="table6" style="display:none;">
<p>Etat des demandes :</p>
<div class="wrap" style="margin-left:230px;">
                        <div class="search">
                            <input type="text"  id="searchInput4" onkeyup="searchTable4()" class="searchTerm" style="border: 3px solid #00154d;" placeholder="Recherche....">
                            <button type="submit" class="searchButton" style=" border: 1px solid #00154d; background: #00154d;">
                            <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </div>
                     </div>
                        <table class="table-container" id="table44" style="width:100%;">
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
                                                        
                                                if ( $line4['val_direction'] == '1' ) { 
                                                        ?>

                                            <tr>
                                                <td><?php echo $line44['id_employees']; ?></td>
                                                <td><?php echo $line44['nom']; ?></td>
                                                <td><?php echo $line44['prenom']; ?></td>
                                                <td><?php echo $line4['type_conge']; ?></td>
                                                <?php if ($line4['val1_rh'] == '1' && $line4['val_direct_general'] == '0') { ?> 
                                                    <td>DG</td>
                                                    <td>En attente</td>
                                                    <?php if($_SESSION['role'] == 'superadmin'){?>
                                                    <td style="background-color:#e6e8ed; border:none"><form method = "POST" action="conge.php">
                                                       
                                                        <button onclick ="openPopupConfirmation_v() style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                                                            <i class='bx bxs-edit'></i> 
                                                        </button>
                                                        </form>
                                                    </td>  
                                                    <?php }?>
                                                    <div id="confirmation_v" class="popup_v" style= "display: none;">
                                                        <form id="confirmation-form" method="post" action="conge.php">
                                                           <input type="hidden" name="id" value="<?php echo $line4['id_demande']; ?>"/>
                                                        
                                                            <label for="validation" class="formbold-form-label">Nouvelle réponse :</label>
                                                            <select name="validation" class="formbold-form-input" id="validation" required>
                                                                <option value="">Veuillez choisir :</option>
                                                                <option value="1">Oui</option>
                                                                <option value="2">Non</option>
                                                            </select><br><br>
                                                            <label for="observation" class="formbold-form-label">observation :</label>
                                                            <input type="text" name="observation" class="formbold-form-input" id="validation" >
                                                            <button type="submit" name="modifierpns">Modifier</button>
                                                            <button type="button" onclick="closePopup_v()">Annuler</button>
                                                        </form>
                                                    </div>
                                                <?php }else if ( $line4['val1_rh'] == '2'){ ?> 
                                                    <td>Ressources Humaines</td>
                                                    <td>Refuse</td>
                                                    <td style="background-color:#e6e8ed; border:none"><form method = "POST" action="conge.php">
                                                        <input type="hidden" name="id" value="<?php echo $line4['id_demande']; ?>"/>
                                                        
                                                        <button type="submit" name="modifier_v" style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                                                            <i class='bx bxs-edit'></i> 
                                                        </button>
                                                        </form>
                                                    </td>   
                                                
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
            ?></tbody>
            </table>

            </div>


            <div id="table7" style="display:none;">
<p style="color:#000;">Titre de congés:<?php if($_SESSION['role'] == 'superadmin'){?> <button style="margin-left:20px;" class="button-34" role="button" onclick="openPopup()">Ajouter<i class='bx bx-plus-medical'></i></button><?php }?></p>
<div class="wrap" style="margin-left:230px;">
                        <div class="search">
                            <input type="text"  id="searchInput7" onkeyup="searchTable7()" class="searchTerm" style="border: 3px solid #00154d;" placeholder="Recherche....">
                            <button type="submit" class="searchButton" style=" border: 1px solid #00154d; background: #00154d;">
                            <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </div>
                     </div>
<table class="table-container" id="table77" style="width:100%;" >
  <thead>
    <tr>  
      <th>Nom et Prénom</th>
      <th>Fonction</th>
      <th>Affectation</th>
      <th>Type de congé</th>
      <th>Periode</th>
      <th>Nbr jours</th>
      <th>Date de reprise</th>
      <th>Piece jointe</th>
      <?php if($_SESSION['role'] == 'superadmin'){?>
      <th>Action</th>
      <?php }?>

    </tr>
  </thead>
  <tbody style="background-color:#fff;">
    <?php
    $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
    $stm = $db->prepare('SELECT * FROM demande_conge');
    $stm->execute();
    $r = $stm->fetchAll();
    
    foreach ($r as $line4) {
        if ($line4['val2_rh'] == '1') {
            $requete44 = "SELECT * FROM employees WHERE id_employees = " . $line4['d_employee'];
            $resultat44 = $db->query($requete44);
            if ($resultat44 && $resultat44->rowCount() > 0) {
                while ($line44 = $resultat44->fetch(PDO::FETCH_ASSOC)) {
                    $requete444 = "SELECT * FROM departement WHERE id_departement = " . $line44['d_departement'];
                    $resultat444 = $db->query($requete444);
                    while ($line444 = $resultat444->fetch(PDO::FETCH_ASSOC)) {
                        $d_employe = $line44['id_employees'];
                        $Nom = $line44['nom'] . " " . $line44['prenom'];
                        $Fonction = $line44['fonction'];
                        $Affectation = $line44['affectation'];
                        $type = $line4['type_conge'];
                        $periode = $line4['date_d'] . " / " . $line4['date_f'];
                        $nbr_jours = $line4['nbr_jours'];
                        $reprise = $line4['reprise'];
                        $d_demande = $line4['id_demande'];
    
                        $sql = "INSERT INTO titre (nom, fonction, affectation, type_conge, periode, nbr_jours, reprise, d_employe, d_demande)
                                SELECT '$Nom', '$Fonction', '$Affectation', '$type', '$periode', '$nbr_jours', '$reprise', '$d_employe', '$d_demande'
                                FROM dual
                                WHERE NOT EXISTS (
                                    SELECT 1
                                    FROM titre
                                    WHERE nom = '$Nom' AND fonction = '$Fonction' AND affectation = '$Affectation' AND type_conge = '$type' AND periode = '$periode' AND nbr_jours = '$nbr_jours' AND reprise = '$reprise' AND d_employe = '$d_employe' AND d_demande = '$d_demande'
                                )";
    
                        // Exécuter la requête
                        if ($db->query($sql) === TRUE) {
                        } 
                    }
                }
            }
        }
    }
    
    $stm2 = $db->prepare('SELECT * FROM titre');
    $stm2->execute();
    $r2 = $stm2->fetchAll();
    foreach ($r2 as $line42) {
        ?>
        <tr>
            <td><?php echo $line42['nom']; ?></td>
            <td><?php echo $line42['fonction']; ?></td>
            <td><?php echo $line42['affectation']; ?></td>
            <td><?php echo $line42['type_conge']; ?></td>
            <td><?php echo $line42['periode']; ?></td>
            <td><?php echo $line42['nbr_jours']; ?></td>
            <td><?php echo $line42['reprise']; ?></td>
            <td>
                <?php if($line42['piece'] != NULL): ?>
                    <a href="img/<?php echo $line42['piece']; ?>">Voir</a>
                <?php endif; ?>
            </td>
            <?php if($line42['piece'] == NULL && $_SESSION['role'] == 'superadmin'): ?>
            <td>
                <div style="display:flex;">
                    <form action="traitement.php" method="post">
                        <input type="hidden" name="nom" value="<?php echo $line42['nom']; ?>">
                        <input type="hidden" name="fonction" value="<?php echo $line42['fonction']; ?>">
                        <input type="hidden" name="affectation" value="<?php echo $line42['affectation']; ?>">
                        <input type="hidden" name="type" value="<?php echo $line42['type_conge']; ?>">
                        <input type="hidden" name="periode" value="<?php echo $line42['periode']; ?>">
                        <input type="hidden" name="nbr" value="<?php echo $line42['nbr_jours']; ?>">
                        <input type="hidden" name="reprise" value="<?php echo $line42['reprise']; ?>">
                        <button type="submit" title="imprimer" name="imprimer" style="background-color:#932d91; height:42px; color:#fff; border-radius:3px; font-size:xx-large">
                            <i class='bx bxs-printer'></i>
                        </button>
                    </form>
                    <button onclick="openPopupConfirmation_supp7()" style="background-color:red; color:#fff; border-radius:3px; font-size:xx-large">
                        <ion-icon name="trash-outline"></ion-icon> 
                    </button>
                    <div id="confirmation_supp7" class="popup_c" style="display:none;">
                        <form id="confirmation" method="post" action="conge.php">
                            <input type="hidden" name="id" value="<?php echo $line42['id']; ?>">
                            <p>Voulez-vous vraiment supprimer cet élément?</p>
                            <button type="submit" name="confirmer_supp">Confirmer</button>
                            <button type="button" onclick="closePopup_7()">Annuler</button>
                        </form>
                    </div>
                    <button onclick="openPopupConfirmation_i(<?php echo $line42['d_employe']; ?>)" title="insérer une pièce jointe" style="background-color:green; height:42px; color:#fff; border-radius:3px; font-size:xx-large">
                        <i class='bx bx-download'></i> 
                    </button>

                    <div id="inserer" class="popup_v" style="display:none;" data-id="<?php echo $line42['d_employe']; ?>">
                        <script>document.getElementById("table7").style.display="block";</script>
                        <form id="confirmation-form" method="post" action="conge.php" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="idp" value="<?php echo $line42['id']; ?>">
                            <input type="hidden" name="emp" value="<?php echo $line42['d_employe']; ?>">
                            <input type="hidden" name="d_demande" value="<?php echo $line42['d_demande']; ?>">
                            <label for="validation" class="formbold-form-label">Insérer une pièce jointe :</label>
                            <input type="file" id="photo" name="photo">
                            <button type="submit" name="in">Insérer</button>
                            <button type="button" onclick="closePopup_i()">Annuler</button>
                        </form>
                    </div>

                </div>
            </td>
            <?php endif; ?>
        </tr>
    <?php
                }?>
  </tbody>
</table>
</div>

<div id="table8" style="display:none;">
<p style="color:#000;">Historique des demandes:<?php if($_SESSION['role'] == 'superadmin'){?> <button class='button-34' style="margin-left:20px;" role="button" onclick="openPopup_c()" >Ajouter<i class='bx bx-plus-medical'></i></button><?php }?></p>

<div class="wrap" style="margin-left:230px;">
                        <div class="search">
                            <input type="text"  id="searchInput8" onkeyup="searchTable8()" class="searchTerm" style="border: 3px solid #00154d;" placeholder="Recherche....">
                            <button type="submit" class="searchButton" style=" border: 1px solid #00154d; background: #00154d;">
                            <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </div>
                     </div>
<table class="table-container" id='table88' style="width:100%;" >
  <thead>
    <tr>
      <th>ID_Employe</th>  
      <th>Nom et Prénom</th>
      <th>Type de congé</th>
      <th>Délai</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody style="background-color:white;">
    <?php
    $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
    $stm = $db->prepare('SELECT * FROM demande_conge');
    $stm->execute();
    $r = $stm->fetchAll();
    foreach ($r as $line4) {
        if ( $line4['val_direction'] == '1'  ) {
        $requete44 = "SELECT * FROM employees WHERE id_employees = " . $line4['d_employee'];
        $resultat44 = mysqli_query($con, $requete44);
        if ($resultat44 && mysqli_num_rows($resultat44) > 0) {
            while ($line44 = mysqli_fetch_assoc($resultat44)) {
                $requete444 = "SELECT * FROM departement WHERE id_departement = " . $line44['d_departement'];
                $resultat444 = mysqli_query($con, $requete444);
                while ($line444 = mysqli_fetch_assoc($resultat444)) {
    ?>
    <tr>
        <td><?php echo $line44['id_employees']; ?></td>
        <td><?php echo $line44['nom']." ".$line44['prenom']; ?></td>
        <td><?php echo $line4['type_conge']; ?></td>
        <td><?php echo $line4['date_d']." - ".$line4['date_f']; ?></td>
        <td><?php if($line4['val1_rh'] == '2' || $line4['val2_rh'] == '2'){ echo 'Non Validée'; } else if($line4['val1_rh'] == '1' || $line4['val2_rh'] == '1'){ echo 'Validée'; } ?></td>
    </tr>
    <?php
                    }
                }
            }
        }
    }
    ?>
  </tbody>
</table>

</div>
        </div>
       
       <div id="table9" style="display:none;">
       <br><br>
       <p >Mes reliquats:</p>
            <table class="my-table">
                
                <thead>
                    <tr>
                    <th>Jours restants</th>
                    <th>Motif</th>
                    <th>Faire votre demande</th>
                    </tr>
                </thead>
                
                
                <tbody style="">
                    <?php
                $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
                $stm = $db->prepare('SELECT * FROM conge_annuel Where d_employe = :id');
                $stm->bindParam(':id', $_SESSION['id']);
                $stm->execute();
                $r = $stm->fetchAll();
                foreach ($r as $line4) {
                     ?>
                    <tr>
                    <td><?php echo $line4['jrs_restant']; ?></td>
                    <td>Annuel detente (<?php echo $line4['exercice']; ?>)</td>
                    <td>
                    <form action="formulaire.php" method="post">
                        <input type="hidden" name="exerce" value="<?php echo $line4['exercice']; ?>">
                        <button type="submit" name="envoie" class="button-34" >
                           Effectuer ma demande <i class='bx bx-plus-medical'></i>
                        </button>
                    </form>
                    </td>
                    </tr>
                    <tr>
                        <?php } ?>
                <?php $requete44 = $db->prepare('SELECT * FROM conge_requisition Where d_employee = :id'); 
                     $requete44->bindParam(':id', $_SESSION['id']); 
                     $requete44->execute(); 
                     $r44 = $requete44->fetchAll(); 
                     foreach ($r44 as $line44) { ?> 
                     <tr>
                    <td><?php echo $line44['jrs_rest']; ?></td>
                    <td>Recuperation</td>
                    <td><a href= "formulaire.php" class="button-34" role="button">Effectuer ma demande <i class='bx bx-plus-medical'></i></a></td>
                    </tr>
                    <tr>
                        <?php } ?>       
                </tbody>
                
            </table> <br><br>

            
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
     </main>

     
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="superadmin.js"></script>
  
<div id="popup" class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
            <button  onclick="closePopup_t()" style="margin-left: 380px; border-radius: 16px;border: none;background-color: red; font-size: large;color: #fff;"><i class='bx bx-x'></i></button> 
              <form action="conge.php" method="POST">
                <h1>Ajouter un titre de conge:</h1><br><br>
                <div class="formbold-mb-5">
                    
                <label for="name" class="formbold-form-label">Employe :</label>
                <input style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput" oninput="searchSelect(event)" placeholder="Rechercher son nom..">
                <div id="searchResults" style="display:none;"></div>
                <div style="display:flex;">
                <input type="text" name="responsable1" id="responsable" class="formbold-form-input" readonly required >
                </div>
                <select  id="responsable" name="responsable" style="display:none;">
                <?php $query4 = "SELECT * FROM employees";
                    $resultp = mysqli_query($con, $query4);

                    if ($resultp) {
                        while ($rowp = mysqli_fetch_assoc($resultp)) {
                            ?>
                            <option value="<?php echo $rowp['id_employees'] ; ?>"><?php echo $rowp['nom'] . " " . $rowp['prenom']; ?></option>
                            <?php
                        }
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                </select>
                <br><br>
                
                </div>
                <div class="formbold-mb-5">
                    <label for="type" class="formbold-form-label">Type de conge :</label>
                    <select  name="type" id="type" class="formbold-form-input"  style="width: 367px; font-size: large; border-color: #cbcbcb; height: 60px">
                     <option value="annuel detente">Annuel detente </option>
                     <option value="recuperation">Recuperation</option>
                     <option value="exeption">Execeptionnel</option>
                    </select>
                </div>
                
                <div class="flex flex-wrap formbold--mx-3">
                  <div class="w-full sm:w-half formbold-px-3">
                    <div class="formbold-mb-5 w-full">
                      <label for="date" class="formbold-form-label"> Date de depart :</label>
                      <input
                        type="date" name="date" id="date" class="formbold-form-input" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa"/>
                    </div>
                  </div>
                  <div class="w-full sm:w-half formbold-px-3">
                    <div class="formbold-mb-5">
                      <label for="date_f" class="formbold-form-label"> Date de fin: </label>
                      <input type="date" name="date_f" id="date_f" class="formbold-form-input" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa"/>
                    </div>
                  </div>
                </div>
                
                      <label for="date_r" class="formbold-form-label"> Date de reprise: </label>
                      <input type="date" name="date_r" id="date_r" class="formbold-form-input" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa"/>
                    <br><br>
                    <label for="nbr" class="formbold-form-label"> Nombre de jours: </label>
                      <input type="number" name="nbr" id="nbr" class="formbold-form-input" oninput="preventNumber30(event)" />
                    <br><br>
                 
                <div style="display:flex;">
                  <button class="formbold-btn" type="submit" name="ajout">Ajouter</button>
                </div>
              </form>
            </div>
          </div>
          
  </div>
</div>
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
</script>
<style>
           
            .formbold-mb-5 {
              margin-bottom: 20px;
            }
            .formbold-pt-3 {
              padding-top: 12px;
            }
            .formbold-main-wrapper {
              display: flex;
              align-items: center;
              justify-content: center;
              padding: 48px;
            }
          
            .formbold-form-wrapper {
              margin: 0 auto;
              max-width: 550px;
              width: 100%;
              background: white;
            }
            .formbold-form-label {
              display: block;
              font-weight: 500;
              font-size: 16px;
              color: #07074d;
              margin-bottom: 12px;
            }
            
          
            .formbold-form-input {
              width: 100%;
              padding: 12px 24px;
              border-radius: 6px;
              border: 1px solid #e0e0e0;
              background: white;
              font-weight: 500;
              font-size: 16px;
              color: #6b7280;
              outline: none;
              resize: none;
            }
            .formbold-form-input:focus {
              border-color: #6a64f1;
              box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
            }
          
            .formbold-btn {
                text-align: center;
                font-size: 15px;
                border-radius: 6px;
                padding: 11px 34px;
                border: none;
                font-weight: 600;
                background-color: #6a64f1;
                color: white;
                cursor: pointer;
                margin-right: 30px;
            }
            .formbold-btn:hover {
              box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
            }
          
            .formbold--mx-3 {
              margin-left: -12px;
              margin-right: -12px;
            }
            .formbold-px-3 {
              padding-left: 12px;
              padding-right: 12px;
            }
            .flex {
              display: flex;
            }
            .flex-wrap {
              flex-wrap: wrap;
            }
            .w-full {
              width: 100%;
            }
            @media (min-width: 540px) {
              .sm\:w-half {
                width: 50%;
              }
            }
          </style>
<style>
  /* Style for the popup */
  #popup, #popup_a, #popup_b, #popup_c , .popup_a {
    display: none;
    position: absolute;
    left: 50%;
    top: 500px;
    transform: translate(-50%, -50%);
    z-index: 999;
  }
</style>
<style>
    .info--wrapper p {
    font-size: 35px;
    text-decoration: underline;
    color: #090081;
}
</style>

<script>
  // Function to open the popup
  function openPopup() {
    const popup = document.getElementById("popup");
    popup.style.display = "block";
  }

  function openPopup_a() {
    const popup = document.getElementById("popup_a");
    popup.style.display = "block";
  }

  function openPopup_b() {
    const popup = document.getElementById("popup_b");
    document.getElementById("table2").style.display="block";
    popup.style.display = "block";
  }

  function openPopup_c() {
    const popup = document.getElementById("popup_c");
    popup.style.display = "block";
  }

  // Function to close the popup
  function closePopup() {
    const popup = document.getElementById("popup");
    popup.style.display = "none";
    window.location.replace("conge.php");
  }


  function closePopup_a() {
    const popup = document.getElementById("popup_a");
    popup.style.display = "none";
  document.getElementById('table1').style.display = 'block';
  }

  function closePopup_b() {
    const popup = document.getElementById("popup_b");
    popup.style.display = "none";
    document.getElementById("table2").style.display="block";
  
  }

  function closePopup_c() {
    const popup = document.getElementById("popup_c");
    popup.style.display = "none";
    document.getElementById("table8").style.display="block";
  
  }

  
</script>
<?php

$annee_en_cours = date('Y');
$exercice_en_cours = $annee_en_cours . '/' . ($annee_en_cours + 1);
$exercice_passe = ($annee_en_cours-1) . '/' . $annee_en_cours;
$passe = ($annee_en_cours-2) . '/' . ($annee_en_cours-1);

?>
<div id="popup_a" class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper"> 
                 <button  onclick="closePopup_a()" style="margin-left: 450px; border-radius: 16px;border: none;background-color: red; font-size: large;color: #fff;"><i class='bx bx-x'></i></button> 
              <form action="conge.php" method="POST">
                
                <h1>Ajouter un reliquat annuel:  </h1><br><br>
                <div class="formbold-mb-5">
                <label for="exercice" class="formbold-form-label" value="">Exercice :</label>
                <select name="exercice" style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px">
                    <option value="<?php echo $exercice_en_cours?>"><?php echo $exercice_en_cours?></option>
                    <option value="<?php echo $exercice_passe?>"><?php echo $exercice_passe?></option>
                    <option value="<?php echo $passe?>"><?php echo $passe?></option>
                </select><br><br>
                <label for="name" class="formbold-form-label">Employe :</label>
                <input style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput_a" oninput="searchSelect_a(event)" placeholder="Rechercher son nom..">
                <div id="searchResults_a" style="display:none;"></div>
                <div style="display:flex;">
                <input type="text" name="responsable1" id="responsable_a" class="formbold-form-input" readonly required >
                </div>
                <select  id="responsable_select" name="responsable" style="display:none;">
                <?php $query41 = "SELECT * FROM employees";
                $resultp1 = mysqli_query($con, $query41);

                if ($resultp1) {
                    while ($rowp1 = mysqli_fetch_assoc($resultp1)) {
                        ?>
                        <option value="<?php echo $rowp1['id_employees']; ?>"><?php echo $rowp1['nom'] . " " . $rowp1['prenom']; ?></option>
                        <?php
                    }
                } else {
                    echo "Error: " . mysqli_error($con);
                }
                ?>
                </select>
                <br><br>
                
                </div>
                
                <label class="formbold-form-label">nombre de jours autorisés</label>
                <input class="formbold-form-input" type="number" id="jrs_auto" name="jrs_autoris" min="1"  oninput="preventNumber30(event)">

                <label class="formbold-form-label">nombre de jours consomés</label>
                <input class="formbold-form-input" type="number" id="jrs_consom" name="jrs_consom" min="1" oninput="preventNumber30(event)">

                <label class="formbold-form-label">nombre de jours restants</label>
                <input class="formbold-form-input" type="number" id="jrs_rest" name="jrs_rest" min="1" oninput="preventNumber30(event)"><br><br>
          
                <div style="display:flex;">
                  <button class="formbold-btn" type="submit" name="ajout_a">Ajouter</button>
               
                </div>
              </form>
            </div>
          </div>
  </div>
</div>



<div id="popup_b" class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
            <button  onclick="closePopup_b()" style="margin-left: 450px; border-radius: 16px;border: none;background-color: red; font-size: large;color: #fff;"><i class='bx bx-x'></i></button> 
              <form action="conge.php" method="POST">
                <h1>Ajouter un reliquat de recuperation:</h1><br><br>
                <div class="formbold-mb-5">
                <label for="name" class="formbold-form-label">Employe :</label>
                <input style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput_b" oninput="searchSelect_b(event)" placeholder="Rechercher son nom..">
                <div id="searchResults_b" style="display:none;"></div>
                <div style="display:flex;">
                <input type="text" name="responsableb" id="responsable_b" class="formbold-form-input" readonly required >
                </div>
                <select  id="responsable_b" name="responsable" style="display:none;">
                <?php $query42 = "SELECT * FROM employees";
                    $resultp2 = mysqli_query($con, $query42);

                    if ($resultp2) {
                        while ($rowp2 = mysqli_fetch_assoc($resultp2)) {
                            ?>
                            <option value="<?php echo $rowp2['id_employees']; ?>"><?php echo $rowp2['nom'] . " " . $rowp2['prenom']; ?></option>
                            <?php
                        }
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                
                </select>
                <br><br>
                
                
                <label class="formbold-form-label">nombre de jours réquisitionés</label>
                <input class="formbold-form-input" type="number" id="jrs_auto" name="jrs_auto" min="1" oninput="preventNumber30(event)">

                <label class="formbold-form-label">nombre de jours consomés</label>
                <input class="formbold-form-input" type="number" id="jrs_rest" name="jrs_rest" min="1" oninput="preventNumber30(event)">

                <label class="formbold-form-label">nombre de jours restants</label>
                <input class="formbold-form-input" type="number" id="jrs_rest" name="jrs_rest" min="1" oninput="preventNumber30(event)"><br><br>
          
                <div style="display:flex;">
                  <button class="formbold-btn" type="submit" name="ajout_b">Ajouter</button>
                  <button class="formbold-btn" onclick="closePopup_b()">Annuler</button>
                </div>
              </form>
            </div>
          </div>
  </div>
</div>
</div>

<div id="popup_c" class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">

        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
            <button  onclick="closePopup_c()" style="margin-left: 443px; border-radius: 16px;border: none;background-color: red; font-size: large;color: #fff;"><i class='bx bx-x'></i></button> 
              <form action="ajout-conge.php" method="POST">
                <h1>Ajouter une demande:</h1><br><br>
                <div class="formbold-mb-5">
                <label for="name" class="formbold-form-label">Employe :</label>
                <input style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px" type="text" id="searchInput_c" oninput="searchSelect_c(event)" placeholder="Rechercher son nom..">
                <div id="searchResults_c" style="display:none;"></div>
                <div style="display:flex;">
                <input type="text" name="responsablec" id="responsable_c" class="formbold-form-input" readonly required >
                </div>
                <select  id="responsable_c" name="responsable" style="display:none;">
                <?php $query43 = "SELECT * FROM employees";
        $resultp3 = mysqli_query($con, $query43);

        if ($resultp3) {
            while ($rowp3 = mysqli_fetch_assoc($resultp3)) {
                ?>
                <option value="<?php echo $rowp3['id_employees']; ?>"><?php echo $rowp3['nom'] . " " . $rowp3['prenom']; ?></option>
                <?php
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
        ?>
                
                </select>
                
                <div class="formbold-mb-5">
                    <label for="type" class="formbold-form-label">Type de conge :</label>
                    <select  name="type" id="type" class="formbold-form-input" >
                     <option value="annuel detente">Annuel detente </option>
                     <option value="recuperation">Recuperation</option>
                     <option value="exeption">Execeptionnel</option>
                    </select>
                </div><br><br>
                
                <div class="flex flex-wrap formbold--mx-3">
                  <div class="w-full sm:w-half formbold-px-3">
                    <div class="formbold-mb-5 w-full">
                      <label for="date" class="formbold-form-label"> Date de depart </label>
                      <input
                        type="date" name="date" id="date" class="formbold-form-input" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa"/>
                    </div>
                  </div>
                  <div class="w-full sm:w-half formbold-px-3">
                    <div class="formbold-mb-5">
                      <label for="date_f" class="formbold-form-label"> Date de fin: </label>
                      <input type="date" name="date_f" id="date_f" class="formbold-form-input" lang="fr" pattern="\d{2}/\d{2}/\d{4}" title="Veuillez saisir une date au format jj/mm/aaaa"/>
                    </div>
                  </div>
                </div>
                

                <label class="formbold-form-label">Status</label>
                <select   id="statu" name="statu" class="formbold-form-input">
                     <option value="1">Acceptee </option>
                     <option value="2">Refuse</option>
                     
                    </select><br><br>
          
                <div style="display:flex;">
                  <button class="formbold-btn" type="submit" name="ajout_c">Ajouter</button>
                </div>
              </form>
            </div>
          </div>
    </div>
  </div>
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

<script>
  function searchSelect_a(event) {
  document.getElementById('searchResults_a').style.display = 'block';
  const searchTerm = event.target.value.toLowerCase();
  const options = document.querySelectorAll('#responsable option');
  const searchResults = document.getElementById('searchResults_a');

  searchResults.innerHTML = '';
  options.forEach(option => {
    if (option.textContent.toLowerCase().includes(searchTerm)) {
      const result = document.createElement('div');
      result.textContent = option.textContent;
      result.addEventListener('click', () => {
        const select = document.getElementById('responsable_a');
        select.value = option.value;
        searchResults.innerHTML = '';
      });
      searchResults.appendChild(result);
    }
  });
}
</script>

<script>
  function searchSelect_c(event) {
  document.getElementById('searchResults_c').style.display = 'block';
  const searchTerm = event.target.value.toLowerCase();
  const options = document.querySelectorAll('#responsable option');
  const searchResults = document.getElementById('searchResults_c');

  searchResults.innerHTML = '';
  options.forEach(option => {
    if (option.textContent.toLowerCase().includes(searchTerm)) {
      const result = document.createElement('div');
      result.textContent = option.textContent;
      result.addEventListener('click', () => {
        const select = document.getElementById('responsable_c');
        select.value = option.value;
        searchResults.innerHTML = '';
      });
      searchResults.appendChild(result);
    }
  });
}
</script>

<script>
  function searchSelect_b(event) {
  document.getElementById('searchResults_b').style.display = 'block';
  const searchTerm = event.target.value.toLowerCase();
  const options = document.querySelectorAll('#responsable option');
  const searchResults = document.getElementById('searchResults_b');

  searchResults.innerHTML = '';
  options.forEach(option => {
    if (option.textContent.toLowerCase().includes(searchTerm)) {
      const result = document.createElement('div');
      result.textContent = option.textContent;
      result.addEventListener('click', () => {
        const select = document.getElementById('responsable_b');
        select.value = option.value;
        searchResults.innerHTML = '';
      });
      searchResults.appendChild(result);
    }
  });
}
</script>

<script>
  function searchSelect_a(event) {
  document.getElementById('searchResults_a').style.display = 'block';
  const searchTerm = event.target.value.toLowerCase();
  const options = document.querySelectorAll('#responsable option');
  const searchResults = document.getElementById('searchResults_a');

  searchResults.innerHTML = '';
  options.forEach(option => {
    if (option.textContent.toLowerCase().includes(searchTerm)) {
      const result = document.createElement('div');
      result.textContent = option.textContent;
      result.addEventListener('click', () => {
        const select = document.getElementById('responsable_a');
        select.value = option.value;
        searchResults.innerHTML = '';
      });
      searchResults.appendChild(result);
    }
  });
}
</script>

<script>

        function showTable(tableNumber) {
            // Masquer tous les tableaux
            document.getElementById('table1').style.display = 'none';
            document.getElementById('table2').style.display = 'none';
            document.getElementById('table3').style.display = 'none';
            document.getElementById('table4').style.display = 'none';
            document.getElementById('table5').style.display = 'none';
            document.getElementById('table6').style.display = 'none';
            document.getElementById('table7').style.display = 'none';
            document.getElementById('table8').style.display = 'none';
            document.getElementById('table9').style.display = 'none';
            
           
            // Afficher le tableau correspondant au bouton cliqué
            document.getElementById('table' + tableNumber).style.display = 'block';
        }
        function Table(number) {
            document.getElementById('table22').style.display = 'none';
            document.getElementById('table' + number).style.display = 'block';
        }

        function checkTableEmpty() {
        var table = document.getElementById("employeesTable");
        var rowCount = table.rows.length;
        document.getElementById('title1').style.display = 'none';
        
        if (rowCount <= 1) {
            table.style.display = "none";
            document.getElementById('table1').style.display = 'block';
        } else {
            table.style.display = "";
        }
        }
       


        // Call the function when the page loads
        window.onload = checkTableEmpty;
        window.onload = showTable;
</script>

<div id="popup_n">
<div class="container">
                <div class="notificationContainer" >
                    <header>
                        <button  onclick="closePopup_n()">fermer</button>
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
                <p>Notification:</p>
                <p><?php echo $notification['message']; ?></p>
                <p id="notif-time"><?php echo $notification['created_at']; ?></p>
                <?php if (!$notification['is_read']): ?>
                    <form action="read-notification.php" method="post" onsubmit="return markAsRead();">
                        <input type="hidden" name="id" value="<?php echo $notification['id']; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $notification['id_receive']; ?>" />
                        <button type="submit"  name="read" ><i class='bx bx-check-double'></i></button>
                    </form>
                    <script>
                        function color(){
                        var unreadNotification = document.querySelector('.notificationCard.unread');
                        if (unreadNotification) {
                            unreadNotification.style.backgroundColor = 'rgb(215, 255, 196)';
                        }
                    }
                    window.onload = color();
                    </script>
                <?php endif; ?>
            </div>
        </div>
        

                        <input type="hidden" id="total_unread_notifications" value="<?php echo $total_unread_notifications;?>" />
    <input type="hidden" id="user-id" value="<?php echo $notification['id_receive'];?>"/>
                        <?php
                    }
                }
            
        ?>
                        
                    </main>
                </div>
            </div>
            

</div>



    
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
    function openPopup_n() {
    const popup = document.getElementById("popup_n");
    popup.style.display = "block"; 
    
  
  }
function closePopup_n() {
    const popup = document.getElementById("popup_n");
    popup.style.display = "none";
  }

  function closePopup_t() {
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
        document.getElementById('confirmation_v').style.display = 'block';
    }



    function closePopup_v() {
        document.getElementById('confirmation_v').style.display = 'none';
    }

    // Appeler la fonction openPopup pour afficher le popup
    window.onload = function() {
        openPopupConfirmation_v();
        
    };

</script>
<?php

       

        if(isset($_POST['modifierpns'])) {
            $id = $_POST['id'];
            
            $validation = $_POST['validation'];
            $observation = $_POST['observation'];
        echo '<script>console.log('.$validation.')</script>';
        if($validation != null){
            $sql2 = "UPDATE demande_conge SET val1_rh = ? WHERE id_demande = ?";
            $stmt2 = $con->prepare($sql2);
            
            if (!$stmt2) {
                // Prepare failed
                echo "Error: " . $con->error;
                exit;
            }
            
            $stmt2->bind_param("ii", $validation, $id); // Assuming 'val1_rh' and 'id' are integers, adjust if needed
            
            if (!$stmt2->execute()) {
                // Execute failed
                echo "Error: " . $stmt2->error;
                exit;
            }
        }
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



        if (isset($_POST['ajout_a'])) {
            $d_employe = $_POST['responsable1'];
            $exercice = $_POST['exercice'];
            $jrs_autoris = $_POST['jrs_autoris'];
            $jrs_consom = $_POST['jrs_consom'];
            $jrs_rest = $_POST['jrs_rest'];

            $query44 = "SELECT * FROM conge_annuel WHERE d_employe = :d_employe AND exercice = :exercice";
            $stmt44 = $conn->prepare($query44);
            $stmt44->bindParam(':d_employe', $d_employe);
            $stmt44->bindParam(':exercice', $exercice);
            $stmt44->execute();
            $result44 = $stmt44->fetchAll();
            
            if (count($result44) > 0) {
                echo '<script>
                document.getElementById("table1").style.display = "block";
                document.getElementById("table7").style.display = "none";
                    Swal.fire({
                        title: "Cette employe a deja un reliquat dans cet exercice",
                        icon: "warning",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                        
                        }
                    });
                
                    
                </script>';
            } else {
                $sql3 = "INSERT INTO conge_annuel (d_employe, exercice, jrs_autoris, jrs_restant, jrs_consome) 
                SELECT :d_employe, :exercice, :jrs_autoris, :jrs_rest, :jrs_consom 
                FROM dual 
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM conge_annuel
                    WHERE d_employe = :d_employe
                    AND exercice = :exercice
                    AND jrs_autoris = :jrs_autoris
                    AND jrs_restant = :jrs_rest
                    AND jrs_consome = :jrs_consom
                )";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bindParam(':d_employe', $d_employe);
                $stmt3->bindParam(':exercice', $exercice);
                $stmt3->bindParam(':jrs_autoris', $jrs_autoris);
                $stmt3->bindParam(':jrs_rest', $jrs_rest);
                $stmt3->bindParam(':jrs_consom', $jrs_consom);
                $stmt3->execute();

                echo"<script>document.getElementById('table1').style.display = 'block';
                document.getElementById('table1').style.display = 'none';</script>";
                exit;
            }
        }

        if (isset($_POST['ajout_b'])) {
             echo '<script>document.getElementById("table2").style.display="block";</script>';
            $d_employee = $_POST['responsableb'];
            $jrs_requisition = $_POST['jrs_auto'];
            $jrs_rest = $_POST['jrs_rest'];
            $jrs_consom = $_POST['jrs_consom'];
            
            $query44 = "SELECT * FROM conge_requisition WHERE d_employee = :d_employee";
            $stmt44 = $conn->prepare($query44);
            $stmt44->bindParam(':d_employee', $d_employee);
            $stmt44->execute();
            $result44 = $stmt44->fetch(PDO::FETCH_ASSOC); // Utilisez fetch() pour obtenir une seule ligne
            
            if ($result44) {
                $jrs_requisition += $result44['jrs_requisition'];
                $jrs_rest += $result44['jrs_rest'];
                $jrs_consom += $result44['jrs_consom'];
            
                $sql2 = "UPDATE conge_requisition SET jrs_requisition = :jrs_requisition, jrs_rest = :jrs_rest, jrs_consom = :jrs_consom WHERE d_employee = :d_employee";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bindParam(':jrs_requisition', $jrs_requisition);
                $stmt2->bindParam(':jrs_rest', $jrs_rest);
                $stmt2->bindParam(':jrs_consom', $jrs_consom);
                $stmt2->bindParam(':d_employee', $d_employee);
                $stmt2->execute();
            }else{
                    // Préparation et exécution de la requête d'insertion
                    $sql2 = "INSERT INTO conge_requisition (d_employee, jrs_requisition, jrs_consom, jrs_rest) 
                    SELECT :d_employee,  :jrs_requisition, :jrs_rest, :jrs_consom 
                    FROM dual 
                    WHERE NOT EXISTS (
                        SELECT 1 
                        FROM conge_requisition 
                        WHERE d_employee = :d_employee 
                        AND jrs_requisition = :jrs_requisition
                        AND jrs_rest= :jrs_rest 
                        AND jrs_consom = :jrs_consom
                    )";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bindParam(':d_employee', $d_employee);
                    $stmt2->bindParam(':jrs_requisition', $jrs_requisition);
                    $stmt2->bindParam(':jrs_rest', $jrs_rest);
                    $stmt2->bindParam(':jrs_consom', $jrs_consom);
                    $stmt2->execute();
                    // $reponse = $requete->fetchAll(PDO::FETCH_ASSOC);
                    }
                exit;
            
            }
                        
            if (isset($_POST['ajout'])) {
                echo '<script>document.getElementById("table7").style.display="block";</script>';
                $type = $_POST['type'];
                $date_d = $_POST['date'];
                $date_f = $_POST['date_f'];
                $periode = $date_d ."/". $date_f;
                $reprise = $_POST['date_r'];
                $nbr = $_POST['nbr'];   
                $d_employee = $_POST['responsable1'];
                $requete44 = "SELECT * FROM employees WHERE id_employees = " . $d_employee;
                        $resultat44 = $db->query($requete44);
                        if ($resultat44 && $resultat44->rowCount() > 0) {
                            while ($line44 = $resultat44->fetch(PDO::FETCH_ASSOC)) {
                                $Nom = $line44['nom'] . " " . $line44['prenom'];
                                $Fonction = $line44['fonction'];
                                $Affectation = $line44['affectation'];
                                $sql = "INSERT INTO titre (nom, fonction, affectation, type_conge, periode, nbr_jours, reprise, d_employe)
                                            SELECT '$Nom', '$Fonction', '$Affectation', '$type', '$periode', '$nbr', '$reprise', '$d_employee'
                                            FROM dual
                                            WHERE NOT EXISTS (
                                                SELECT 1
                                                FROM titre
                                                WHERE nom = '$Nom' AND fonction = '$Fonction' AND affectation = '$Affectation' AND type_conge = '$type' AND periode = '$periode' AND nbr_jours = '$nbr' AND reprise = '$reprise' AND d_employe = '$d_employee'
                                            )";
                
                                    // Exécuter la requête
                                    if ($db->query($sql) === TRUE) {
                                    } 
                            }
                
                
                    exit;}
                        }

?>
<!--fonction de recherche des tableaux-->
<script>
    function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("table11");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let match = false;

        for (let j = 0; j < cells.length; j++) {
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

    function searchTable2() {
        let input = document.getElementById("searchInput2").value.toLowerCase();
        let table = document.getElementById("table222");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < cells.length; j++) {
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

    function searchTable3() {
        let input = document.getElementById("searchInput3").value.toLowerCase();
        let table = document.getElementById("table22");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < cells.length; j++) {
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
    function searchTable4() {
        let input = document.getElementById("searchInput4").value.toLowerCase();
        let table = document.getElementById("table44");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < cells.length; j++) {
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
    function searchTable7() {
        let input = document.getElementById("searchInput7").value.toLowerCase();
        let table = document.getElementById("table77");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < cells.length; j++) {
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
    function searchTable8() {
        let input = document.getElementById("searchInput8").value.toLowerCase();
        let table = document.getElementById("table88");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < cells.length; j++) {
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
<!--style des popup-->
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
<?php
        if(isset($_POST['modifier_1'])) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $exercice = $_POST['exercice'];
            $auto = $_POST['auto'];
            $rest = $_POST['rest'];
            $conso = $_POST['conso'];
            $nom = $_POST['nom'];
            if($id) {
                // Afficher le popup de confirmation
                echo '
                <script>document.getElementById("table1").style.display="block";</script>
                <div id="confirmation_s" class="popup_a" style="display:none;">
                <form id="confirmation" method="post" action="conge.php">
                <p><b>Modifier reliquat conge annuel de '.$nom.'</b></p>
                <div class="formbold-mb-5">
                    <label for="exercice" class="formbold-form-label" value="">Exercice :</label>
                    <select name="exercice" style="width: 100%; font-size: large; border-color: #cbcbcb; height: 40px">
                        <option value="'. $exercice_en_cours.'">'. $exercice_en_cours.'</option>
                        <option value="'. $exercice_passe.'">'. $exercice_passe.'</option>
                        <option value="'.$passe.'">'. $passe.'</option>
                    </select><br><br>
                <label class="formbold-form-label">nombre de jours autorisés</label>
                <input class="formbold-form-input" type="number" id="jrs_auto" name="jrs_autoris" min="1" value="'.$auto.'" >

                <label class="formbold-form-label">nombre de jours consomés</label>
                <input class="formbold-form-input" type="number" id="jrs_consom" name="jrs_consom" min="1" value= "'.$conso.'">

                <label class="formbold-form-label">nombre de jours restants</label>
                <input class="formbold-form-input" type="number" id="jrs_rest" name="jrs_rest" min="1 value="'.$rest.'""><br><br>
                <input type="hidden" name="id" value="'.$id.'">
                <div style="display:flex;">
                <button class="formbold-btn" type="submit" name="modifier_a">Modifier</button>
                <button class="formbold-btn" onclick="closePopup()" >Annuler</button>
            
                </div>>
                </form>
            </div>';
            }

        }

        if(isset($_POST['modifier_a'])) {
            $id = $_POST['id'];
            $exercice = $_POST['exercice'];
            $jrs_rest = $_POST['jrs_rest'];
            $jrs_consom = $_POST['jrs_consom'];
            $jrs_autoris = $_POST['jrs_auto'];
            
            echo '<script>document.getElementById("table1").style.display="block";</script>';    
            if($exercice != null){
                $sql2 = "UPDATE conge_annuel SET  exercice= ? where id= ?";
                $stmt2 = $con->prepare($sql2);
                if (!$stmt2) {
                // Prepare failed
                echo "Error: " . $conn->error;
                exit;
                }
                $stmt2->bind_param("sd", $exercice, $id);
                if (!$stmt2->execute()) {
                // Execute failed
                echo "Error: " . $stmt2->error;
                exit;
                } }

                if ($jrs_autoris != null) {
                    $sql3 = "UPDATE conge_annuel SET jrs_autoris= ? WHERE id= ?";
                    $stmt3 = $con->prepare($sql3);
                    if (!$stmt3) {
                        // Prepare failed
                        echo "Error: " . $con->error;
                        exit;
                    }
                    // Convert d_depa
                    $stmt3->bind_param("ii", $jrs_autoris, $id);
                    if (!$stmt3->execute()) {
                        // Execute failed
                        echo "Error: " . $stmt3->error;
                        exit;
                    }
                }

                if ($jrs_rest != null) {
                    $sql4 = "UPDATE conge_annuel SET jrs_restant= ? WHERE id= ?";
                    $stmt4 = $con->prepare($sql4);
                    if (!$stmt4) {
                        // Prepare failed
                        echo "Error: " . $con->error;
                        exit;
                    }
                    // Convert d_depa
                    $stmt4->bind_param("ii", $jrs_rest, $id);
                    if (!$stmt4->execute()) {
                        // Execute failed
                        echo "Error: " . $stmt4->error;
                        exit;
                    }
                }

                if ($jrs_consom != null) {
                    $sql5 = "UPDATE conge_annuel SET jrs_consome= ? WHERE id= ?";
                    $stmt5 = $con->prepare($sql5);
                    if (!$stmt5) {
                        // Prepare failed
                        echo "Error: " . $con->error;
                        exit;
                    }
                    // Convert d_depa
                    $stmt5->bind_param("ii", $jrs_consom, $id);
                    if (!$stmt5->execute()) {
                        // Execute failed
                        echo "Error: " . $stmt5->error;
                        exit;
                    }
                }
                
            
            

            }


        if(isset($_POST['supprimer_1'])) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;

            
            
                
            if($id) {
                    
                echo '<div id="confirmation_s" class="popup_c">
                <script>document.getElementById("table1").style.display="block";</script>
                <form id="confirmation" method="post" action="compte.php">
                    <input type="hidden" name="id_emp" value="'.$id.'">
                    <p>Voulez vous vraiment supprimer cet element?</p>
                    <button type="submit" name="confirmer_s">Confirmer</button>
                    <button type="button" onclick="closePopup()">Annuler</button>
                </form>
            </div>';}}
            if(isset($_POST['confirmer_s'])) {
                $id_emp = isset($_POST['id']) ? $_POST['id'] : null;
            
                if($id_emp) {
                     echo'<script>document.getElementById("table1").style.display="block";</script>';
                    $sql_delete = "DELETE FROM conge_annuel WHERE id = $id";
                    if ($con->query($sql_delete) === TRUE) {
                                    
                    } else {
                        echo "Erreur lors de la suppression de l'élément.";
                    }
                    
                } 
        }    
        
       
        if(isset($_POST['confirmer_supp'])) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            
            if($id) {
                // Assurez-vous de toujours utiliser des requêtes préparées pour éviter les attaques par injection SQL
                $sql_delete = "DELETE FROM titre WHERE id = ?";
                $stmt = $con->prepare($sql_delete);
                $stmt->bind_param("i", $id); // "i" pour un entier
                
                if ($stmt->execute()) {
                    echo "Élément supprimé avec succès.";
                } else {
                    echo "Erreur lors de la suppression de l'élément.";
                }
                
                $stmt->close(); // Fermer la requête préparée
            } 
        }
                  


?>
<script>
    function openPopupConfirmation_2() {
        document.getElementById('confirmation_s2').style.display = 'block';
        document.getElementById('table2').style.display = 'block';
    }

    function openPopupConfirmation_ss2() {
        document.getElementById('table2').style.display = 'block';
        document.getElementById('confirmation_s22').style.display = 'block';
    }
   
    function closePopup_2() {
        document.getElementById('confirmation_s2').style.display = 'none';
        document.getElementById('confirmation_s22').style.display = 'none';
        document.getElementById('table2').style.display = 'block';
    }
    
</script>
<?php

        if(isset($_POST['modifier_b'])) {
            echo"<script>document.getElementById('table2').style.display = 'block';</script>";
            $id = $_POST['id'];
            $jrs_rest = $_POST['jrs_rest'];
            $jrs_consom = $_POST['jrs_consom'];
            $jrs_autoris = $_POST['jrs_auto'];
            
                
            

                if ($jrs_autoris != null) {
                    $sql3 = "UPDATE conge_requisition SET jrs_requisition= ? WHERE id= ?";
                    $stmt3 = $con->prepare($sql3);
                    if (!$stmt3) {
                        // Prepare failed
                        echo "Error: " . $con->error;
                        exit;
                    }
                    // Convert d_depa
                    $stmt3->bind_param("ii", $jrs_autoris, $id);
                    if (!$stmt3->execute()) {
                        // Execute failed
                        echo "Error: " . $stmt3->error;
                        exit;
                    }
                }

                if ($jrs_rest != null) {
                    $sql4 = "UPDATE conge_requisition SET jrs_rest= ? WHERE id= ?";
                    $stmt4 = $con->prepare($sql4);
                    if (!$stmt4) {
                        // Prepare failed
                        echo "Error: " . $con->error;
                        exit;
                    }
                    // Convert d_depa
                    $stmt4->bind_param("ii", $jrs_rest, $id);
                    if (!$stmt4->execute()) {
                        // Execute failed
                        echo "Error: " . $stmt4->error;
                        exit;
                    }
                }

                if ($jrs_consom != null) {
                    $sql5 = "UPDATE conge_requisition SET jrs_consom= ? WHERE id= ?";
                    $stmt5 = $con->prepare($sql5);
                    if (!$stmt5) {
                        // Prepare failed
                        echo "Error: " . $con->error;
                        exit;
                    }
                    // Convert d_depa
                    $stmt5->bind_param("ii", $jrs_consom, $id);
                    if (!$stmt5->execute()) {
                        // Execute failed
                        echo "Error: " . $stmt5->error;
                        exit;
                    }
                }
                
            
            

            }


        
            if(isset($_POST['confirmer_s22'])) {
                echo"<script>document.getElementById('table2').style.display = 'block';</script>";
                $id_emp = isset($_POST['id']) ? $_POST['id'] : null;
                echo '<script>console.log('.$id_emp.')</script>';
                if($id_emp) {
                
                    $sql_delete = "DELETE FROM conge_requisition WHERE id = $id_emp";
                    if ($con->query($sql_delete) === TRUE) {
                                    
                    } else {
                        echo "Erreur lors de la suppression de l'élément.";
                    }
                    
                } 
        }          
        
        if (isset($_POST['in'])) {
            $id = $_POST['idp'];
            $d_demande = $_POST['d_demande'];
            $idemp = $_POST['emp'];
            echo'<script>console.log('.$id.');</script>';
            if ($id) {
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 && $_FILES['photo']['size'] <= 1000000) {
                    $photo = $_FILES['photo']['name'];
                    $fileTmpName = $_FILES['photo']['tmp_name'];
                    $fileSize = $_FILES['photo']['size'];
                    $folder = "./img/";
                
                    // Vérifiez si le dossier existe, sinon créez-le
                    if (!file_exists($folder)) {
                        mkdir($folder, 0755, true);
                    }
                
                    // Vérifiez si le fichier a été uploadé et si la taille ne dépasse pas le limite
                    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 && $_FILES['photo']['size'] <= 1000000) {
                        // Génèrez un nom unique pour le fichier
                        $newImageName = uniqid();
                        $newImageName .= "." . pathinfo($photo, PATHINFO_EXTENSION);
                
                        // Déplacez le fichier temporaire vers le dossier 'img'
                        move_uploaded_file($fileTmpName, $folder . $newImageName);
 
        
                    
                        // Insérer le fichier joint dans la base de données
                        $sql = "UPDATE titre SET piece = :piece WHERE id = :id AND d_employe = :idemp";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':piece', $newImageName);
                        $stmt->bindParam(':id', $id);
                        $stmt->bindParam(':idemp', $idemp);
                        if ($stmt->execute()){
                    
                        }
                        
                        echo "Erreur : le fichier a été inséré.";
                    } else {
                        echo "Erreur : le fichier n'a pas été inséré.";
                    }
                    
            }
        
            }
        }
?>
<script>
    function openPopupConfirmation() {
        document.getElementById('confirmation_s').style.display = 'block';
        document.getElementById('table1').style.display = 'block';
    }



    function closePopup() {
        document.getElementById('confirmation_s').style.display = 'none';
        document.getElementById('table1').style.display = 'block';
    }


    

    function openPopupConfirmation_supp7() {
        document.getElementById('table7').style.display = 'block';
        document.getElementById('confirmation_supp7').style.display = 'block';
    }



    function closePopup_7() {
        document.getElementById('confirmation_supp7').style.display = 'none';
        document.getElementById('table7').style.display = 'block';
    }
    function openPopupConfirmation_i(employeeId) {
    document.getElementById('table7').style.display = 'block';
    var popup = document.querySelector('.popup_v[data-id="' + employeeId + '"]');
    popup.style.display = "block";
}



    function closePopup_i() {
        document.getElementById('inserer').style.display = 'none';
        document.getElementById('table7').style.display = 'block';
    }

   

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
    </script>
</body>
</html>