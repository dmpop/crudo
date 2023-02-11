<?php
include('inc/config.php');
include 'inc/parsedown.php';
if ($protect) {
	require_once('protect.php');
}

if (!file_exists($root_dir)) {
	mkdir($root_dir, 0755, true);
}

function deleteDirectory($dir)
{
	if (!file_exists($dir)) {
		return true;
	}
	if (!is_dir($dir)) {
		return unlink($dir);
	}
	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') {
			continue;
		}

		if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
			return false;
		}
	}
	return rmdir($dir);
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Author: Dmitri Popov, dmpop@linux.com
         License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="favicon.png" />
	<link rel="shortcut icon" href="favicon.png" />
	<link rel="stylesheet" href="css/lit.css">
	<link rel="stylesheet" href="css/styles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Suppress form re-submit prompt on refresh -->
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>
</head>

<body>
	<div class="c">
		<div style="text-align: center;">
			<div style="margin-top: 1em; margin-bottom: 1em;">
				<img style="display: inline; height: 3em; vertical-align: middle; margin-right: 0.5em;" src="favicon.svg" alt="logo" />
				<h1 style="display: inline; margin-top: 0em; vertical-align: middle;"><?php echo $title; ?></h1>
			</div>
			<div class="card w-100">
				<?php
				// Start session
				if (session_status() == PHP_SESSION_NONE) {
					session_start();
				}
				// The $d parameter is used to detect a subdirectory
				if (isset($_GET['d'])) {
					$current_dir = $_GET['d'];
				} else {
					$current_dir = $root_dir;
				}
				$_SESSION["dir"] = $current_dir;
				$sub_dirs = array_filter(glob($current_dir . DIRECTORY_SEPARATOR . '*'), 'is_dir');
				// Generate sub-directory navigation
				if ((count($sub_dirs)) > 0 or (!empty($current_dir))) {
					$higher_dirs = explode("/", $current_dir);
					$higher_dir_cascade = "";
					foreach ($higher_dirs as $higher_dir) {
						if (!empty($higher_dir)) {
							if (!empty($higher_dir_cascade)) {
								$higher_dir_cascade = $higher_dir_cascade . DIRECTORY_SEPARATOR;
							}
							$higher_dir_cascade = $higher_dir_cascade . $higher_dir;
							echo "<a href='"  . basename($_SERVER['PHP_SELF']) . "?d=" . $higher_dir_cascade . "'>" . $higher_dir . "</a> /&nbsp;";
						}
					}
					// Populate a drop-down list with subdirectories
					echo '<select class="card w-50" name="" onchange="javascript:location.href = this.value;">';
					echo '<option value="Default">Select table</option>';
					foreach ($sub_dirs as $dir) {
						setlocale(LC_ALL, 'C.UTF-8');
						$dir_name = basename($dir);
						$dir_option = str_replace('\'', '&apos;', $current_dir . DIRECTORY_SEPARATOR . $dir_name);
						echo "<option value='?d=" . ltrim($dir_option, '/') . "'>" . $dir_name . "</option>";
					}
					echo "</select>";
				}
				?>
			<form style='display: inline;' method='POST' action=''>
				<input class="card w-50" style='display: inline; width: 9em; margin-left: 0.5em;' type='text' name='table'>
				<input class="btn primary" style='display: inline; margin-left: 0.5em; margin-right: 0.5em;' type='submit' name='add' value='Add'>
			</form>
			</div>
			<div class="card">
				<table style="margin-top: 1em;" id="theTable">
					<?php
					// Read CSV file
					$csv_file = $current_dir . DIRECTORY_SEPARATOR . "data.csv";
					if (!is_file($csv_file)) {
						$init_data = "Column 1; Column 2; Column 3\nField 1; Field 2; Field 3\n";
						file_put_contents($csv_file, $init_data);
					}
					$row = 1;
					if (($handle = fopen($csv_file, "r")) !== FALSE) {
						while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
							$num = count($data);
							$Parsedown = new Parsedown();
							if ($row == 1) {
								echo '<thead><tr>';
							} else {
								echo '<tr>';
							}
							for ($c = 0; $c < $num; $c++) {
								if (empty($data[$c])) {
									$value = "&nbsp;";
								} else {
									$value = $data[$c];
								}
								if ($row == 1) {
									echo '<th "sortable" onclick="sortTable(' . $c . ')">' . $Parsedown->text($value) . '</th>';
								} else {
									echo '<td class="text-left">' . $Parsedown->text($value) . '</td>';
								}
							}
							if ($row == 1) {
								echo '</tr></thead><tbody>';
							} else {
								echo '</tr>';
							}
							$row++;
						}
						fclose($handle);
					} else {
						echo "<div style='margin-top: 1em; margin-bottom: 1.5em;'>So empty here. Press the <strong>Edit</strong> button to add tables.</div>";
					}
					if (isset($_POST["add"]) && !empty($_POST["table"])) {
						// Create new directory
						mkdir($current_dir . DIRECTORY_SEPARATOR . $_POST["table"], 0755, true);
						ob_start();
						while (ob_get_status()) {
							ob_end_clean();
						}
						$url = "index.php?d=" . $current_dir . DIRECTORY_SEPARATOR . $_POST['table'];
						header("Location:$url");
					}
					if (isset($_POST["delete"])) {
						// Remove existing directory
						echo "<div class='card'>Do you really want to delete this table? <form method='POST' action=''><input class='btn primary' type='submit' name='confirm' value='Yes'></form></div>";
					}
					if (isset($_POST["confirm"])) {
						deleteDirectory($current_dir);
						ob_start();
						while (ob_get_status()) {
							ob_end_clean();
						}
						$url = "index.php?d=" . $root_dir;
						header("Location:$url");
					}
					?>
					</tbody>
				</table>
			</div>
			<form style='display: inline;' method='POST' action=''>
				<input class="btn" style='display: inline; margin-right: 0.5em;' type='submit' name='delete' value='Delete'>
			</form>
			<button class="btn primary" style="margin-top: 1.5em; margin-bottom: 1.5em;" onclick='window.location.href = "edit.php"'>Edit</button>
			<div class="card">
				<?php echo $footer; ?>
			</div>
		</div>
		<script>
			function sortTable(n) {
				var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
				table = document.getElementById("theTable");
				switching = true;
				dir = "asc";
				while (switching) {
					switching = false;
					rows = table.rows;
					for (i = 1; i < (rows.length - 1); i++) {
						shouldSwitch = false;
						x = rows[i].getElementsByTagName("TD")[n];
						y = rows[i + 1].getElementsByTagName("TD")[n];
						if (dir == "asc") {
							if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
								shouldSwitch = true;
								break;
							}
						} else if (dir == "desc") {
							if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
								shouldSwitch = true;
								break;
							}
						}
					}
					if (shouldSwitch) {
						rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						switching = true;
						switchcount++;
					} else {
						if (switchcount == 0 && dir == "asc") {
							dir = "desc";
							switching = true;
						}
					}
				}
			}
		</script>
	</div>
</body>

</html>