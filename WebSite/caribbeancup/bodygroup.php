<?php
	require ('groups.php');
	
	function bodygroup()
	{
		$script =           '				<body>';
		$script = $script . '					<div id="Tournament">';
		$script = $script . '						<a href="http:////www.area1650.net">Home</a>';
		$year = 2017;
		$script = $script . '						<p>Caribbean Cup</p>';
		$script = $script . host_tournament($year);
/*
		$script = $script . '						<div id="group-stage">';
		$script = $script . '							<p>Group Stage</p>';

		$array = array('A', 'B', 'C', 'D');
		foreach ($array as &$group)
		{
			$script = $script . '						<div id="group">';
			$script = $script . '							<p>Group ' . $group . '</p>';
			$script = $script . '							<div id="group_detail">';
			$script = $script . group_detail($year, $group);
			$script = $script . '							</div>';
			$script = $script . '							<div id="group_matches">';
			$script = $script . group_matches($year, $group);
			$script = $script . '							</div>';
			$script = $script . '							<div id="group_table">';
			$script = $script . group_table($year, $group);
			$script = $script . '							</div>';
			$script = $script . '						</div>';
		}
		$script = $script . '						</div>';
		
		$script = $script . '						<div id="quarter-finals">';
		$script = $script . '							<p>Quarter Finals</p>';
		$stage = 3;
		$script = $script . knockout_stage($year, $stage);
		$script = $script . '						</div>';
*/		
		$script = $script . '						<div id="semi-finals">';
		$script = $script . '							<p>Semi Finals</p>';
		$stage = 4;
		$script = $script . knockout_stage($year, $stage);
		$script = $script . '						</div>';
		
		$script = $script . '						<div id="third-place">';
		$script = $script . '							<p>Third Place</p>';
		$stage = 5;
		$script = $script . knockout_stage($year, $stage);
		$script = $script . '						</div>';
		
		$script = $script . '						<div id="final">';
		$script = $script . '							<p>Final</p>';
		$stage = 6;
		$script = $script . knockout_stage($year, $stage);
		$script = $script . '						</div>';
		
		$script = $script . '					</div>';
		$script = $script . '				</body>';
		$script = $script . '    </html>';
		return $script;
	}
?>