<?php
include('config.php');
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<!-- Author: Dmitri Popov, dmpop@linux.com
         License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/classless.css" />
	<link rel="stylesheet" href="css/themes.css" />
	<!-- Suppress form re-submit prompt on refresh -->
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>
</head>

<body>
	<div class="card text-center">
		<div style="margin-top: 1em; margin-bottom: 1em;">
			<img style="display: inline; height: 2.5em; vertical-align: middle;" src="favicon.svg" alt="logo" />
			<h1 style="display: inline; margin-top: 0em; vertical-align: middle; letter-spacing: 3px;"><?php echo $title; ?></h1>
		</div>
		<hr style="margin-bottom: 2em;">
		<table id="theTable">
			<?php
			$csv_file = "data.csv";
			if (!is_file($csv_file)) {
				$init_data = "Column 1; Column 2; Column 3\nField 1; Field 2; Field 3\n";
				file_put_contents($csv_file, $init_data);
			}
			$row = 1;
			if (($handle = fopen($csv_file, "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
					$num = count($data);
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
							echo '<th "sortable" onclick="sortTable(' . $c . ')">' . $value . '</th>';
						} else {
							echo '<td class="text-left">' . $value . '</td>';
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
			}
			?>
			</tbody>
		</table>
		<button style="margin-bottom: 1.5em;" title="Edit link list" onclick='window.location.href = "edit.php"'><img style='vertical-align: middle;' src='svg/edit.svg' /></button>
		<div style="margin-bottom: 1em;">
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
</body>

</html>