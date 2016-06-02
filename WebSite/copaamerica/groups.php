<?php
	function host_tournament($year)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
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
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		$resultado = $mysqli->query('select a.group_code, b.code, b.name from group_stage a, country b where a.tournament = ' . $year . ' and a.group_code = \'' . $group . '\' and a.squad = b.code order by a.id');
		$script = '<lu>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '	<li><p>' . $fila['name'] . '</p><p><a href="http://www.area1650.net/copaamerica/country_stats.html?country=' . $fila['code'] . '">stats</a></p></li>';
		}
		$script = $script . '</lu>';
		$mysqli->close();
		return $script;
	}

	function group_matches($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
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
			$script = $script . '<td><a href="http://www.area1650.net/copaamerica/match_stats.html?squada=' . $fila['squada'] . '&squadb=' . $fila['squadb'] . '">stats</a></td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
	function country_stats($country)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = 'select a.name, sum(b.points) points, count(c.squad) games, count(d.squad) win, count(e.squad) draw,';
		$query = $query . ' count(f.squad) loose, sum(g.goals) goals, sum(i.goals) again, (sum(g.goals) - sum(i.goals)) diff,';
		$query = $query . ' (count(d.squad) / count(c.squad)) pw, (count(e.squad) / count(c.squad)) pd,';
		$query = $query . ' (count(f.squad) / count(c.squad)) pl ';
		$query = $query . ' from ';
		$query = $query . ' country a inner join game_score b inner join game h ';
		$query = $query . '     on a.code = ? and a.code = b.squad and b.matchid = h.matchid and b.time_type in (2,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
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
		$query = $query . '    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad'; 
		$query = $query . ' left join game_score i';
		$query = $query . '     on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad';
		$query = $query . ' group by a.code';
		$query = $query . ' order by points desc, diff desc, goals desc, again desc';
	
		$resultado = $mysqli->prepare($query);
		$resultado->bind_param('i', $country);
		$resultado->execute();
        $resultado->bind_result($name, $points, $games, $win, $draw, $loose, $goals, $again, $diff, $pw, $pd, $pl);		
		
		$script = '<a href="http://www.area1650.net/copaamerica/page.php">Copa America Centenario</a>';
		$script = $script . '<table>';
		while  ($resultado->fetch())
		{
			$script = $script . '<tr>';
			$script = $script . '<td>Country</td><td>'               . $name   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Points</td><td>'                . $points . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games</td><td>'                 . $games  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Won</td><td>'             . $win    . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Draw</td><td>'            . $draw   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Defeated</td><td>'        . $loose  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Scored</td><td>'          . $goals  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Against</td><td>'         . $again  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Difference</td><td>'      . $diff   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Victory</td><td>' . $pw     . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Draw</td><td>'    . $pd     . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Defeat</td><td>'  . $pl     . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
	function match_stats($squada, $squadb)
	{
		$mysqli = new PDO('mysql:dbname=copaamerica;host=127.0.0.1', 'areanet_admin', 'erSS1979_');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = ' select ';
		$query = $query . '      a.name,'; 
		$query = $query . ' 	 sum(b.points) points,'; 
		$query = $query . ' 	 count(c.squad) games,';
		$query = $query . ' 	 count(d.squad) win, ';
		$query = $query . ' 	 count(e.squad) draw,';
		$query = $query . ' 	 count(f.squad) loose,';
		$query = $query . '  	 sum(g.goals) goals,';
		$query = $query . ' 	 sum(i.goals) again,';
		$query = $query . ' 	 (sum(g.goals) - sum(i.goals)) diff,';
		$query = $query . ' 	 (count(d.squad) / count(c.squad)) pw,';
		$query = $query . ' 	 (count(e.squad) / count(c.squad)) pd,';
		$query = $query . ' 	 (count(f.squad) / count(c.squad)) pl';
		$query = $query . ' from ';
		$query = $query . ' country a inner join game_score b inner join game h inner join game_score j';
		$query = $query . '     on a.code = :squada and a.code = b.squad and b.matchid = h.matchid and b.matchid = j.matchid and j.squad = :squadb and b.time_type = j.time_type';
		$query = $query . ' 	 and b.time_type in (2,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
		$query = $query . ' left join game_score c';
		$query = $query . '     on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and c.squad = :squadb';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and d.squad = :squadb';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and e.squad = :squadb';
		$query = $query . ' left join game_score f';
		$query = $query . '     on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid)'; 
		$query = $query . ' 	                 and b.matchid = f.matchid and b.time_type = f.time_type and f.squad = :squadb';
		$query = $query . ' left join game_score g';
		$query = $query . '     on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = g.matchid and b.time_type = g.time_type and g.squad = :squada';
		$query = $query . ' left join game_score i';
		$query = $query . '     on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = i.matchid and b.time_type = i.time_type and i.squad = :squadb';
		$query = $query . ' group by a.code';
		$query = $query . ' order by points desc, diff desc, goals desc, again desc';		
		echo '<p>aqui</p>';
		$resultado = $mysqli->prepare($query);
		echo '<p>aqui1</p>';
		$resultado->bindParam(':squada', $squada, PDO::PARAM_INT);
		$resultado->bindParam(':squadb', $squadb, PDO::PARAM_INT);	
		echo '<p>aqui2</p>';		
		$resultado->execute();
		echo '<p>aqui3</p>';
        $resultado->bind_result($name, $points, $games, $win, $draw, $loose, $goals, $again, $diff, $pw, $pd, $pl);		
		
		$script = '<a href="http://www.area1650.net/copaamerica/page.php">Copa America Centenario</a>';
		$script = $script . '<table>';
		while  ($resultado->fetch())
		{
			$script = $script . '<tr>';
			$script = $script . '<td>Country</td><td>'               . $name   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Points</td><td>'                . $points . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games</td><td>'                 . $games  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Won</td><td>'             . $win    . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Draw</td><td>'            . $draw   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Defeated</td><td>'        . $loose  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Scored</td><td>'          . $goals  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Against</td><td>'         . $again  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Difference</td><td>'      . $diff   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Victory</td><td>' . $pw     . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Draw</td><td>'    . $pd     . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Defeat</td><td>'  . $pl     . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
?>