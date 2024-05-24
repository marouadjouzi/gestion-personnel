
<?php
session_start();
require_once('config.php');
$querry = "select * from role";
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
    <title>Gestion des Roles</title>
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
        <?php if($_SESSION['role'] == 'superadmin'){?>
        <a href="role.php" >Affectation des roles</a>
        <?php }?>
        <style>
            
            .main-container .info--wrapper .main-title a{
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
            
            .main-container .info--wrapper .main-title a:hover {
                box-shadow: rgba(80, 63, 205, 0.5) 0 1px 30px;
                transition-duration: .1s;
            }
         </style>       
        </div>
                
    <div class="container">
   
    <table id="employeesTable">
        <thead>
            <tr>
            <th>ID</th>
            <th>Nom.Prenom</th>
            <th>Approbation</th>
            <th>Departement</th>
            <th>Role</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    while($row = mysqli_fetch_assoc($result)) {
        $requete44 = "SELECT * FROM employees WHERE id_employees = " . $row['id'];
        $resultat44 = mysqli_query($con, $requete44);
        if ($resultat44 && mysqli_num_rows($resultat44) > 0) {
            while ($line44 = mysqli_fetch_assoc($resultat44)) {
                $requete444 = "SELECT * FROM departement WHERE id_departement = " . $line44['d_departement'];
                $resultat444 = mysqli_query($con, $requete444);
                while ($line444 = mysqli_fetch_assoc($resultat444)) {
                    
    ?>
   
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['approbation']; ?></td>
        <td><?php echo $line444['nom_departement']; ?></td>
        <td><?php echo $row['role']; ?></td>
        <?php 
        if($_SESSION['role'] == 'superadmin'){?>
        <td>
            <form action="compte.php" method="POST">
                <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" id="nom" name="nom" value="<?php echo $row['username']; ?>">
                <input type="hidden" id="role" name="role" value="<?php echo $row['role']; ?>">
                <input type="hidden" id="departement" name="nom_departement" value="<?php echo $line444['nom_departement']; ?>">
                <input type="hidden" id="departement" name="id_departement" value="<?php echo $line444['id_departement']; ?>">
                <button type="submit" name="modifier" style="background-color:rgb(84, 169, 90); color:#fff; border-radius:3px; font-size:xx-large">
                    <i class='bx bxs-edit'></i> 
                </button>
                <button type="submit" name="supprimer" style="background-color:red; color:#fff; border-radius:3px; font-size:xx-large">
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
 ?>   
</tbody>
        </table>
    </diV>
    
</main>
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
<?php
if(isset($_POST['modifier'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nom = $_POST['nom'];
    $role = $_POST['role'];
    $nom_departement = $_POST['nom_departement'];
    $id_departement = $_POST['id_departement'];
    if($id) {
        // Afficher le popup de confirmation
        echo '<div id="confirmation_s" class="popup_a">
            <form id="confirmation-form" method="post" action="compte.php">
                 <table style="background-color:#fff;">
                 <tr>
                <td style="background-color:#fff;"><input type="hidden" name="id" value="'.$id.'"></td>
                <td style="background-color:#fff;"><label style="color:grey;" for="nom" class="formbold-form-label"> Nom Prenom: </label></td>
                <td style="background-color:#fff;"><input type="text" name="nom" id="nom" class="formbold-form-input" value="'.$nom.'" readonly/></td>

                <td style="background-color:#fff;"><label style="color:grey; for="nom_responsable" class="formbold-form-label">Departement: </label></td>
                <td style="background-color:#fff;"><input type="text" name="departement" id="nom_responsable" value="'.$nom_departement.'" class="formbold-form-input" readonly/></td>
                ';$requete444 = "SELECT * FROM departement ";
                $resultat444 = mysqli_query($con, $requete444);
                echo '
                    <td>
                        <label for="super" class="formbold-form-label">Nouveau departement :</label>
                        <select name="d_departement" id="departement" required>
                ';
                while ($line444 = mysqli_fetch_assoc($resultat444)) {
                    echo '
                        <option value="'.$line444['id_departement'].'">'.$line444['nom_departement'].'</option>
                    ';
                }
                echo '
                        </select>
                    </td>
                '; echo'
                
                
                
                <td style="background-color:#fff;"><label style="color:grey; for="nom_responsable" class="formbold-form-label">Role: </label></td>
                <td style="background-color:#fff;"><input type="text" name="responsable" id="nom_responsable" value="'.$role.'" class="formbold-form-input" readonly/></td>
                <td><label for="super"class="formbold-form-label">Nouveau Role :</label>
                <select name="role" id="role"  required>
                          <option value="">Veuillez choisir :</option>
                          <option value="superadmin">Administrateur principal</option>
                          <option value="admin">Administrateur</option>
                          <option value="user">Utilisateur</option>
                      </select></td>
                </tr>
                </table>
                <button type="submit" name="modifierrole">Modifier</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';
    }

}

if(isset($_POST['modifierrole'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $role = $_POST['role'];
    $d_departement = $_POST['d_departement'];

    
        
    if($role != null){
        $sql2 = "UPDATE role SET  role= ? where id= ?";
        $stmt2 = $con->prepare($sql2);
        if (!$stmt2) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt2->bind_param("sd", $role, $id);
         if (!$stmt2->execute()) {
         // Execute failed
         echo "Error: " . $stmt2->error;
         exit;
        } }

        if ($d_departement != null) {
            $sql3 = "UPDATE employees SET d_departement= ? WHERE id_employees= ?";
            $stmt3 = $con->prepare($sql3);
            if (!$stmt3) {
                // Prepare failed
                echo "Error: " . $con->error;
                exit;
            }
            // Convert d_departement to integer
            $d_departement_int = intval($d_departement);
            $stmt3->bind_param("ii", $d_departement_int, $id);
            if (!$stmt3->execute()) {
                // Execute failed
                echo "Error: " . $stmt3->error;
                exit;
            }
        }
        
     
    

    }


if(isset($_POST['supprimer'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    
        if($id) {
            
            echo '<div id="confirmation_s" class="popup_c">
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
            
                $sql_delete = "DELETE FROM role WHERE id = $id";
                if ($con->query($sql_delete) === TRUE) {
                                
                } else {
                    echo "Erreur lors de la suppression de l'élément.";
                }
                
            } 
        }          

?>

<script>
function openPopupConfirmation() {
    document.getElementById('confirmation_s').style.display = 'block';
}



function closePopup() {
    document.getElementById('confirmation_s').style.display = 'none';
}

// Appeler la fonction openPopup pour afficher le popup
window.onload = function() {
    openPopupConfirmation();
    
};

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