<?php
error_reporting(E_ERROR);
include('inc/config.php');
include 'inc/parsedown.php';
if ($protect) {
	require_once('protect.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Author: Dmitri Popov, dmpop@linux.com
         License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="shortcut icon" href="favicon.png" />
	<link rel="stylesheet" href="css/picnic.min.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/popup.css">
	<script src="js/popup.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div style="text-align: center;">
		<div style="margin-top: 1em; margin-bottom: 1em;">
			<img style="display: inline; height: 3em; vertical-align: middle; margin-right: 0.5em;" src="favicon.svg" alt="logo" />
			<h1 style="display: inline; margin-top: 0em; vertical-align: middle;"><?php echo $title; ?></h1>
		</div>
		<div style="margin-bottom: 1.3em; margin-top: 1.3em;"><a href="index.php?d=<?php echo $_SESSION['dir']; ?>">Back</a></div>
		<?php
		function Read()
		{

			$csvfile = $_SESSION["dir"] . DIRECTORY_SEPARATOR . "data.csv";
			echo file_get_contents($csvfile);
		}
		function Write()
		{
			$csvfile = $_SESSION["dir"] . DIRECTORY_SEPARATOR . "data.csv";
			$data = $_POST["text"];
			file_put_contents($csvfile, $data);
		}
		?>
		<?php
		if (isset($_POST["save"])) {
			Write();
			echo "<script>";
			echo 'popup("Changes have been saved");';
			echo "</script>";
		};
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<textarea name="text"><?php Read(); ?></textarea><br /><br />
			<button style="margin-bottom: 1.5em;" type="submit" name="save">Save</button>
		</form>
		<div style="margin-bottom: 1em;">
			<?php echo $footer; ?>
		</div>
	</div>
</body>

</html>