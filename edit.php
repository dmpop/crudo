<?php
include('config.php');
?>

<html lang='en'>
<!-- Author: Dmitri Popov, dmpop@linux.com
         License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="shortcut icon" href="favicon.png" />
	<link rel="stylesheet" href="css/milligram.min.css">
	<link rel="stylesheet" href="css/styles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div id="content">
		<h1 style="display: inline; margin-left: 0.19em; vertical-align: middle; letter-spacing: 3px; margin-top: 0em;"><?php echo $title; ?></h1>
		<hr>
		<form method="GET" action="index.php">
			<p><button type="submit">Back</button></p>
		</form>
		<?php
		function Read()
		{
			$CSVFILE = "data.csv";
			echo file_get_contents($CSVFILE);
		}
		function Write()
		{
			$CSVFILE = "data.csv";
			$fp = fopen($CSVFILE, "w");
			$data = $_POST["text"];
			fwrite($fp, $data);
			fclose($fp);
		}
		?>
		<?php
		if ($_POST["save"]) {
			if ($_POST['passwd'] != $password) {
				echo '<p>Wrong password.</p>';
				exit();
			}
			Write();
			echo '<div style="margin-bottom: 2em;">Changes have been saved</div>';
		};
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
			<div>
				<textarea name="text"><?php Read(); ?></textarea>
			</div>
			<div>
				<label for='password'>Password:</label>
			</div>
			<div>
				<input type="password" name="passwd" style="width: 90%; max-width: 75%;">
			</div>
			<input type="submit" name="save" value="Save">
		</form>
		<p><?php echo $footer; ?></p>
	</div>
</body>

</html>