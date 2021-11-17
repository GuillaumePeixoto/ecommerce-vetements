
    <div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php if($admin_page == 'boutique' ){echo 'active';} ?>" aria-current="page" href="<?= URL ?>admin/gestion_boutique.php">
                    <i class="bi bi-bag"></i>
                    Gestion boutique
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($admin_page == 'user' ){echo 'active';} ?>" href="<?= URL ?>admin/gestion_user.php">
                    <i class="bi bi-person-circle"></i>
                    Gestion user
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($admin_page == 'commande' ){echo 'active';} ?>" href="<?= URL ?>admin/gestion_commande.php">
                    <i class="bi bi-bag-check"></i>
                    Gestion commande
                    </a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">