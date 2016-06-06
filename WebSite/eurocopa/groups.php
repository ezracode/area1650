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

	function group_table($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_eurocopa');

		if ($mysqli->connect_errno) 
		{
			echo 'Falló la conexión a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = ' select'; 
		$query = $query . '      a.name name, ';
		$query = $query . ' 	 sum(b.points) points, ';
		$query = $query . ' 	 count(b.squad) games, ';
		$query = $query . ' 	 count(c.squad) win, ';
		$query = $query . ' 	 count(d.squad) draw, ';
		$query = $query . ' 	 count(e.squad) loose,';
		$query = $query . ' 	 sum(f.goals) goals,';
		$query = $query . ' 	 sum(g.goals) again,';
		$query = $query . ' 	 (sum(f.goals) - sum(g.goals)) diff';
		$query = $query . ' from ';
		$query = $query . ' country a inner join game_score b inner join game h inner join group_stage i';
		$query = $query . '     on a.code = b.squad and b.matchid = h.matchid and h.game_type in (2) and year(h.matchdate) = ' . $year;
		$query = $query . ' 	 and b.squad = i.squad and b.goals is not null and i.group_code = \'' . $group  . '\' and i.tournament = ' . $year;
		$query = $query . ' left join game_score c';
		$query = $query . '     on c.points >= 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points = 1 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 0 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad';
		$query = $query . ' left join game_score f';
		$query = $query . '     on b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad';
		$query = $query . ' left join game_score g';
		$query = $query . '     on b.matchid = g.matchid and b.time_type = g.time_type and b.squad <> g.squad';
		$query = $query . ' where b.time_type in (2, 4, 6)'; 
		$query = $query . ' group by a.code';
		$query = $query . ' order by group_code, points desc, diff desc, goals desc, again desc';
		
		$resultado = $mysqli->query($query);
		$script = '<table>';
		$script = $script . '<tr>';
		$script = $script . '<td>Team</td>';
		$script = $script . '<td>Points</td>';
		$script = $script . '<td>Games</td>';
		$script = $script . '<td>Won</td>';
		$script = $script . '<td>Draw</td>';
		$script = $script . '<td>Loose</td>';
		$script = $script . '<td>Goals</td>';
		$script = $script . '<td>Against</td>';
		$script = $script . '<td>Difference</td>';
		$script = $script . '</tr>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '<tr>';
			$script = $script . '<td>' . $fila['name'] . '</td>';
			$script = $script . '<td>' . $fila['points'] . '</td>';
			$script = $script . '<td>' . $fila['games'] . '</td>';
			$script = $script . '<td>' . $fila['win'] . '</td>';
			$script = $script . '<td>' . $fila['draw'] . '</td>';
			$script = $script . '<td>' . $fila['loose'] . '</td>';
			$script = $script . '<td>' . $fila['goals'] . '</td>';
			$script = $script . '<td>' . $fila['again'] . '</td>';
			$script = $script . '<td>' . $fila['diff'] . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
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
		$query = $query . '     on a.newsquad = :country and a.oldsquad = b.squad and b.matchid = h.matchid and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
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
		$resultado->execute(array(':country' => $country));
		$script = '<a href="http://www.area1650.net/eurocopa/page.php">UEFA Euro 2016</a>';
		$script = $script .'<table>';
		$record = 0;
		while ($data = $resultado->fetch())
		{
			$record = 1;
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
		if ($record == 0)
		{
			$script = '<a href="http://www.area1650.net/eurocopa/page.php">UEFA Euro 2016</a>';
			$script = $script .'<p>No records for this team</p>';
		}
		return $script;
	}

	function match_stats($squada, $squadb)
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

		$query = ' select ';
		$query = $query . '      a.newname squada,';
		$query = $query . '      l.newname squadb,';
		$query = $query . ' 	 sum(b.points) pointsa,';
		$query = $query . ' 	 sum(i.points) pointsb,';
		$query = $query . ' 	 count(c.squad) games,';
		$query = $query . ' 	 count(d.squad) winb,';
		$query = $query . ' 	 count(e.squad) draw,';
		$query = $query . ' 	 count(f.squad) wina,';
		$query = $query . '  	 sum(g.goals) goalsa,';
		$query = $query . ' 	 sum(i.goals) goalsb,';
		$query = $query . ' 	 abs(sum(g.goals) - sum(i.goals)) diff,';
		$query = $query . ' 	 (count(d.squad) / count(c.squad)) pb,';
		$query = $query . ' 	 (count(e.squad) / count(c.squad)) pd,';
		$query = $query . ' 	 (count(f.squad) / count(c.squad)) pa';
		$query = $query . ' from ';
		$query = $query . ' current_country a inner join game_score b inner join game h inner join game_score j inner join current_country l';
		$query = $query . '     on a.newsquad = :squada and a.oldsquad = b.squad and b.matchid = h.matchid and b.matchid = j.matchid and l.newsquad = :squadb and l.oldsquad = j.squad and b.time_type = j.time_type';
		$query = $query . ' 	 and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
		$query = $query . ' left join game_score c';
		$query = $query . '     on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and l.newsquad = :squadb and l.oldsquad = c.squad';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and l.newsquad = :squadb and l.oldsquad = d.squad';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and l.newsquad = :squadb and l.oldsquad = e.squad';
		$query = $query . ' left join game_score f';
		$query = $query . '     on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid)'; 
		$query = $query . ' 	                 and b.matchid = f.matchid and b.time_type = f.time_type and l.newsquad = :squadb and l.oldsquad = f.squad';
		$query = $query . ' left join game_score g';
		$query = $query . '     on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))';  
		$query = $query . ' 	                 and b.matchid = g.matchid and b.time_type = g.time_type and a.newsquad = :squada and a.oldsquad = g.squad';
		$query = $query . ' left join game_score i';
		$query = $query . '     on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))';  
		$query = $query . ' 	                 and b.matchid = i.matchid and b.time_type = i.time_type and l.newsquad = :squadb and l.oldsquad = i.squad';
		$query = $query . ' group by a.newsquad';

		$resultado = $conn->prepare($query);
		$resultado->execute(array(':squada' => $squada, ':squadb' => $squadb));

		$script = '<a href="http://www.area1650.net/eurocopa/page.php">UEFA Euro 2016</a>';
		$script = $script .'<table>';

		$record = 0;
		while ($data = $resultado->fetch())
		{
			$record = 1;
			$script = $script . '<tr>';
			$script = $script . '<td>Country</td><td>'               . $data[0]  . '</td><td>Country</td><td>'               . $data[1]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games</td><td>'                 . $data[4]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Points</td><td>'                . $data[2]  . '</td><td>Points</td><td>'                . $data[3]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Won</td><td>'             . $data[5]  . '</td><td>Games Won</td><td>'             . $data[7]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Draw</td><td>'            . $data[6]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Scored</td><td>'          . $data[8]  . '</td><td>Goals Scored</td><td>'          . $data[9]  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Difference</td><td>'      . $data[10] . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Victory</td><td>' . $data[13] . '</td><td>Likelihood of Victory</td><td>' . $data[11] . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Draw</td><td>'    . $data[12] . '</td></td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$conn = null;
		if ($record == 0)
		{
			$script = '<a href="http://www.area1650.net/eurocopa/page.php">UEFA Euro 2016</a>';
			$script = $script .'<p>No records for this match</p>';
		}

		return $script;
	}

	function match_details($squada, $squadb)
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

		$query = ' select i.oldname namea, j.oldname nameb, f.name gametype, g.name timetype, a.goals goalsa, b.goals goalsb, c.matchdate matchdate, ifnull(d.goals,-1) goalsd, ifnull(e.goals, -1) goalse, ifnull(h.name, \'\') penalties from game_score a inner join game_score b inner join game c';
		$query = $query . ' on a.matchid = b.matchid and a.time_type = b.time_type and'; 
		$query = $query . ' a.time_type = (select max(time_type) from game_score where matchid = b.matchid and time_type in (2,3,4,6))';
		$query = $query . ' and a.matchid = c.matchid and c.matchdate < now()';
		$query = $query . ' left join game_score d'; 
		$query = $query . '  on d.time_type = 7'; 
		$query = $query . '  and a.matchid = d.matchid';
		$query = $query . '  and a.squad = d.squad';
		$query = $query . ' left join game_score e'; 
		$query = $query . '  on e.time_type = 7'; 
		$query = $query . '  and b.matchid = e.matchid';
		$query = $query . '  and b.squad = e.squad';
		$query = $query . '  left join game_type f on c.game_type = f.id';
		$query = $query . '  left join time_type g on a.time_type = g.id';
		$query = $query . '  left join time_type h on e.time_type = h.id';
		$query = $query . '  left join current_country i on a.squad = i.oldsquad';
		$query = $query . '  left join current_country j on b.squad = j.oldsquad';
		$query = $query . ' where i.newsquad = :squada and j.newsquad = :squadb';
		$query = $query . ' order by matchdate desc';		
		
		$resultado = $conn->prepare($query);
		$resultado->execute(array(':squada' => $squada, ':squadb' => $squadb));

		$script = '<table>';
		while ($data = $resultado->fetch())
		{
			$script = $script . '<tr>';
			$script = $script . '<td>Match Date: ' . $data[6] . '</td>';
			$script = $script . '</tr>';

			$script = $script . '<tr>';
			$script = $script . '<td>Country</td><td>' . $data[0] . '</td><td>Country</td><td>' . $data[1] . '</td>';
			$script = $script . '</tr>';

			$script = $script . '<tr>';
			$script = $script . '<td></td><td>' . $data[4] . '</td><td></td><td>' . $data[5] . '</td>';
			$script = $script . '</tr>';
			
			$script = $script . '<tr>';
			$script = $script . '<td>Game type</td><td>' . $data[2] . '</td>';
			$script = $script . '</tr>';

			$script = $script . '<tr>';
			$script = $script . '<td>Time Type</td><td>' . $data[3] . '</td>';
			$script = $script . '</tr>';

		    if ($data[7] > -1 && $data[8] > -1)
			{
				$script = $script . '<tr>';
				$script = $script . '<td>Time Type</td><td>' . $data[9] . '</td>';
				$script = $script . '</tr>';
				$script = $script . '<tr>';
				$script = $script . '<td></td><td>' . $data[7] . '</td><td></td><td>' . $data[8] . '</td>';
				$script = $script . '</tr>';
			}
		}
		$script = $script . '</table>';
		$conn = null;

		return $script;
	}
?>