<?php
session_start();
require_once('config.php');
$querry = "select * from employees";
$result = mysqli_query($con,$querry);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link rel="stylesheet" href="superadmin.css">
    <link rel="stylesheet" href="role.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <title>Affecter des Roles</title>
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
                        <div  class="nav__link collapse active" >
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
                        <a href="absence.php" class="nav__link ">
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
        <p class="font-weight-bold">Gestion des Roles</p>
        
        <div class="wrap">
            <div class="search">
            <input type="text"  id="searchInput" oninput="searchTable()" class="searchTerm" placeholder="Recherche....">
            <button type="submit" class="searchButton">
            <ion-icon name="search-outline"></ion-icon>
            </button>
            </div>
        </div>
        <div class="b" style="display:flex;">

        <a href="ajout.php" style="margin-right:10px;" >Ajouter un element</a>
        <a href="compte.php"  >Retour</a></div>
       </div>
         <style>
            
            .main-container .info--wrapper .main-title .b a{
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
                display: flex;
                align-items: center;
                justify-content: center;
                height: 4rem;
                padding: 0 1.6rem;
            }
            
            .main-container .info--wrapper .main-title .b a:hover {
                box-shadow: rgba(80, 63, 205, 0.5) 0 1px 30px;
                transition-duration: .1s;
            }
         </style>       
    <div class="container">
   
    <table id="employeesTable">
        <thead>
            <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Date_naissance</th>
            <th>lieu_naissance</th>
            <th>Telephone</th>
            <th>E-mail</th>
            <th>Adresse</th>
            <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
            <td><?php echo $row['id_employees']; ?></td>
            <td><?php echo $row['nom']; ?></td>
            <td><?php echo $row['prenom']; ?></td>
            <td><?php echo $row['date_n']; ?></td>
            <td><?php echo $row['lieu_n']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['adresse']; ?></td>
           
            <td><form action="ajout-role.php" method="POST">
                <input type="hidden" id="id" name="id"  value="<?php echo $row['id_employees']; ?>">
                <input type="hidden" id="nom" name="nom"  value="<?php echo $row['nom']; ?>">
                <input type="hidden" id="prenom" name="prenom"  value="<?php echo $row['prenom']; ?>"><br><br>
                <?php 
                if($_SESSION['role'] == 'superadmin'){
                $stmt = $con->prepare("SELECT * FROM role WHERE id = ".$row['id_employees']);
                $stmt->execute();
                $count = $stmt->store_result();
                $count = $stmt->num_rows;
            
                // If the element doesn't exist in the role table, display the "ajouter" button
                if ($count == 0) {
                    echo '<button type="submit" name="ajouter"><ion-icon name="add-circle"></ion-icon></button>';
                }}
                ?>
                
                </form></td> <?php }?>
            </tr>
            
                
         </tbody>

        </table>
    </diV>
    
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