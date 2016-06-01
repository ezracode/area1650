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

		$array = array('A', 'B', 'C', 'D');
		foreach ($array as &$group)
		{
			$script = $script . '					<div id="group">';
			$script = $script . '						<p>Group' . $group . '</p>';
			$script = $script . '						<div id="group_detail">';
			$script = $script . group_detail($year, $group);
			$script = $script . '						</div>';
			$script = $script . '						<div id="group_matches">';
			$script = $script . group_matches($year, $group);
			$script = $script . '						</div>';
			$script = $script . '					</div>';
		}

		$script = $script . '				</body>';
		$script = $script . '    </html>';
		return $script;
	}
?>