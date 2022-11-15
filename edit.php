<?php
error_reporting(E_ERROR);
include('config.php');
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
	<link rel="stylesheet" href="css/milligram.min.css">
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
		<hr style="margin-bottom: 1em;">
		<button class="button button-outline" onclick="location.href='index.php'">Back</button>
		<?php
		function Read()
		{
			global $csv_file;
			echo file_get_contents($csv_file);
		}
		function Write()
		{
			global $csv_file;
			$data = $_POST["text"];
			file_put_contents($csv_file, $data);
		}
		?>
		<?php
		if (isset($_POST["save"])) {
			Write();
			echo "<script>
            popup('Changes have been saved');
            </script>";
		};
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div>
				<textarea name="text"><?php Read(); ?></textarea>
			</div>
			<button type="submit" name="save">Save</button>
		</form>
		<div style="margin-bottom: 1em;">
			<?php echo $footer; ?>
		</div>
	</div>
</body>

</html>