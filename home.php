<?php
session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $profile = $_SESSION["profile"];
    session_write_close();
} else {
    // user is not-logged-in
    // so clear all session variables and redirect user to index
    session_unset();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
}

?>
<HTML>
<HEAD>
<TITLE>Welcome</TITLE>
<link href="assets/css/style.css" type="text/css"
	rel="stylesheet" />
<link href="assets/css/registration.css" type="text/css"
	rel="stylesheet" />
</HEAD>
<BODY>
	<div class="container">
		<div class="page-header">
			<span class="login-signup"><a href="logout.php">Logout</a></span>
		</div>
		<div class="page-content">
            Welcome <?php echo $username;?>
            <div><img src="<?php echo  'vendor/img/profiles/' . $profile; ?>" alt="" /></div>
        </div>
	</div>
</BODY>
</HTML>
