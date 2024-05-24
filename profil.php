<?php
session_start();
require_once('config.php');
$query1 = "SELECT * FROM employees where id_employees =".$_SESSION['id'];
$result1 = mysqli_query($con, $query1);
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
    <title>Mon profil</title>
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
                        <a href="profil.php" class="nav__link active">
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
                            $id = $st->fetchColumn();

                            $stm = $db->prepare('SELECT * FROM departement WHERE id_departement = :id');
                                $stm->bindParam(':id', $id);
                                $stm->execute();
                                $id = $stm->fetchColumn();
                                $count = $stm->rowCount();
                                if ($count > 0) {
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
            <p class="font-weight-bold">Mes informations</p>
            <img src="baosem_logo.png" alt="" style="width:15%;">
        </div>
        </div>
        <div class="main">
        <h2>Mon Profil</h2>
        <div class="card">
            <div class="card-body">
            <?php 
        while($row = $result1->fetch_assoc()) {?>
            <div class="formbold-input-flex">
            <div>
              <label for="prenom" class="formbold-form-label">
                Prenom
              </label>
              <input
                type="text"
                name="prenom"
                id="prenom"
                value="<?php echo $row['prenom'];?>"
                class="formbold-form-input" readonly
              />
            </div>
            <div>
              <label for="nom" class="formbold-form-label"> Nom </label>
              <input
                type="text"
                name="nom"
                id="nom"
                value="<?php echo $row['nom'];?>"
                class="formbold-form-input" redonly
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
                value="<?php echo $row['affectation'];?>"
                class="formbold-form-input" readonly
              />
            </div>
            <div>
              <label for="fonction" class="formbold-form-label"> Fonction: </label>
              <input
                type="text"
                name="fonction"
                id="fonction"
                value="<?php echo $row['fonction'];?>"
                class="formbold-form-input" readonly
              />
            </div>
          </div>
          
          <div class="formbold-input-flex">
            <div>
              <label for="date_n" class="formbold-form-label"> Date de Naissance: </label>
              <input
                type="text"
                name="date_n"
                id="date_n"
                value="<?php echo $row['date_n'];?>"
                class="formbold-form-input" readonly
              />
            </div>
            <div>
              <label for="lieu_n" class="formbold-form-label"> Lieu de Naissance: </label>
              <input
                type="text"
                name="lieu_n"
                id="lieu_n"
                value="<?php echo $row['lieu_n'];?>"
                class="formbold-form-input" readonly
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
                value="<?php echo $row['email'];?>"
                class="formbold-form-input" readonly
              />
            </div>
            <div>
              <label for="phone" class="formbold-form-label"> Num de Telephone </label>
              <input
                type="text"
                name="phone"
                id="phone"
                value="<?php echo $row['phone'];?>"
                class="formbold-form-input" readonly
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
              value="<?php echo $row['adresse'];?>"
              class="formbold-form-input" readonly
            />
          </div>
    
            <div>
              <label for="nss" class="formbold-form-label"> Numéro de sécurité sociale: </label>
              <input
                type="text"
                name="nss"
                id="post"
                value="<?php echo $row['nss'];?>"
                class="formbold-form-input" readonly
              />
            </div>
            </div>
            <?php }?>
        </div>
    </main>
    <style>
        /* Import Font Dancing Script */
@import url(https://fonts.googleapis.com/css?family=Dancing+Script);

* {
    margin: 0;
}

body {
    background-color: #e8f5ff;
    font-family: Arial;
}

/* NavbarTop */
.navbar-top {
    background-color: #fff;
    color: #333;
    box-shadow: 0px 4px 8px 0px grey;
    height: 70px;
}

.title {
    font-family: 'Dancing Script', cursive;
    padding-top: 15px;
    position: absolute;
    left: 45%;
}

.navbar-top ul {
    float: right;
    list-style-type: none;
    margin: 0;
    overflow: hidden;
    padding: 18px 50px 0 40px;
}

.navbar-top ul li {
    float: left;
}

.navbar-top ul li a {
    color: #333;
    padding: 14px 16px;
    text-align: center;
    text-decoration: none;
}

.icon-count {
    background-color: #ff0000;
    color: #fff;
    float: right;
    font-size: 11px;
    left: -25px;
    padding: 2px;
    position: relative;
}

/* End */

/* Sidenav */
.sidenav {
    background-color: #fff;
    color: #333;
    border-bottom-right-radius: 25px;
    height: 86%;
    left: 0;
    overflow-x: hidden;
    padding-top: 20px;
    position: absolute;
    top: 70px;
    width: 250px;
}

.profile {
    margin-bottom: 20px;
    margin-top: -12px;
    text-align: center;
}

.profile img {
    border-radius: 50%;
    box-shadow: 0px 0px 5px 1px grey;
}

.name {
    font-size: 20px;
    font-weight: bold;
    padding-top: 20px;
}

.job {
    font-size: 16px;
    font-weight: bold;
    padding-top: 10px;
}

.url, hr {
    text-align: center;
}

.url hr {
    margin-left: 20%;
    width: 60%;
}

.url a {
    color: #818181;
    display: block;
    font-size: 20px;
    margin: 10px 0;
    padding: 6px 8px;
    text-decoration: none;
}

.url a:hover, .url .active {
    background-color: #e8f5ff;
    border-radius: 28px;
    color: #000;
    margin-left: 14%;
    width: 65%;
}

/* End */

/* Main */
.main {
    margin-top: 2%;
    font-size: 28px;
    padding: 0 10px;
    width: 100%;
}

.main h2 {
    color: #333;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 24px;
    margin-bottom: 10px;
}

.main .card {
    background-color: #fff;
    border-radius: 18px;
    box-shadow: 1px 1px 8px 0 grey;
    height: auto;
    margin-bottom: 20px;
    padding: 20px 0 20px 50px;
}

.main .card table {
    border: none;
    font-size: 16px;
    height: 270px;
    width: 80%;
}

.edit {
    position: absolute;
    color: #e7e7e8;
    right: 14%;
}
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
        color: #000;
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