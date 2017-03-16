<?php
	require ('groups.php');
	
	function bodygroup()
	{
		$script =           '				<body>';
		$script = $script . '					<div id="Tournament">';
		$script = $script . '						<a href="http:////www.area1650.net">Home</a>';
		$script = $script . '						<p>UEFA Champions League 2016/17</p>';
		$script = $script . '						<div id="quarter-finals">';
		$script = $script . '							<p>Quarter Finals Possible Matches</p>';
		$script = $script . probable_matches();
		$script = $script . '						</div>';
		$script = $script . '					</div>';
		$script = $script . '				</body>';
		$script = $script . '    </html>';
		return $script;
	}
?>