<?php
include('config.php');
?>

<html lang="en">
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
							echo '<td>' . $value . '</td>';
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
		<form method='GET' action='edit.php'>
			<p style="margin-top: 1.5em;"><button type='submit'>Edit</button></p>
		</form>
		<p><?php echo $footer; ?></p>
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