<?php
session_start();
// Établir la connexion à la base de données
$servername = "localhost"; // Adresse du serveur MySQL
$username = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL
$dbname = "baosem"; // Nom de la base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}


// Fermer la connexion
$conn = new PDO("mysql:host=localhost;dbname=baosem", "root", "");
$db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');




$id_receive = $_SESSION['id'];

$sql = "SELECT * FROM notifications WHERE id_receive = ? OR id_receive = ? ORDER BY is_read ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_receive, $id_receive]); // Ajoutez le deuxième paramètre ici
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_unread_notifications = 0;

$receiv_num = "SELECT COUNT(*) AS total_unread_notifications 
                FROM notifications 
                WHERE id_receive = ? 
                AND is_read = 0";

$statement = $conn->prepare($receiv_num);
$statement->execute([$id_receive]);
$row = $statement->fetch();
$total_unread_notifications += $row['total_unread_notifications'];
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Source+Sans+Pro:wght@600;700&display=swap"
    rel="stylesheet">

        <!-- ===== CSS ===== -->
        <link rel="stylesheet" href="employee.css">
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
        <title>employee</title>
    </head>
    <body id="body-pd">

        <!--SIDE BAR-->
        <div class="l-navbar" id="navbar">
            <nav class="nav">
                <div>
                    <div class="nav__brand">
                        <ion-icon name="menu-outline" class="nav__toggle" id="nav-toggle"></ion-icon>
                    </div>
                    <div class="nav__list">
                    <a href="profil.php" class="nav__link ">
                            <ion-icon name="home-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Acceuil</span>
                        </a>
                        <a href="employee.php" class="nav__link active">
                        <ion-icon name="notifications-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Acceuil</span>
                        </a>
                        
                        <a href="employee2.php" class="nav__link">
                            <ion-icon name="calendar-outline" class="nav__icon"></ion-icon>
                            <span class="nav__name">Congés</span>
                        </a>
                      
                    </div>
                </div>

                <a onclick="logout()" class="nav__link">
                
                <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                <span class="nav__name">DECONNECTER</span>
            </a>  
            </nav>
        </div>
       
        <section class="section about" id="about">
        <div class="container">

          <div class="about-content">
            <div class="bouton">
<a onclick="openPopup()" class="button-34">notification <i class='bx bx-bell'></i> (<?php echo $total_unread_notifications; ?>)</a>
<?php if ($_SESSION['role'] == 'user' && $_SESSION['approbation'] != '0') {
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
                            <a href="conge_direct.php" class="button-34">Espace administratif</a>
                        <?php }else {
                            $st = $db->prepare('SELECT id_departement FROM departement WHERE nom_departement = :name');
                            $st->bindParam(':name', $_SESSION['approbation']);
                            $st->execute();
                            $id = $st->fetchColumn();

                            $stm = $db->prepare('SELECT * FROM departement WHERE id_departement = :id');
                                $stm->bindParam(':id', $id);
                                $stm->execute();
                                $id = $stm->fetchColumn();
                                $count = $stm->rowCount();
                                if ($count > 0) {
                                ?>
                                <a href="conge_depar.php" class="button-34 ">Espace administratif</a>
                                <?php
                            }
                        }  if($_SESSION['approbation'] == 'dg'){?>
                        <a href="conge_dg.php"  class="button-34 ">Espace administratif</a>
                        <?php }}?></div>
            <h2 class="h2 section-title underline">informations importante</h2>
            
        <style>
             .button-34 {
                margin-inline-start: auto;
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
            <div class="stats-list2">
              <button id="openmodal-annual">
                <div class="stats-card">
                  <p class="stats-text">informations sur</p>
                  <p class="stats-text">conge annuel</p>
                </div>
              </button>

              <button id="openmodal-sick">
                <div class="stats-card">
                  <p class="stats-text">informations sur</p>
                  <p class="stats-text">jours de recuperation</p>
                </div>
              </button>

              <button id="openmodal-unpaid">
                <div class="stats-card">
                  <p class="stats-text">informations sur</p>
                  <p class="stats-text">conge exceptionel</p>
                </div>
              </button>

              <button id="openmodal-maladie">
                <div class="stats-card">
                  <p class="stats-text">informations sur</p>
                  <p class="stats-text">conge de maladie</p>
                </div>
              </button>

              <button id="openmodal-absence">
                <div class="stats-card">
                  <p class="stats-text">informations sur</p>
                  <p class="stats-text">vos absence/retard</p>
                </div>
              </button>

              <button id="openmodal-pret">
                <div class="stats-card">
                  <p class="stats-text">informations sur</p>
                  <p class="stats-text">vos retenus de pret</p>
                </div>
              </button>
            </div>

             
                  <div class="modal" id="modal-annual">
                    <div class="modal-inner">
                      <?php  
                        $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
                          $stm = $db->prepare('SELECT * FROM conge_annuel Where d_employe = :id');
                          $stm->bindParam(':id', $_SESSION['id']);
                          $stm->execute();
                          $r = $stm->fetchAll(PDO::FETCH_ASSOC);
                          if (!empty($r)) {
                          foreach ($r as $row) {
                      ?>
                      <p> -vous avez <?php echo $row['jrs_autoris'];?> jours de conge annuel</p>
                      <p>-vous avez consome<?php echo $row['jrs_consome'];?> jours </p>
                      <p>- il vous reste <?php echo $row['jrs_restant'];?> jours  dans L'exercice <?php echo $row['exercice'];?> </p>
                      <form action="formulaire.php" method="post">
                        <input type="hidden" name="exerce" value="<?php echo $row['exercice']; ?>">
                       <button class="close" style="margin-left: 82px; background-color: black;" type="submit" name="envoie" onclick="window.location.href = 'formulaire.php';">faire une demande</button>
                     
                    </form> 
                    <?php
                          }
                   }else { ?><p> -vous n'avez pas des jours de conge annuel</p><?php } 
                  ?>
                    <div class="options">
                        <button class="close" id="closemodal-annual">Fermer</button>
                        
                        </div>
                    </div>
                  </div>
                 
                    
                  
                  
             <?php
                $stmt44 = $conn->prepare('SELECT * FROM conge_requisition WHERE d_employee = :id');
                $stmt44->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
                $stmt44->execute();
                $result4 = $stmt44->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($result4)) {
                    // Process the results
                    foreach ($result4 as $row4) {
                           
                ?>
                  <div class="modal" id="modal-sick">
                   <div class="modal-inner">
                      <p> -vous avez<?php echo $row4['jrs_requisition'];?>jours de recuperation</p>
                      <p>-vous avez consome <?php echo $row4['jrs_consom'];?>jours </p>
                      <p>- il vous reste <?php echo $row4['jrs_rest'];?> jours </p>
                      <div class="options">
                        <button class="close" id="closemodal-sick">fermer</button>
                        <button class="close" onclick="window.location.href = 'formulaire.php';">faire une demande</button>
                     </div>
                   </div>
                  </div>
                    <?php 
                    }
                  } else {?>
                    <div class="modal" id="modal-sick">
                    <div class="modal-inner">
                       <p> -vous n'avez pas de jours de recuperation</p>
                       <div class="options">
                         <button class="close" id="closemodal-sick">Close</button>
                      </div>
                    </div>
                   </div>
                <?php  
              }?>



        

                      <div class="modal" id="modal-unpaid">
                        <div class="modal-inner">
                          <div class="options">
                            <button class="close" id="closemodal-unpaid">Fermer</button>
                            <button class="close" onclick="window.location.href = 'formulaire.php';">faire une demande</button>
                          </div>
                        </div>
                      </div>

                   <?php
                      $stmt5 = $conn->prepare('SELECT * FROM conge_maladie WHERE d_employe = :id');
                      $stmt5->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
                      $stmt5->execute();
                      $result5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
                      if (!empty($result5)) {
                          // Process the results
                          foreach ($result5 as $row5) {
                    ?>
                  <div class="modal" id="maladie">
                    <div class="modal-inner">
                      <p> -vous avez <span id="duration1-<?php echo $row5['id']; ?>"><?php echo $row5['duree']; ?> heures</span> de maladie </p>
                      
            <script>
                convertHoursToDay2(document.getElementById('duration1-<?php echo $row5['id']; ?>'));
            </script>
                      <div class="options">
                        <button class="close" id="closemodal-maladie">Fermer</button>
                      </div>
                    </div>
                  </div>
                  <?php 
                        }
                      } else {?>
                      <div class="modal" id="maladie">
                    <div class="modal-inner">
                      <p> -vous n'avez pas des heures de maladie </p>
                   
                      <div class="options">
                        <button class="close" id="closemodal-maladie">Fermer</button>
                      </div>
                    </div>
                  </div>
                      <?php }
                  ?>


                 <?php
                      $stmt55 = $conn->prepare('SELECT * FROM absence_retard WHERE d_employee = :id');
                      $stmt55->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
                      $stmt55->execute();
                      $result55 = $stmt55->fetchAll(PDO::FETCH_ASSOC);
                      if (!empty($result55)) {
                          // Process the results
                          foreach ($result55 as $row55) {
                    ?>
                  <div class="modal" id="modal-absence">
                    <div class="modal-inner">
                      <p> -vous avez <span id="duration3-<?php echo $row55['id']; ?>"><?php echo $row55['duree']; ?> heures</span> de retard/absence </p>
                      <script>
                        convertHoursToDay3(document.getElementById('duration3-<?php echo $row55['id']; ?>'));
                    </script>
                      <div class="options">
                        <button class="close" id="closemodal-absence">Fermer</button>
                      </div>
                    </div>
                  </div>
                  <?php
                        }
                      }else { ?>
                      <div class="modal" id="modal-absence">
                    <div class="modal-inner">
                      <p> -vous n'avez pas des heures de retard/absence </p>
                      
                      <div class="options">
                        <button class="close" id="closemodal-absence">Fermer</button>
                      </div>
                    </div>
                  </div>
                      <?php }
                  ?>

                  
                 <?php
                      $stmt555 = $conn->prepare('SELECT * FROM pret WHERE id_employees = :id');
                      $stmt555->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
                      $stmt555->execute();
                      $result555 = $stmt555->fetchAll(PDO::FETCH_ASSOC);
                      
                      if (!empty($result555)) {
                          foreach ($result555 as $row555) {
                    ?>
                  <div class="modal" id="modal-pret">
                    <div class="modal-inner">
                      <p> -vous avez <?php echo $row555['rtn_electro']; ?> DA de retenu de l'electromenager </p>
                      <p> -vous avez <?php echo $row555['rtn_vehicule']; ?> DA de retenu de vehicule</p>
                      <p> -vous avez <?php echo $row555['rtn_admin']; ?> DA de retenu administratif </p>
                      <div class="options">
                        <button class="close" id="closemodal-pret">Fermer</button>
                      </div>
                    </div>
                  </div>
                  <?php 
                        }
                      }else {
                    ?>
                    <div class="modal" id="modal-pret">
                    <div class="modal-inner">
                     <p>vous n'avez pas des retenus de pret</p>
                    <div class="options">
                        <button class="close" id="closemodal-pret">Fermer</button>
                      </div>
                    </div>
                  </div>
                    <?php
                      }
                  ?>
      </section>


       
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        
        <!-- ===== MAIN JS ===== -->
        <script src="employee.js"></script>
        
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

function convertHoursToDay2(hoursElement) {
    let hoursText = hoursElement.innerText;
    let hours = parseFloat(hoursText.replace(" heures", ""));

    if (!isNaN(hours) && hours > 8) {
        let days = Math.floor(hours / 8);
        let remainingHours = hours % 8;
        hoursElement.innerText = `${days} jour(s) ${remainingHours} heure(s)`;
    }
}

// Appel de la fonction pour chaque élément avec l'ID commençant par 'duration1-'
document.addEventListener("DOMContentLoaded", function() {
    let durationElements = document.querySelectorAll("[id^='duration1-']");
    durationElements.forEach(function(element) {
        convertHoursToDay2(element);
    });
});

function convertHoursToDay3(hoursElement) {
    let hoursText = hoursElement.innerText;
    let hours = parseFloat(hoursText.replace(" heures", ""));

    if (!isNaN(hours) && hours > 8) {
        let days = Math.floor(hours / 8);
        let remainingHours = hours % 8;
        hoursElement.innerText = `${days} jour(s) ${remainingHours} heure(s)`;
    }
}

// Appel de la fonction pour chaque élément avec l'ID commençant par 'duration3-'
document.addEventListener("DOMContentLoaded", function() {
    let durationElements = document.querySelectorAll("[id^='duration3-']");
    durationElements.forEach(function(element) {
        convertHoursToDay3(element);
    });
});
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
