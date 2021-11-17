<?php
    require_once '../inc/init.inc.php';

    // Si l'internaute n'est pas admin, il n'a rien a faire sur cette page
    if(!adminConnect())
    {
        header('location: '.URL.'connexion.php');
    }

    if(isset($_GET['action']) && $_GET['action'] == 'suppression')
    {
        if(isset($_GET['id_article']) && !empty($_GET['id_article']))
        {
            $supprimer = $bdd->prepare('DELETE FROM article where id_article = :id_article');
            $supprimer->bindValue(':id_article',$_GET['id_article'],PDO::PARAM_INT);
            $supprimer->execute();
            $sup_success = '<p class="alert bg-success mx-auto mt-2 col-5 text-center"> Suppression du produit n°<strong>'.$_GET['id_article'].'</strong>&nbsp;réalisé avec succès !</p>';
            $_GET['action'] = 'affichage';
        }
        else
        {
            header('location:'.URL.'admin/gestion_boutique.php?action=affichage');
        }
    }

    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        if(isset($_GET['id_article']) && !empty($_GET['id_article']))
        {
            $produitActuel = $bdd->prepare("select * from article where id_article = :id_article");
            $produitActuel->bindValue(':id_article',$_GET['id_article'],PDO::PARAM_INT);
            $produitActuel->execute();
            // Si la requete retourne au moins 1 résultat, cela veux dire que l'id du produit est connu en BDD
            if($produitActuel->rowCount())
            {
                $product = $produitActuel->fetch(PDO::FETCH_ASSOC);

                // on stock chaque donnée du produit a modifier dans chaque variable
                $id_produit = (isset($product['id_article'])) ? $product['id_article'] : '';
                $reference = (isset($product['reference'])) ? $product['reference'] : '';
                $categorie = (isset($product['categorie'])) ? $product['categorie'] : '';
                $titre = (isset($product['titre'])) ? $product['titre'] : '';
                $description = (isset($product['description'])) ? $product['description'] : '';
                $couleur = (isset($product['couleur'])) ? $product['couleur'] : '';
                $taille = (isset($product['taille'])) ? $product['taille'] : '';
                $sexe = (isset($product['sexe'])) ? $product['sexe'] : '';
                $photo = (isset($product['photo'])) ? $product['photo'] : '';
                $prix = (isset($product['prix'])) ? $product['prix'] : '';
                $stock = (isset($product['stock'])) ? $product['stock'] : '';


            }
            else
            {
                header('location:'.URL.'admin/gestion_boutique.php?action=affichage');
            }
        }
        else
        {
            header('location:'.URL.'admin/gestion_boutique.php?action=affichage');
        }
    }


    if(isset($_POST['reference'], $_POST['categorie'], $_POST['titre'], $_POST['description'], $_POST['couleur'], $_POST['taille'], $_POST['sexe'], $_POST['prix'], $_POST['stock']))
    {
        // Traitement de fichier uploader
        if(!empty($_FILES['photo']['name']))
        {
            // On renomme l'image en concatenant la références saisie dans le formulaire et le nom de l'image d'origine piochée dans le $_FILES
            $nomPhoto = $_POST['reference'].'-'.$_FILES['photo']['name'];


            // on définit l'URL de l'image qui sera stockée en BDD
            $photoBdd = URL.'assets/img/'.$nomPhoto;

            // On définit le chemin physique de l'image qui sera copié dans le dossier
            $photoDossier = RACINE_SITE.'assets/img/'.$nomPhoto;

            // copy() fonction prédéfini permettant de copier un fichier uploadé
            // Argument : 1er : le nom temporaire du fichier | 2e : le chemin physique de l'image sur le serveur
            copy($_FILES['photo']['tmp_name'], $photoDossier);
        }else{
            if(isset($photo))
            {
                $photoBdd = $photo;
            }
            else
            {
                $photoBdd = '';
            }
            
        }
        // Exo : réaliser le traitement PHP + SQL permettant d'insérer un nouveau produit dans la BDD à la validation du formulaire ( prepare + bindvalue + execute)
        if(!empty($_POST['reference']) && !empty($_POST['categorie']) && !empty($_POST['titre']) &&  !empty($_POST['description']) && !empty($_POST['couleur']) && !empty($_POST['taille']) && !empty($_POST['sexe']) && !empty($_POST['prix']))
        {
            if(isset($_GET['action']) && $_GET['action'] == 'ajout')
            {
                $requete = $bdd->prepare("insert into article values(null,:reference,:categorie,:titre,:description,:couleur,:taille,:sexe,:photo,:prix,:stock)");
            }
            elseif(isset($_GET['action']) && $_GET['action'] == 'modification')
            {
                $requete = $bdd->prepare("UPDATE article SET reference = :reference, categorie = :categorie, titre = :titre ,description = :description, couleur = :couleur, taille = :taille, sexe = :sexe, photo = :photo, prix = :prix, stock = :stock WHERE id_article = :id_article");
                $requete->bindValue(':id_article',$_GET['id_article'],PDO::PARAM_INT);
            }
            $requete->bindValue(':reference',$_POST['reference'],PDO::PARAM_STR);
            $requete->bindValue(':categorie',$_POST['categorie'],PDO::PARAM_STR);
            $requete->bindValue(':titre',$_POST['titre'],PDO::PARAM_STR);
            $requete->bindValue(':description',$_POST['description'],PDO::PARAM_STR);
            $requete->bindValue(':couleur',$_POST['couleur'],PDO::PARAM_STR);
            $requete->bindValue(':taille',$_POST['taille'],PDO::PARAM_STR);
            $requete->bindValue(':sexe',$_POST['sexe'],PDO::PARAM_STR);
            $requete->bindValue(':photo',$photoBdd,PDO::PARAM_STR);
            $requete->bindValue(':prix',$_POST['prix'],PDO::PARAM_STR);
            $requete->bindValue(':stock',$_POST['stock'],PDO::PARAM_INT);
            $requete->execute();

            if(isset($_GET['action']) && $_GET['action'] == 'ajout')
            {
                $msg = "L'article <strong>$_POST[titre]</strong> référence <strong>$_POST[reference]</strong> a bien été enregistré.";
            }
            elseif(isset($_GET['action']) && $_GET['action'] == 'modification')
            {
                $msg = "L'article <strong>$_POST[titre]</strong> référence <strong>$_POST[reference]</strong> a bien été modifié.";
            }
        }

    }

    $admin_page = 'boutique';

    require_once '../inc/admin_inc/header.inc.php';
    require_once '../inc/admin_inc/nav.inc.php';

            // echo $nomPhoto.'<br>'; 
            // echo $photoBdd.'<br>';
            // echo $photoDossier.'<br>';

?>

<!-- 
    1. Réaliser le traitement SQL permettant d'afficher le nombre d'article de la table article sous forme de tableau HTML
    2. Prévoir un lien modification / delete pour chaque produit
    3. Faites en sorte d'afficher une partie de la description (50 caractère ) si la taille de la description est supérieur a 50
    4. L'image doit apparaite et non l'URL
    5. Au dessus du tableau afficher le nombre d'article en BDD


 -->

<div class="d-flex col-md-3 mx-auto flex-column mt-2">
    <a href="?action=affichage" class="btn btn-outline-primary">Liste des articles</a>
    <a href="?action=ajout" class="btn btn-outline-info mt-2">Ajout d'un article</a>
    <a href="" class="btn btn-outline-success mt-2">Statistiques</a>
</div>

<?php if(isset($sup_success)){echo $sup_success;}?>

<?php
    if(isset($_GET['action']) && $_GET['action'] == 'affichage')
    {
?>

        <hr><h2 class="text-center"> Liste des article </h2><hr>

        <?php
            $affiche = $bdd->prepare("select * from article");
            $affiche->execute();
            $articles = $affiche->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <span>Il y a <span class="text-white bg-success badge "><?= $affiche->rowCount(); ?></span> article(s)</span>
        <div class="table-responsive">
            <table id="table-backoffice" class="table mt-2 table-bordered">
            <thead style="border-bottom: 2px solid black"><tr>
            <?php
            foreach($articles[0] as $index => $value)
            {
                if($index != 'id_article')
                {
                    echo "<th class='text-center'>$index</th>";
                }
                
            }
            echo "<th class='text-center'>Modifier</th><th class='text-center'>Supprimer</th></tr></thead><tbody class='border border-dark'>";
            foreach($articles as $article)
            {
                echo "<tr>";
                foreach($article as $index => $value)
                {
                    if($index != 'id_article')
                    {
                        if($index == 'photo')
                        {
                            echo "<td class='text-center align-middle'><img height='100px' src='$value'></td>";
                        }
                        elseif($index == 'description')
                        {
                            if(strlen($value) > 50)
                            {
                                echo "<td class='text-center align-middle'>".substr($value, 0, 50)."...</td>";
                            }
                            else
                            {
                                echo "<td class='text-center align-middle'>$value</td>";
                            }
                            
                        }
                        else
                        {
                            echo "<td class='text-center align-middle'>$value</td>";
                        }
                    }
                }
                ?>
                <td class='align-middle '><a class='bg-success m-0 rounded d-flex text-center justify-content-center text-decoration-none' href='?action=modification&id_article=<?= $article['id_article']?>'><i class='bi bi-pencil-square  p-3 fs-2 d-flex mx-0 text-white'></i></a></td>
                <td class='align-middle '><a class='bg-danger m-0 rounded d-flex text-center justify-content-center text-decoration-none' href='?action=suppression&id_article=<?= $article['id_article']?>' onclick="return(confirm('Voulez vous réelement supprimer ce produit ?'))" ><i class='bi bi-trash p-3 fs-2 d-flex mx-0 text-white'></i></a></td>
                </tr>
                <?php } ?>
  
                </tbody>
            </table>
        </div>

<?php
    } // Ordre des priorités des conditions
    elseif(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification'))
    {
?>
<?php 
    if($_GET['action'] == 'ajout'){
        echo "<hr><h2 class='text-center'> Ajout d'un article </h2><hr>";
    }
    elseif($_GET['action'] == 'modification'){
        echo "<hr><h2 class='text-center'> Modification de l'article n°$_GET[id_article] </h2><hr>";
    }
?>


<?php if(isset($msg)){ echo "<p class='bg-success col-md-5 mx-auto p-3 text-center text-white mt-3 rounded'> $msg </p>"; }?> 

<!-- Réaliser un formulaire HTML correspondant à la table article -->

<?php // echo '<pre>'; print_r($_POST); echo '</pre>'; ?>

<!-- les données d'un fichier uploadé sont accessible en PHP via la superglobale $_FILES(Array) -->
<?php // echo '<pre>'; print_r($_FILES); echo '</pre>'; ?>

<!-- enctype="multipart/form-data" : attribut permettant de récupérer les informations d'un fichier uploadé via un formulaire -->
<form class="row g-3 mb-5" enctype="multipart/form-data" method="post">

                <div class="mb-1 col-md-4">
                    <label for="reference" class="form-label">Référence</label>
                    <input type="text" class="form-control " id="reference" name="reference" value="<?php if(isset($reference)){ echo $reference; }?>">
                </div>
                <div class="mb-1 col-md-4">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <input type="text" class="form-control " id="categorie"  name="categorie" value="<?php if(isset($categorie)){ echo $categorie; }?>">
                </div>
                <div class="mb-1 col-md-4">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>">
                </div>
                <div class="mb-1 col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea type="text" class="form-control" rows="10" id="description" name="description"><?php if(isset($description)){ echo $description; }?></textarea>
                </div>
                <div class="mb-1 col-4">
                    <label for="couleur" class="form-label">Couleur</label>
                    <input type="text" class="form-control" id="couleur" name="couleur" value="<?php if(isset($couleur)){ echo $couleur; }?>">
                </div>
                <div class="mb-1 col-4">
                    <label for="taille" class="form-label">Taille</label>
                    <input type="text" class="form-control" id="taille" name="taille" value="<?php if(isset($taille)){ echo $taille; }?>">
                </div>
                <div class="col-md-4">
                    <label for="sexe" class="form-label">Sexe</label>
                    <select type="text" class="form-control" id="sexe" name="sexe">
                        <option value="homme" <?php if(isset($taille) && $sexe == 'homme'){ echo 'selected'; }?> >Homme</otpion>
                        <option value="femme" <?php if(isset($taille) && $sexe == 'femme'){ echo 'selected'; }?> >Femme</option>
                        <option value="mixte" <?php if(isset($taille) && $sexe == 'mixte'){ echo 'selected'; }?> >Mixte</option>
                    </select>
                </div>
                <?php if(isset($photo)){ echo "<div class='mb-1 col-md-12 row'><h3 class='col-12 mt-1 text-center'>Photo Actuel :</h3><img src='$photo' class='col-4 mx-auto' alt='$titre'></div>"; }?>
                <div class="mb-1 col-md-4">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo">
                </div>
                <div class="mb-1 col-md-4">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="text" class="form-control" id="prix" name="prix" value="<?php if(isset($prix)){ echo $prix; }?>">
                </div>
                <div class="mb-1 col-md-4">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="text" class="form-control" id="stock" name="stock" value="<?php if(isset($stock)){ echo $stock; }?>">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-dark">
                        <?php 
                            if($_GET['action'] == 'ajout'){
                                echo "Ajouter";
                            }
                            elseif($_GET['action'] == 'modification'){
                                echo "Modifier";
                            }
                        ?>
                    </button>
                </div>
            </form>




<?php

    }
    require_once '../inc/admin_inc/footer.inc.php';
?>