<?php
session_start();
require_once('config.php');


if(isset($_POST['recherche'])){
    $element = $_POST['element'];
    $type = $_POST['type'];
    
    if($type == 'departement'){
        $sql = "SELECT * FROM departement WHERE nom_departement = '$element'";
        $result_r = mysqli_query($con, $sql);
        
        if($result_r){
            while($row = mysqli_fetch_assoc($result_r)){
                $sql_direction = "SELECT nom_direction FROM direction WHERE id_direction = " . $row['d_direction'];
                $result_direction = mysqli_query($con, $sql_direction);
                $row_direction = mysqli_fetch_assoc($result_direction);
                
                $input[] = '<table >
                             <thead >
                                <tr>
                                    <th style="color:white;">nom</th>
                                    <th style="color:white;">Responsable</th>
                                    <th style="color:white;">superieur</th>               
                                </tr>
                             </thead>
                             <tbody style="background-color:#fff;">   
                                <tr>
                                    <td style="color:#3318ff; background-color:#fff;">' . $row['nom_departement'] . '</td>
                                    <td style="color:#3318ff; background-color:#fff;">' . $row['responsable'] . '</td>
                                    <td style="color:#3318ff;background-color:#fff;">' . $row_direction['nom_direction'] . '</td>
                                </tr>
                             </tbody>   
                            </table>';
            }
        } else {
            $input[] = "<p style='color:#3318ff'>Ce département n'existe pas</p>";
        }
    } elseif($type == 'employee'){
        $sql = "SELECT * FROM employees WHERE nom = '$element'";
        $result_r = mysqli_query($con, $sql);
        
        if($result_r){
            while($row = mysqli_fetch_assoc($result_r)){
                $sql_departement = "SELECT nom_departement FROM departement WHERE id_departement = " . $row['d_departement'];
                $result_departement = mysqli_query($con, $sql_departement);
                $row_departement = mysqli_fetch_assoc($result_departement);
                
                
                $input[] = '<table>
                                <tr>
                                    <th style="color:black;">Nom</th>
                                    <th style="color:black;">Prenom</th>
                                    <th style="color:black;">E-mail</th>
                                    <th style="color:black;">Département</th>               
                                </tr>
                                <tr>
                                    <td style="color:#3318ff;">' . $row['nom'] . '</td>
                                    <td style="color:#3318ff;">' . $row['prenom'] . '</td>
                                    <td style="color:#3318ff;">' . $row['email'] . '</td>
                                    <td style="color:#3318ff;">' . $row_departement['nom_departement'] . '</td>
                                </tr>
                            </table>';
            }
        } else {
            $input[] = "<p style='color:#3318ff'>Cet employé n'existe pas</p>";
        }
    }
}
$sqlt = "SELECT COUNT(*) as total_elements FROM employees";
$resultt = mysqli_query($con, $sqlt);
$rowt = mysqli_fetch_assoc($resultt);

$sqlt1 = "SELECT COUNT(*) as total_elements1 FROM departement";
$resultt1 = mysqli_query($con, $sqlt1);
$rowt1 = mysqli_fetch_assoc($resultt1);

$sqlt2 = "SELECT COUNT(*) as total_elements2 FROM direction";
$resultt2 = mysqli_query($con, $sqlt2);
$rowt2 = mysqli_fetch_assoc($resultt2);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="superadmin.css">
        <link rel='stylesheet' href="stucture.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>superadmin structure</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="sweetalert2.min.js"></script>
        <link rel="stylesheet" href="sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="sweetalert2.min.js"></script>
        <link rel="stylesheet" href="sweetalert2.min.css">
    
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
                                <a href="compte.php" class="collapse__sublink">Roles</a>
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
          <p class="font-weight-bold">STRUCTURE</p>
         
         <div class="search">
     <div class="wrap"><form action="structure.php" method="POST" style="display:flex;">
      <input type="text" class="searchTerm" name="element" placeholder="Chercher un element........">
      <select name="type" id="type" style="font-size: 12px;border-radius: 4px;color: darkblue;height: 37px;border: 3px solid #00B4CC;"><option value="departement">departement</option>
    <option value="employee">employee</option></select>
      <button type="submit" class="searchButton" name="recherche">
      <i class='bx bx-search'></i>
     </button></form>
   </div>
</diV>
<?php if($_SESSION['role'] == "superadmin"){?>
          <a href="ajout.php"  class="btn btn-primary" style="background-color: #5372F0;color: #fff;padding: 20px 20px;border: none;border-radius: 5px;cursor: pointer;transition: background-color 0.3s; hight:45px;">Parametrer votre structure</a><?php }?>
        </div>
        <?php
        $querry = "select * from direction";
        $result = mysqli_query($con,$querry);
      if(isset($input)){
        foreach($input as $result_rr){
            echo $result_rr ;
        }
    }
    ?>
      
        <div class="main-cards">

          <div class="card">
            <div class="card-inner">
              <p class="text-primary">Direction Generale</p>
              <span class="material-icons-outlined text-blue">inventory_2</span>
            </div>
            <span class="text-primary font-weight-bold">1</span>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="text-primary">Sous Direction</p>
              <span class="material-icons-outlined text-orange"><i class='bx bx-building-house'></i></span>
            </div>
            <span class="text-primary font-weight-bold"><?php echo $rowt2['total_elements2']?></span>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="text-primary">Departement</p>
              <span class="material-icons-outlined text-green"><ion-icon name="file-tray-stacked-outline"></ion-icon></span>
            </div>
            <span class="text-primary font-weight-bold"> <?php echo $rowt1['total_elements1']?></span>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="text-primary">Employés</p>
              <span class="material-icons-outlined text-red"><ion-icon name="people-outline"></ion-icon></span>
            </div>
            <span class="text-primary font-weight-bold"> <?php echo $rowt['total_elements']?></span>
          </div>

    </div>
        <div class="container">
     <?php if($_SESSION['role']=='admin' || $_SESSION['role']=='superadmin' || $_SESSION['approbation'] == 'dg'){?>
    <table>
    <thead>
        <tr>
            <th>nom_direction</th>
            <th>Responsable</th>
            <th>nbr_departement</th>
            <th>Action</th>
        </tr>
    </thead>        <?php 
        while($row = mysqli_fetch_assoc($result)) {
            if($row['id_direction'] != '24'){
        ?>
    <tbody>

        <tr>
            <td><?php echo $row['nom_direction']; ?></td>
            <td><?php echo $row['responsable']; ?></td>
            <?php
            $sqltt1 = "SELECT COUNT(*) as total_elements FROM departement WHERE d_direction = " . $row['id_direction'];
            $resulttt0 = mysqli_query($con, $sqltt1);
            $rowtt0 = mysqli_fetch_assoc($resulttt0);
            ?>
            <td><?php echo $rowtt0['total_elements']; ?></td>
            <td>
                <div class="action">
                    <button onclick="toggleSubTable('<?php echo $row['id_direction']; ?>')">Explorer</button>
                    <?php if ($_SESSION['role'] == 'superadmin'){?>
                    <form action="structure.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id_direction']; ?>">
                        <button type="submit" name="supprimer_direction" class="supprimer">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </form>
                    <form action="structure.php" method="post">
                        <input type="hidden" name="id_m" value="<?php echo $row['id_direction']; ?>">
                        <input type="hidden" name="nom" value="<?php echo $row['nom_direction']; ?>">
                        <input type="hidden" name="responsable" value="<?php echo $row['responsable']; ?>">
                        <button type="submit" name="modifier_direction" class="supprimer" style="background-color:rgb(84 169 90);">
                            <i class='bx bxs-edit'></i>
                        </button>
                    </form>
                    <?php }?>
                </div>
            </td>
        </tr>
        <tr id="<?php echo $row['id_direction']; ?>" style="display: none;">
                <td colspan="5">
                 <table class="sub-table" style="width:100%;">
                  <thead>
		            <tr>
			         <th>nom_departement</th>
			         <th>Responsable</th>
			         <th>nbr d'employees</th>
			         <th>Action</th>
		            </tr>
	              </thead>
                  <?php
                    
	                $query_dep = "SELECT * FROM departement WHERE d_direction = " . $row['id_direction'];
	                $result_dep = mysqli_query($con, $query_dep);
                    while($row_dep = mysqli_fetch_assoc($result_dep)) {
                        $sqlt0 = "SELECT COUNT(*) as total_elements FROM employees WHERE d_departement = " . $row_dep['id_departement'];
			            $resultt0 = mysqli_query($con, $sqlt0);
			            $rowt0 = mysqli_fetch_assoc($resultt0);
	              ?>
                  <tbody>
                    <tr>
                     <td><?php echo $row_dep['nom_departement']; ?></td>
			         <td><?php echo $row_dep['responsable']; ?></td>
                     <td><?php echo $rowt0['total_elements']; ?></td>
                     <td>
				      <div class="action">
				        <button onclick="toggleSubTable0('sub-table-row-<?php echo $row_dep['id_departement']; ?>')">Explorer</button>
                        <?php if($_SESSION['role']=='superadmin'){?>
			            <form action="structure.php" method="post">
					     <input type="hidden" name="id_d" value="<?php echo $row_dep['id_departement']; ?>">
					     <button type="submit" name="supprimer_departement" class="supprimer">
						   <ion-icon name="trash-outline"></ion-icon>
					     </button>
				        </form>
				        <form action="structure.php" method="post">
				         <input type="hidden" name="id_m" value="<?php echo $row_dep['id_departement']; ?>">
				         <input type="hidden" name="nom" value="<?php echo $row_dep['nom_departement']; ?>">
				         <input type="hidden" name="responsable" value="<?php echo $row_dep['responsable']; ?>">
				         <button type="submit" name="modifier_departement" class="supprimer" style="background-color:rgb(84 169 90);">
				          <i class='bx bxs-edit'></i>
				         </button>
				        </form>
                        <?php }?>
                      </div>
			         </td>
                    </tr>
                    <tr id="sub-table-row-<?php echo $row_dep['id_departement']; ?>" style="display: none;">
			         <td colspan="5">
				      <table class="sub-table1" style="width: 100%;">
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
							<th>NSS</th>
							<th>Action</th>
						</tr>
					   </thead>
                       <?php
					$query_emp = "SELECT * FROM employees WHERE d_departement = " . $row_dep['id_departement'];
					$result_emp = mysqli_query($con, $query_emp);
                    while($row_emp = mysqli_fetch_assoc($result_emp)) {
				?>
                       <tbody>
                        <tr>
                            <td><?php echo $row_emp['id_employees']; ?></td>
							<td><?php echo $row_emp['nom']; ?></td>
							<td><?php echo $row_emp['prenom']; ?></td>
							<td><?php echo $row_emp['date_n']; ?></td>
							<td><?php echo $row_emp['lieu_n']; ?></td>
							<td><?php echo $row_emp['phone']; ?></td>
							<td><?php echo $row_emp['email']; ?></td>
							<td><?php echo $row_emp['adresse']; ?></td>
							<td><?php echo $row_emp['nss']; ?></td>
                            <td>
                                <div class="action">
                                <?php if($_SESSION['role'] =='superadmin'){?>
                                 <form action="structure.php" method="post">
									<input type="hidden" name="id_emp" value="<?php echo $row_emp['id_employees']; ?>">
									<button type="submit" name="supprimer_employee" style="background-color: rgb(235, 47, 47);font-size: 30px;margin-left: 10px;">
										<ion-icon name="trash-outline"></ion-icon>
									</button>                                                    
								 </form>
                                 <form action="structure.php" method="post">
								  <input type="hidden" name="id_m" value="<?php echo $row_emp['id_employees']; ?>">
                                  <input type="hidden" name="nom" value="<?php echo $row_emp['nom']; ?>">
								  <input type="hidden" name="prenom" value="<?php echo $row_emp['prenom']; ?>">
								  <input type="hidden" name="date_n" value="<?php echo $row_emp['date_n']; ?>">
								  <input type="hidden" name="lieu_n" value="<?php echo $row_emp['lieu_n']; ?>">
								  <input type="hidden" name="phone" value="<?php echo $row_emp['phone']; ?>">
								  <input type="hidden" name="email" value="<?php echo $row_emp['email']; ?>">
								  <input type="hidden" name="adresse" value="<?php echo $row_emp['adresse']; ?>">
								  <input type="hidden" name="nss" value="<?php echo $row_emp['nss']; ?>">
								  <button type="submit" name="modifier_employee" class="supprimer" style="background-color:rgb(84 169 90);">
								   <i class='bx bxs-edit'></i>
								  </button>
								 </form>
                                 <?php }?>
                                </div>
                            </td> 
                        </tr>
                       </tbody>
                       <?php } ?>
                      </table>
                     </td>
                    </tr>
                  </tbody>  
                  <?php }?>
                 </table>   
                </td>
               
            </tr>
        <?php 
        ?>     
        </tbody><?php }}?>
    </table>
<?php }
else if ($_SESSION['role'] == 'user' && $_SESSION['approbation'] != '0') {
    $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
    $st = $db->prepare('SELECT id_direction FROM direction WHERE nom_direction = :name ');
    $st->bindParam(':name', $_SESSION['approbation']);
    $st->execute();
    $id = $st->fetchColumn();

    $stm = $db->prepare('SELECT * FROM direction WHERE id_direction = :id');
    $stm->bindParam(':id', $id);
    $stm->execute();
    $count = $stm->rowCount();
    if ($count > 0) {
        $r = $stm->fetchAll();
        ?>
        <table>
            <thead>
                <tr>
                    <th>nom_direction</th>
                    <th>Responsable</th>
                    <th>nbr_departement</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($r as $row) {
                    if ($row['id_direction'] != '24') {
                        ?>
                        <tr>
                            <td><?php echo $row['nom_direction']; ?></td>
                            <td><?php echo $row['responsable']; ?></td>
                            <?php
                            $stmt1 = $db->prepare('SELECT COUNT(*) as total_elements FROM departement WHERE d_direction = :id');
                            $stmt1->bindParam(':id', $row['id_direction']);
                            $stmt1->execute();
                            $row1 = $stmt1->fetch();
                            ?>
                            <td><?php echo $row1['total_elements']; ?></td>
                            <td>
                                <div class="action">
                                    <button onclick="toggleSubTable('<?php echo $row['id_direction']; ?>')">Explorer</button>
                                </div>
                            </td>
                        </tr>
                        <tr id="<?php echo $row['id_direction']; ?>" style="display: none;">
                            <td colspan="5">
                                <table class="sub-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>nom_departement</th>
                                            <th>Responsable</th>
                                            <th>nbr d'employees</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt2 = $db->prepare('SELECT * FROM departement WHERE d_direction = :id');
                                        $stmt2->bindParam(':id', $row['id_direction']);
                                        $stmt2->execute();
                                        while ($row2 = $stmt2->fetch()) {
                                            $stmt3 = $db->prepare('SELECT COUNT(*) as total_elements FROM employees WHERE d_departement = :id');
                                            $stmt3->bindParam(':id', $row2['id_departement']);
                                            $stmt3->execute();
                                            $row3 = $stmt3->fetch();
                                            ?>
                                            <tr>
                                                <td><?php echo $row2['nom_departement']; ?></td>
                                                <td><?php echo $row2['responsable']; ?></td>
                                                <td><?php echo $row3['total_elements']; ?></td>
                                                <td>
                                                    <div class="action">
                                                        <button onclick="toggleSubTable0('sub-table-row-<?php echo $row2['id_departement']; ?>')">Explorer</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr id="sub-table-row-<?php echo $row2['id_departement']; ?>" style="display: none;">
                                                <td colspan="5">
                                                    <table class="sub-table1" style="width: 100%;">
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
                                                                <th>NSS</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $stmt4 = $db->prepare('SELECT * FROM employees WHERE d_departement = :id');
                                                            $stmt4->bindParam(':id', $row2['id_departement']);
                                                            $stmt4->execute();
                                                            while ($row4 = $stmt4->fetch()) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row4['id_employees']; ?></td>
                                                                    <td><?php echo $row4['nom']; ?></td>
                                                                    <td><?php echo $row4['prenom']; ?></td>
                                                                    <td><?php echo $row4['date_n']; ?></td>
                                                                    <td><?php echo $row4['lieu_n']; ?></td>
                                                                    <td><?php echo $row4['phone']; ?></td>
                                                                    <td><?php echo $row4['email']; ?></td>
                                                                    <td><?php echo $row4['adresse']; ?></td>
                                                                    <td><?php echo $row4['nss']; ?></td>
                                                                    <td>
                                                                        <div class="action">
                                                                            <!-- Ajoutez ici les actions que vous souhaitez -->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    <?php }else {
    $db = new PDO('mysql:host=localhost;dbname=baosem', 'root', '');
    $st = $db->prepare('SELECT id_departement FROM departement WHERE nom_departement = :name ');
    $st->bindParam(':name', $_SESSION['approbation']);
    $st->execute();
    $id = $st->fetchColumn();

    $stm = $db->prepare('SELECT * FROM departement WHERE id_departement = :id');
    $stm->bindParam(':id', $id);
    $stm->execute();
    $r = $stm->fetchAll();
    
        ?>
        <?php?>
        <table>
                                    <thead>
                                        <tr>
                                            <th>nom_departement</th>
                                            <th>Responsable</th>
                                            <th>nbr d'employees</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($r as $row2) {
                                            $stmt3 = $db->prepare('SELECT COUNT(*) as total_elements FROM employees WHERE d_departement = :id');
                                            $stmt3->bindParam(':id', $row2['id_departement']);
                                            $stmt3->execute();
                                            $row3 = $stmt3->fetch();
                                            ?>
                                            <tr>
                                                <td><?php echo $row2['nom_departement']; ?></td>
                                                <td><?php echo $row2['responsable']; ?></td>
                                                <td><?php echo $row3['total_elements']; ?></td>
                                                <td>
                                                    <div class="action">
                                                        <button onclick="toggleSubTable0('sub-table-row-<?php echo $row2['id_departement']; ?>')">Explorer</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr id="sub-table-row-<?php echo $row2['id_departement']; ?>" style="display: none;">
                                                <td colspan="5">
                                                    <table class="sub-table1" style="width: 100%;">
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
                                                                <th>Numero de Sucerite Sociale</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $stmt4 = $db->prepare('SELECT * FROM employees WHERE d_departement = :id');
                                                            $stmt4->bindParam(':id', $row2['id_departement']);
                                                            $stmt4->execute();
                                                            while ($row4 = $stmt4->fetch()) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $row4['id_employees']; ?></td>
                                                                    <td><?php echo $row4['nom']; ?></td>
                                                                    <td><?php echo $row4['prenom']; ?></td>
                                                                    <td><?php echo $row4['date_n']; ?></td>
                                                                    <td><?php echo $row4['lieu_n']; ?></td>
                                                                    <td><?php echo $row4['phone']; ?></td>
                                                                    <td><?php echo $row4['email']; ?></td>
                                                                    <td><?php echo $row4['adresse']; ?></td>
                                                                    <td><?php echo $row4['nss']; ?></td>
                                                                    <td>
                                                                        <div class="action">
                                                                            <!-- Ajoutez ici les actions que vous souhaitez -->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
    <?php }}
 ?>

</div>
        </div>

</div>
</main>
              

    <!-- Scripts -->
    
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        
<script src="superadmin.js"></script>
<script>
function toggleSubTable(rowId) {
        var subTable = document.getElementById(rowId);
        if (subTable.style.display === "none") {
            subTable.style.display = "table-row";
        } else {
            subTable.style.display = "none";
        }
    }

function toggleSubTable0(rowId) {
    var subTable = document.getElementById(rowId);
    if (subTable.style.display === "none") {
        subTable.style.display = "table-row";
    } else {
        subTable.style.display = "none";
    }
}

</script>
    
<?php
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
              
            ?>

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
// Vérifier si le formulaire de suppression a été soumis
if(isset($_POST['supprimer_direction'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if($id) {
        
        echo '<div id="confirmation" class="popup_a">
            <form id="confirmation-form" method="post" action="structure.php">
                <input type="hidden" name="id" value="'.$id.'">
                <p>Attention! La suppression de cet élément provoque la suppression de tout élément lié</p>
                <button type="submit" name="confirmer">Confirmer</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';
    }
}

if(isset($_POST['confirmer'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if($id) {
        
        echo '<div style="background:grey;" id="confirmation" class="popup_a">
            <form  id="confirmation-form" method="post" action="structure.php">
                <input type="hidden" name="id" value="'.$id.'">
                <p style="color:white;">Etes vous sur de supprimer cette direction avec ses elements?</p>
                <button style="background:red;" type="submit" name="confirmer2">OUI</button>
                <button style="background:black;" type="button" onclick="closePopup()">NON</button>
            </form>
        </div>';
    }
}


if(isset($_POST['confirmer2'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if($id) {
        
        $sql_delete = "DELETE FROM direction WHERE id_direction = $id";
        if ($con->query($sql_delete) === TRUE) {
            
            $sql_alter = "ALTER TABLE departement
                          DROP FOREIGN KEY d_direction,
                          ADD CONSTRAINT d_direction FOREIGN KEY (d_direction) REFERENCES direction(id_direction) ON DELETE CASCADE";
            
        } else {
            echo "Erreur lors de la suppression de l'élément dans la table direction.";
        }
    } 
}


?>
<?php

if(isset($_POST['supprimer_departement'])) {
    
    $id_d = isset($_POST['id_d']) ? $_POST['id_d'] : null;

    
        
        if($id_d) {
            echo '<div id="confirmation" class="popup_b">
            <form id="confirmation" method="post" action="structure.php">
                <input type="hidden" name="id_d" value="'.$id_d.'">
                <p>Attention! la suppression de cet élément provoque la suppression de tout élément lié</p>
                <button type="submit" name="confirmer">Confirmer</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';
    
    }
    
    
    }
    
        if(isset($_POST['confirmer'])) {
            $id_d = isset($_POST['id_d']) ? $_POST['id_d'] : null;
        
            if($id_d) {
                
                $sql_delete = "DELETE FROM departement WHERE id_departement = $id_d";
                if ($con->query($sql_delete) === TRUE) {
                    
                    //$sql_alter = "ALTER TABLE employees 
                                 // DROP FOREIGN KEY d_departement,
                                 // ADD CONSTRAINT d_departement FOREIGN KEY (d_departement) REFERENCES departement(id_departement) ON DELETE CASCADE";
                    
                } else {
                    echo "Erreur lors de la suppression de l'élément dans la table direction.";
                }
            } 
        }        

?>
<?php

if(isset($_POST['supprimer_employee'])) {
    $id_emp = isset($_POST['id_emp']) ? $_POST['id_emp'] : null;

    
        if($id_emp) {
            
            echo '<div id="confirmation" class="popup_c">
            <form id="confirmation" method="post" action="structure.php">
                <input type="hidden" name="id_emp" value="'.$id_emp.'">
                <p>Voulez vous vraiment supprimer cet element?</p>
                <button type="submit" name="confirmer">Confirmer</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';}}
        if(isset($_POST['confirmer'])) {
            $id_emp = isset($_POST['id_emp']) ? $_POST['id_emp'] : null;
        
            if($id_emp) {
            
                $sql_delete = "DELETE FROM employees WHERE id_employees = $id_emp";
                if ($con->query($sql_delete) === TRUE) {
                                
                } else {
                    echo "Erreur lors de la suppression de l'élément dans la table direction.";
                }
                
            } 
        }          

?>


<?php

if(isset($_POST['modifier_direction'])) {
    $id = isset($_POST['id_m']) ? $_POST['id_m'] : null;
    $nom = $_POST['nom'];
    $responsable_d = $_POST['responsable'];
    if($id) {
        // Afficher le popup de confirmation
        echo '<div id="confirmation" class="popup_a">
            <form id="confirmation-form" method="post" action="structure.php">
                 <table style="background-color:#fff;">
                 <tr>
                <td style="background-color:#fff;"><input type="hidden" name="id" value="'.$id.'"></td>
                <td style="background-color:#fff;"><label style="color:grey;" for="nom" class="formbold-form-label"> NOM-Direction: </label></td>
                <td style="background-color:#fff;"><input type="text" name="nom" id="nom" class="formbold-form-input" value="'.$nom.'"/></td>
            
                <td style="background-color:#fff;"><label style="color:grey; for="nom_responsable" class="formbold-form-label">Responsable: </label></td>
                <td style="background-color:#fff;"><input type="text" name="responsable" id="nom_responsable" value="'.$responsable_d.'" class="formbold-form-input"/></td>
                </tr>
                </table>
                <button type="submit" name="modifierdirection">Modifier</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';
    }

}

if(isset($_POST['modifierdirection'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $responsable = $_POST['responsable'];

    if($nom != null){
        $sql1 = "UPDATE direction SET nom_direction = ? where id_direction = ?";
        $stmt1 = $con->prepare($sql1);
        if (!$stmt1) {
         // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt1->bind_param("sd", $nom, $id);
        if (!$stmt1->execute()) {
        // Execute failed
        echo "Error: " . $stmt1->error;
        exit;
       } }
        
    if($responsable != null){
        $sql2 = "UPDATE direction SET  responsable= ? where id_direction = ?";
        $stmt2 = $con->prepare($sql2);
        if (!$stmt2) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt2->bind_param("sd", $responsable, $id);
         if (!$stmt2->execute()) {
         // Execute failed
         echo "Error: " . $stmt2->error;
         exit;
        } }
     
    

    }

?>

<?php

if(isset($_POST['modifier_departement'])) {
    $id = isset($_POST['id_m']) ? $_POST['id_m'] : null;
    $nom = $_POST['nom'];
    $responsable = $_POST['responsable'];
    if($id) {
        // Afficher le popup de confirmation
        echo '<div id="confirmation" class="popup_a">
            <form id="confirmation-form" method="post" action="structure.php">
                 <table>
                 <tr>
                <td style="background-color:#fff;"><input type="hidden" name="id" value="'.$id.'"></td>
                <td style="background-color:#fff;"><label style="color:grey;" for="nom" class="formbold-form-label"> NOM-Departement: </label></td>
                <td style="background-color:#fff;"><input type="text" name="nom" id="nom" class="formbold-form-input" value="'.$nom.'"/></td>
                <td style="background-color:#fff;"><label style="color:grey; for="responsable" class="formbold-form-label"> Responsable: </label></td>
                <td style="background-color:#fff;"><input type="text" name="responsable" id="responsable" value="'.$responsable.'" class="formbold-form-input"/></td>
                </tr>
                </table>
                <button type="submit" name="modifierdepartement">Modifier</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';
    }

}
if(isset($_POST['modifierdepartement'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $responsable = $_POST['responsable'];

    if($nom != null){
        $sql1 = "UPDATE departement SET nom_departement = ? where id_departement = ?";
        $stmt1 = $con->prepare($sql1);
        if (!$stmt1) {
         // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt1->bind_param("sd", $nom, $id);
        if (!$stmt1->execute()) {
        // Execute failed
        echo "Error: " . $stmt1->error;
        exit;
       } }
        
    if($prenom_responsable != null){
        $sql2 = "UPDATE departement SET responsable= ? where id_departement = ?";
        $stmt2 = $con->prepare($sql2);
        if (!$stmt2) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt2->bind_param("sd", $responsable, $id);
         if (!$stmt2->execute()) {
         // Execute failed
         echo "Error: " . $stmt2->error;
         exit;
        } }
     
    }


?>

<?php
if(isset($_POST['modifier_employee'])) {
    $id = isset($_POST['id_m']) ? $_POST['id_m'] : null;
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_n = $_POST['date_n'];
    $lieu_n = $_POST['lieu_n'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];
    $nss = $_POST['nss'];
    if($id) {
        // Afficher le popup de confirmation
        echo '<div id="confirmation" class="popup_a">
            <form id="confirmation-form" method="post" action="structure.php">
                 <table>
                 <tr>
                <input type="hidden" name="id" value="'.$id.'">
                <td style="background-color:#fff;"><label style="color:grey;" for="nom" class="formbold-form-label"> NOM: </label>
                <input type="text" name="nom" id="nom" class="formbold-form-input" value="'.$nom.'"/></td>
                <td style="background-color:#fff;"><label style="color:grey; for="prenom" class="formbold-form-label">Prenom:</label>
                <input type="text" name="prenom" id="prenom_responsable" value="'.$prenom.'" class="formbold-form-input"/></td>
                <td style="background-color:#fff;"><label style="color:grey;" for="date_n" class="formbold-form-label"> date_naissance: </label>
                <input type="text" name="date_n" id="date_n" class="formbold-form-input" value="'.$date_n.'"/></td>
                <td style="background-color:#fff;"><label style="color:grey; for="lieu_n" class="formbold-form-label">Lieu_naissance:</label>
                <input type="text" name="lieu_n" id="lieu_n" value="'.$lieu_n.'" class="formbold-form-input"/></td>
                <td style="background-color:#fff;"><label style="color:grey; for="phone" class="formbold-form-label"> Telephone: </label>
                <input type="text" name="phone" id="phone" value="'.$phone.'" class="formbold-form-input"/></td>
                </tr>
                <tr>
                <td style="background-color:#fff;"><label style="color:grey; for="adresse" class="formbold-form-label"> Adresse: </label>
                <input type="text" name="adresse" id="adresse" value="'.$adresse.'" class="formbold-form-input"/></td>
                <td style="background-color:#fff;"><label style="color:grey; for="email" class="formbold-form-label"> E-mail: </label>
                <input type="text" name="email" id="email" value="'.$email.'" class="formbold-form-input"/></td>
                <td style="background-color:#fff;"><label style="color:grey; for="ccp" class="formbold-form-label"> NSS: </label>
                <input type="text" name="nss" id="ccp" value="'.$nss.'" class="formbold-form-input"/></td>
                </tr>
                </table>
                <button type="submit" name="modifier">Modifier</button>
                <button type="button" onclick="closePopup()">Annuler</button>
            </form>
        </div>';
    }

}
if(isset($_POST['modifier'])) {
    $id =  $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_n = $_POST['date_n'];
    $lieu_n = $_POST['lieu_n'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];
    $nss = $_POST['nss'];
    

    if($nom != null){
        $sql1 = "UPDATE employees SET nom = ? where id_employees = ?";
        $stmt1 = $con->prepare($sql1);
        if (!$stmt1) {
         // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt1->bind_param("sd", $nom, $id);
        if (!$stmt1->execute()) {
        // Execute failed
        echo "Error: " . $stmt1->error;
        exit;
       } }
        
    if($prenom != null){
        $sql2 = "UPDATE employees SET  prenom= ? where id_employees = ?";
        $stmt2 = $con->prepare($sql2);
        if (!$stmt2) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt2->bind_param("sd", $prenom, $id);
         if (!$stmt2->execute()) {
         // Execute failed
         echo "Error: " . $stmt2->error;
         exit;
        } }
     
    if($date_n != null){
        $sql3 = "UPDATE employees SET  date_n= ? where id_employees = ?";
        $stmt3 = $con->prepare($sql3);
        if (!$stmt3) {
        // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt3->bind_param("ss", $date_n, $id);
        if (!$stmt3->execute()) {
        // Execute failed
        echo "Error: " . $stmt3->error;
        exit;
       }     }

    

    if($lieu_n != null){
        $sql4 = "UPDATE employees SET lieu_n = ? where id_employees = ?";
        $stmt4 = $con->prepare($sql4);
        if (!$stmt4) {
         // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt4->bind_param("sd", $lieu_n, $id);
        if (!$stmt4->execute()) {
        // Execute failed
        echo "Error: " . $stmt4->error;
        exit;
       } }
        
    if($phone != null){
        $sql5 = "UPDATE employees SET  phone= ? where id_employees = ?";
        $stmt5 = $con->prepare($sql5);
        if (!$stmt5) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt5->bind_param("sd", $phone, $id);
         if (!$stmt5->execute()) {
         // Execute failed
         echo "Error: " . $stmt5->error;
         exit;
        } }
     
    if($email != null){
        $sql6 = "UPDATE employees SET  email= ? where id_employees = ?";
        $stmt6 = $con->prepare($sql6);
        if (!$stmt6) {
        // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt6->bind_param("sd", $email, $id);
        if (!$stmt6->execute()) {
        // Execute failed
        echo "Error: " . $stmt6->error;
        exit;
       }   }  

    
       if($adresse != null){
        $sql7 = "UPDATE employees SET adresse = ? where id_employees = ?";
        $stmt7 = $con->prepare($sql7);
        if (!$stmt7) {
         // Prepare failed
        echo "Error: " . $con->error;
        exit;
        }
        $stmt7->bind_param("sd", $adresse, $id);
        if (!$stmt7->execute()) {
        // Execute failed
        echo "Error: " . $stmt7->error;
        exit;
       } }
        
    if($nss != null){
        $sql8 = "UPDATE employees SET  nss= ? where id_employees = ?";
        $stmt8 = $con->prepare($sql8);
        if (!$stmt8) {
         // Prepare failed
         echo "Error: " . $con->error;
         exit;
         }
         $stmt8->bind_param("sd", $nss, $id);
         if (!$stmt8->execute()) {
         // Execute failed
         echo "Error: " . $stmt8->error;
         exit;
        } }
    
    }        



?>
<script>
function openPopupConfirmation() {
    document.getElementById('confirmation').style.display = 'block';
}



function closePopup() {
    document.getElementById('confirmation').style.display = 'none';
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