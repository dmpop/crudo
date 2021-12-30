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
	<link rel="stylesheet" href="css/classless.css" />
	<link rel="stylesheet" href="css/themes.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		textarea {
			font-size: 15px;
			width: 100%;
			height: 15em;
			line-height: 1.9;
			margin-top: 1em;
		}
	</style>
</head>

<body>
	<div class="card text-center">
		<div style="margin-top: 1em; margin-bottom: 1em;">
			<img style="display: inline; height: 2.5em; vertical-align: middle;" src="favicon.svg" alt="logo" />
			<h1 style="display: inline; margin-top: 0em; vertical-align: middle; letter-spacing: 3px;"><?php echo $title; ?></h1>
		</div>
		<hr style="margin-bottom: 1em;">
		<button onclick="location.href='index.php'">Back</button>
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
		if (isset($_POST["save"])) {
			if ($_POST['password'] != $password) {
				echo '<p>Wrong password.</p>';
				exit();
			}
			Write();
			echo '<div style="margin-bottom: 2em;">Changes have been saved</div>';
		};
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div>
				<textarea name="text"><?php Read(); ?></textarea>
			</div>
			<div>
				<label for='password'>Password:</label>
			</div>
			<div>
				<input type="password" name="password">
			</div>
			<button type="submit" name="save">Save</button>
		</form>
	</div>
	<div class="text-center">
		<?php echo $footer; ?>
	</div>
</body>

</html>