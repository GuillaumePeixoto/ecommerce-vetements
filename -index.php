<?php

    require_once 'inc/init.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';

    $lasts_products = $bdd->prepare('select * from article ORDER BY id_article DESC LIMIT 6');
    $lasts_products->execute();
    $prods = $lasts_products->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_COOKIE['article_history']))
    {
        $json_array = json_decode($_COOKIE['article_history'], true);
        $array = array_slice($json_array, -3, 3, true);
        $article_history = implode( ', ',$array);
        $lasts_products_see = $bdd->query("select * from article WHERE id_article IN ($article_history)");
        // $lasts_products_see->bindValue(':article_history',$article_history,PDO::PARAM_STR);
        // $lasts_products_see->execute();
        $last_prods_see = $lasts_products_see->fetchAll(PDO::FETCH_ASSOC);
    }

?>

            <h1 class="text-center my-5">Site demo Ecommerce</h1>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore asperiores dicta nobis eos sapiente cupiditate eaque voluptatum praesentium doloremque tempora! Blanditiis pariatur esse voluptas nam harum id cum laboriosam asperiores reprehenderit atque aliquam, natus maxime, impedit dolor ab suscipit, vel repellendus quidem perferendis. Quod laboriosam fugit iusto animi ducimus, error ipsam expedita. Ipsa doloremque deleniti in illo accusamus doloribus unde perferendis, magnam ipsum itaque, culpa iure tempora facilis? Deserunt magnam corporis inventore dolor culpa exercitationem facilis magni consequatur? Eveniet ea ad dolor explicabo perferendis, saepe illum architecto natus voluptates veritatis, sint delectus placeat quibusdam asperiores doloremque doloribus voluptas assumenda deserunt.</p>

            <h2 class="text-center my-5">Nouveautés</h2>

            <div class="container d-flex flex-wrap justify-content-around my-5">
                <?php
                    foreach($prods as $prod)
                    {
                        echo "<a href='fiche_produit.php?id_article=$prod[id_article]' class='liens-nouveautes m-2 img-nouveautes col-3 shadow-sm rounded d-flex'><img src='$prod[photo]' class='w-100 align-self-center' alt='$prod[titre]'></a>";
                    }
                ?>
            </div>
            
            <?php if(isset($_COOKIE['article_history'])){ ?>
                <h2 class="text-center my-5">Derniers articles consultés</h2>

                <div class="container d-flex flex-wrap justify-content-around my-5">
                <?php
                        foreach($last_prods_see as $last_prod)
                        {
                            echo "<a href='fiche_produit.php?id_article=$last_prod[id_article]' class='liens-nouveautes m-2 img-nouveautes col-3 shadow-sm rounded d-flex'><img src='$last_prod[photo]' class='w-100 align-self-center' alt='$last_prod[titre]'></a>";
                        }
                    ?>
                </div>
                    <?php } ?>

<?php
    require_once 'inc/footer.inc.php';
?>
