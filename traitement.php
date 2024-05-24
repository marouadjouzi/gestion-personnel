<?php
$nom_prenom = '';
$type_conge = '';
$fonction = '';
$affectation = '';
$periode = '';
$reprise = '';
$nbr = '';
$annee_en_cours = date('Y');
$exercice = ($annee_en_cours-1) . '/' . $annee_en_cours;


if(isset($_POST['imprimer'])){
    $nom_prenom = $_POST['nom'];
    $type_conge = $_POST['type'];
    $fonction = $_POST['fonction'];
    $affectation = $_POST['affectation'];
    $periode = $_POST['periode'];
    $reprise = $_POST['reprise'];
    $nbr = $_POST['nbr'];
    $annee_en_cours = date('Y');
    $exercice = ($annee_en_cours-1) . '/' . $annee_en_cours;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre de conge</title>
</head>
<body>


<div class="haut">
  <img src="baosem_logo.png" alt="">
  <p>Direction Generale</p>
  <p>Departement Ressources Humaines</p>
  <p>Alger, le <?php echo date('d-m-y');?></p>
</div>
    <div class="box">
      <h1 style="margin-top:30px;">Titre de conge</h1>
      <h1 style="margin-bottom:30px;">Exercice(<?php echo $exercice;?>)</h1>
     </div>
    <div class="formbold-main-wrapper">
  <!-- Author: FormBold Team -->
  <!-- Learn More: https://formbold.com -->
  <div class="formbold-form-wrapper">
    <form  method="POST">
      <div class="formbold-mb-5">
        <label for="name" class="formbold-form-label">Nom-Prenom: </label>
        <input
          type="text"
          name="name"
          id="name"
          value="<?php echo $nom_prenom;?>"
          class="formbold-form-input"
          readonly
        />
      </div>
      <div class="formbold-mb-5">
        <label for="phone" class="formbold-form-label"> Fonction: </label>
        <input
          type="text"
          name="phone"
          id="phone"
          value="<?php echo $fonction;?>"
          class="formbold-form-input"
          readonly
        />
      </div>
      <div class="formbold-mb-5">
        <label for="email" class="formbold-form-label"> Affectation: </label>
        <input
          type="email"
          name="email"
          id="email"
          value="<?php echo $affectation;?>"
          class="formbold-form-input"
          readonly
        />
      </div>
      <div class="flex flex-wrap formbold--mx-3">
        <div class="w-full sm:w-half formbold-px-3">
          <div class="formbold-mb-5 w-full">
            <label for="date" class="formbold-form-label"> Droit au conge: </label>
            <input
              type="text"
              name="date"
              id="date"
              value="<?php echo $type_conge;?>"
              class="formbold-form-input"
              readonly
            />
          </div>
        </div>
        <div class="w-full sm:w-half formbold-px-3">
          <div class="formbold-mb-5">
            <label for="time" class="formbold-form-label"> NOMBRE DE JOURS PRIS: </label>
            <input
              type="text"
              name="time"
              id="time"
              value="<?php echo $nbr;?>"
              class="formbold-form-input"
              readonly
            />
          </div>
        </div>
      </div>
      <div class="formbold-mb-5">
        <label for="name" class="formbold-form-label">Periode: </label>
        <input
          type="text"
          name="name"
          id="name"
          value="<?php echo $periode;?>"
          class="formbold-form-input"
          readonly
        />
      </div>
      <div class="formbold-mb-5">
        <label for="name" class="formbold-form-label">Date de reprise: </label>
        <input
          type="text"
          name="name"
          id="name"
          value="<?php echo $reprise;?>"
          class="formbold-form-input"
          readonly
        />
      </div>
      
     

      <div style="display:flex;">
        <button class="formbold-btn" id="btn" onclick="imprimerPage()">Imprimer</button>
        <a class="formbold-btn" id="btn" style="text-decoration:none; color:#fff;" href="conge.php" >Retour</a></button>
      </div>
    </form>
  </div>
</div>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  body {
    font-family: "Inter", Arial, Helvetica, sans-serif;
  }
  img{
    width: 183px;
  }
  p{
    margin-left:22px;
  }
  .box{
    border: 3px solid;
    width: 100%;
    margin-top: 40px;
  }
  .box h1{
    margin-left: 30%;
  }
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
  .formbold-form-label-2 {
    font-weight: 600;
    font-size: 20px;
    margin-bottom: 20px;
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
    margin-right: 30px;
    font-size: 16px;
    border-radius: 6px;
    padding: 14px 32px;
    border: none;
    font-weight: 600;
    background-color: #6a64f1;
    color: white;
    width: 100%;
    cursor: pointer;
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
  
    <script>
        function imprimerPage() {
        var currentUrl = window.location.href;
        document.getElementById('btn').style.display = 'none';
        window.print();
        setTimeout(function() {
            window.location.href = currentUrl;
        }, 1000);
    }

    function retour(){
      location.href = 'conge.php';
    }
    </script>
</body>
</html>