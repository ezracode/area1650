<?php
	$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
	if ($mysqli->connect_errno) {
		echo 'Falló la conexión a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
	}

	$resultado = $mysqli->query('select a.group_code, b.name from group_stage a, country b where a.tournament = ' . $year . ' and a.group_code = ' . $group . '  and a.squad = b.code order by a.id');
  
    echo '<lu>'. '\n';
    for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) {
		$resultado->data_seek($num_fila);
		$fila = $resultado->fetch_assoc();
		echo '	<li>' . $fila['name'] . '</li>\n';
	}
	echo '</lu>'. '\n';
	$mysqli->close();
?>