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
		$resultado = $mysqli->query('select a.group_code, b.code, b.name from group_stage a, country b where a.tournament = ' . $year . ' and a.group_code = \'' . $group . '\' and a.squad = b.code order by a.id');
		$script = '<lu>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '	<li><p>' . $fila['name'] . '</p><p><a href="http://www.area1650.net/eurocopa/country_stats.html?country=' . $fila['code'] . '">stats</a></p></li>';
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
			$script = $script . '<td><a href="http://www.area1650.net/eurocopa/match_stats.html?squada=' . $fila['squada'] . '&squadb=' . $fila['squadb'] . '">stats</a></td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
	function country_stats($country)
	{
		try
		{
			$conn = new PDO('mysql:host=127.0.0.1;dbname=areanet_eurocopa', 'areanet_admin', 'erSS1979_');
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			print '¡Error!: ' . $e->getMessage() . '<br/>';
			die();
		}
		$query = 'select a.newname, sum(b.points) points, count(c.squad) games, count(d.squad) win, count(e.squad) draw,';
		$query = $query . ' count(f.squad) loose, sum(g.goals) goals, sum(i.goals) again, (sum(g.goals) - sum(i.goals)) diff,';
		$query = $query . ' (count(d.squad) / count(c.squad)) pw, (count(e.squad) / count(c.squad)) pd,';
		$query = $query . ' (count(f.squad) / count(c.squad)) pl ';
		$query = $query . ' from ';
		$query = $query . ' current_country a inner join game_score b inner join game h ';
		$query = $query . '     on a.newsquad = ? and a.oldsquad = b.squad and b.matchid = h.matchid and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
		$query = $query . ' left join game_score c';
		$query = $query . '     on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad';
		$query = $query . ' left join game_score f';
		$query = $query . '     on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid)'; 
		$query = $query . ' 	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad';
		$query = $query . ' left join game_score g';
		$query = $query . '    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))';  
		$query = $query . ' 	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad'; 
		$query = $query . ' left join game_score i';
		$query = $query . '     on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))';  
		$query = $query . ' 	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad';
		$query = $query . ' group by a.newsquad';
		$query = $query . ' order by points desc, diff desc, goals desc, again desc';
		
		$resultado = $conn->prepare($query);
		$resultado->execute(array($country));
		$script = '<a href="http://www.area1650.net/eurocopa/page.php">UEFA Euro 2016</a>';
		$script = $script .'<table>';
		while ($data = $resultado->fetch())
		{
			$script = $script . '<tr>';
			$script = $script . '<td>Country</td><td>'               . $data[0]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Points</td><td>'                . $data[1]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games</td><td>'                 . $data[2]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Won</td><td>'             . $data[3]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Draw</td><td>'            . $data[4]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Defeated</td><td>'        . $data[5]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Scored</td><td>'          . $data[6]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Against</td><td>'         . $data[7]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Difference</td><td>'      . $data[8]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Victory</td><td>' . $data[9]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Draw</td><td>'    . $data[10] . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Defeat</td><td>'  . $data[10] . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$conn = null;
		return $script;
	}
?>