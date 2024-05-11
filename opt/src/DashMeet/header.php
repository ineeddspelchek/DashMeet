<header>
    <?php 
        // By Henry Newton
    ?>
    <nav class="navbar navbar-light">
        <a class="navbar-brand mb-0 h1" href="?command=account">DashMeet</a>
        <form class="form-inline">
            <span class="align-middle">
                <?=$email?>
            </span>
            <a href="?command=logout" class="btn btn-success">
                Logout
            </a>
            <button class="btn btn-outline-dark btn-sm">
                <a class="account" href="?command=account">
                    <img src="images/account.png" alt="account icon">
                </a>
            </button>
        </form>
    </nav>
</header>