<?php
$config = include('config.php');
$pw_hash = password_hash($password, PASSWORD_DEFAULT);

if ($protect) {
    session_start();
}

if (isset($_POST['password']) && password_verify($_POST['password'], $pw_hash)) {
    $_SESSION["password"] = $pw_hash;
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic">
    <link rel="stylesheet" href="css/styles.css">
    <title><?php echo $title; ?></title>
</head>

<body>
    <div style="text-align: center;">
        <div style="margin-top: 1em; margin-bottom: 1em;">
            <img style="display: inline; height: 3em; vertical-align: middle; margin-right: 0.5em;" src="favicon.svg" alt="logo" />
            <h1 style="display: inline; margin-top: 0em; vertical-align: middle;"><?php echo $title; ?></h1>
        </div>
        <form action="" method="POST">
            <label>Password:</label>
            <input style="width: 15em;" type="password" name="password"><br />
            <button style="margin-top: 1em; margin-bottom: 1 em;" type="submit" name="submit">Log in</button>
        </form>
        <?php echo $footer; ?>
    </div>
</body>

</html>