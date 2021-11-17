<?php
    require_once 'inc/init.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';

    $categories = $bdd->prepare('select distinct categorie from article');
    $categories->execute();
    $categorie = $categories->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_GET['categorie']) && !empty($_GET['categorie']))
    {
        $prods_categorie = $bdd->prepare('select * from article where categorie = :categorie');
        $prods_categorie->bindValue(':categorie',$_GET['categorie'],PDO::PARAM_STR);
        $prods_categorie->execute();
        $prods = $prods_categorie->fetchAll(PDO::FETCH_ASSOC);
    }        
    else
    {
        $products = $bdd->prepare('select * from article');
        $products->execute();
        $prods = $products->fetchAll(PDO::FETCH_ASSOC);
    }

?>
<h1 class="text-center my-5">Shopping</h1>

<p class="my-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, labore. Dolor voluptatem nobis ea deleniti, sit possimus eligendi iure recusandae rem eius. Doloribus delectus quas, tempore rem laboriosam nesciunt pariatur velit, illum sint, necessitatibus ea eaque provident. Cupiditate alias repellat aliquid veniam quibusdam corrupti, non odit asperiores illo eligendi necessitatibus! Fugiat quo in provident minus ullam praesentium natus amet sequi delectus quia incidunt beatae rem, labore quisquam pariatur accusantium exercitationem enim suscipit consequatur dolorum animi commodi saepe? Eos quas, aliquid blanditiis officia ipsum natus ea. Porro officiis qui totam unde dignissimos nesciunt repudiandae possimus numquam pariatur placeat! Magnam et aperiam hic officiis? Veniam, laborum voluptate nemo, qui tempore voluptates sed at, suscipit facere sint totam eos beatae nam aperiam molestiae! Asperiores non officia cupiditate itaque sapiente fuga earum illo quibusdam? Adipisci quia aliquid laboriosam saepe, dignissimos eos expedita molestiae quaerat nisi quae ratione provident, optio ad. Recusandae iure hic culpa!</p>

<!-- Exo :
1. Réaliser le traitement PHP + SQL permettant de sélectionner les catégories d'articles disctinct dans la BDD
2. Afficher dynamiquement les catégories dans l'accordéon ci dessous (boucle + fetch)
3. Faites en sorte d'envoyé le nom de catégorie dans l'URL lorseque l'on clique sur le lien
4. Afficher les articles en fonction de la catégories choisi



 -->

    <div class="accordion col-12 col-sm-10 col-md-4 col-lg-3 col-xl-3 mx-auto my-5" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Catégories
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <p class='my-2'><a href='?' class='alert-link text-dark'>Aucune</a></p>
                    <?php
                        foreach($categorie as $value)
                        {
                            echo "<p class='my-2'><a href='?categorie=$value[categorie]' class='alert-link text-dark'>$value[categorie]</a></p>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <?php
            foreach($prods as $product)
            {
                if(strlen($product['description']) > 50)
                {
                    $description = substr($product['description'], 0, 50)."...";
                }
                else
                {
                    $description = $product['description'];
                }
                echo "<div class='col-4 d-flex'>
                        <div class='card shadow-sm rounded my-auto h-100 w-100'>
                            <a href='fiche_produit.php?id_article=$product[id_article]' class='h-75 d-flex'><img src='$product[photo]' class='card-img-top my-auto img-fluid' style='max-height: 100%' alt='$product[titre]'></a>
                            <div class='card-body d-flex flex-column justify-content-center w-100'>
                                <h5 class='card-title text-center'><a href='fiche_produit.php?id_article=$product[id_article]' class='alert-link text-dark titre-produit-boutique'>$product[titre]</a></h5>
                                <p class='card-text  text-center'>$description</p>
                                <p class='card-text fw-bold  text-center'>$product[prix] €</p>
                                <p class='card-text text-center'><a href='fiche_produit.php?id_article=$product[id_article]' class='btn btn-outline-dark'>En savoir plus</a></p>
                            </div>
                        </div>
                    </div>";

            }
        ?>  
    </div>

<?php
    require_once 'inc/footer.inc.php';
?>
 