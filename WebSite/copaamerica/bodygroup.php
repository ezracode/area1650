<?php
echo ('    <html>' . '\n'); 
echo ('				<body>' . '\n');
echo ('					<div id="Tournament">' . '\n');
$yaer = 2016;
echo ('						<p>Group Stage</p>' . '\n');
echo ('						<p>Group A</p>' . '\n');
$group = 'A';
include ('group.php');
echo ('						<p>Group B</p>' . '\n');
$group = 'B';
include ('group.php');
echo ('						<p>Group C</p>' . '\n');
$group = 'C';
include ('group.php');
echo ('						<p>Group D</p>' . '\n');
$group = 'D';
include ('group.php');
echo ('					</div>' . '\n');
echo ('				</body>' . '\n');
echo ('    </html>' . '\n');
?>