<?php
	function host_tournament($year)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_eurocopa');
		if ($mysqli->connect_errno) 
		{
			echo 'Falló la conexión a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		$sctipt = '';
		$resultado = $mysqli->query('select a.code, b.name from tournament a, country b where a.country = b.code and a.code = ' . $year);
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '	<p>' . $fila['code'] . ' - ' . $fila['name'] . '</p>';
		}
		$mysqli->close();
		return $script;
	}

	function group_detail($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_eurocopa');
		if ($mysqli->connect_errno) 
		{
			echo 'Falló la conexión a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		$resultado = $mysqli->query('select a.group_code, b.name from group_stage a, country b where a.tournament = ' . $year . ' and a.group_code = \'' . $group . '\' and a.squad = b.code order by a.id');
		$script = '<lu>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '	<li>' . $fila['name'] . '</li>';
		}
		$script = $script . '</lu>';
		$mysqli->close();
		return $script;
	}
	function group_matches($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_eurocopa');
		if ($mysqli->connect_errno) 
		{
			echo 'Falló la conexión a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = 'select b.matchdate matchdate, c.squad squada, d.name namea, e.squad squadb, f.name nameb from ';
		$query = $query . 'group_stage a inner join game b inner join game_score c '; 
		$query = $query . 'inner join country d inner join game_score e inner join country f ';
		$query = $query . 'on b.matchid = c.matchid and c.time_type = e.time_type '; 
		$query = $query . 'and c.id = (select min(id) from game_score where matchid = c.matchid) ';
		$query = $query . 'and c.matchid = e.matchid and c.squad <> e.squad ';
		$query = $query . 'and a.squad = c.squad and a.tournament = ' . $year;
		$query = $query . ' and year (b.matchdate) = a.tournament and a.group_code = \'' . $group . '\' and ';
		$query = $query . 'b.game_type = 2 and c.time_type = 2 and c.squad = d.code and e.squad = f.code ';
		$query = $query . 'order by b.matchid';	

		$resultado = $mysqli->query($query);
		$script = '<table>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '<tr>';
			$script = $script . '<td>' . $fila['matchdate'] . '</td>';
			$script = $script . '<td>' . $fila['namea'] . '</td>';
			$script = $script . '<td>vs</td>';
			$script = $script . '<td>' . $fila['nameb'] . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
?>