<header>
    <?php 
        // By Henry Newton
    ?>
    <nav class="navbar navbar-light">
        <a class="navbar-brand" href="?command=hostMain">DashMeet</a>
        <form class="form-inline">
            <a href="?command=logout" class="btn btn-success">
                <?php echo $email;?>
                Logout
            </a>
            <a class="account" href="?command=account">
                <img src="images/account.png" alt="account icon">
            </a>
        </form>
    </nav>
</header>