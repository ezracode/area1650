<?php
	require ('groups.php');
	
	function bodygroup()
	{
		$script = '    <html>'; 
		$script = $script . '				<body>';
		$script = $script . '					<a href="http:////www.area1650.net">Home</a>';
		$script = $script . '					<div id="Tournament">';
		$year = 2016;
		$script = $script . '						<p>Copa America</p>';
		$script = $script . host_tournament($year);
		$script = $script . '						<p>Group Stage</p>';
		$script = $script . '						<p>Group A</p>';
		$group = 'A';
		$script = $script . group_detail($year, $group);
		$script = $script . group_matches($year, $group);
		$script = $script . '						<p>Group B</p>';
		$group = 'B';
		$script = $script . group_detail($year, $group);
		$script = $script . group_matches($year, $group);
		$script = $script . '						<p>Group C</p>';
		$group = 'C';
		$script = $script . group_detail($year, $group);
		$script = $script . group_matches($year, $group);
		$script = $script . '						<p>Group D</p>';
		$group = 'D';
		$script = $script . group_detail($year, $group);
		$script = $script . group_matches($year, $group);
		$script = $script . '					</div>';
		$script = $script . '				</body>';
		$script = $script . '    </html>';
		return $script;
	}
?>