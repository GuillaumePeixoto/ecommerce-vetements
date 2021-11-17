<?php
    require_once 'inc/init.inc.php';

    // Si il est déja connecter il n'a rien a faire ici
    if(!connect())
    {
        header('location: connexion.php');
    }


    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
?>



<!-- tenter d'afficher 'Bonjour pseudo' sur la page en passant par la session -->

<h1 class="text-center my-5">Bonjour <span class="text-danger"><?= $_SESSION['user']['pseudo'] ?></span></h1>

<!-- Réaliser une page profil affichant les données personnelle de l'utilisateur stockées dans le fichier session avec le design de votre choix -->

<?php 
    $commandes = $bdd->query('SELECT * FROM commande WHERE user_id = '.$_SESSION['user']['id_user']);
    if($commandes->rowCount())
    {
        echo "<div class='d-flex mb-3'><a class='mx-auto btn btn-success' href='".URL."validation_commande.php'>Mes commandes</a></div>";
    }

?>
<div class="card mx-auto col-5 mb-5 ">
  <img src="https://picsum.photos/350/350" class="card-img-top w-75 rounded-circle mx-auto p-3" alt="...">
  <div class="card-body p-0 mt-2">
    <table class="table text-center rounded mb-0">
        <?php
            foreach($_SESSION['user'] as $index => $value)
            {
                if($index != 'id_user' && $index != 'statut') 
                {
                    echo "<tr><td><strong>".ucfirst(str_replace('_',' ',$index))."</strong></td><td>$value</td></tr>";
                }
            }
        ?>
    </table>
  </div>
</div>


<?php
    require_once 'inc/footer.inc.php';
?> 