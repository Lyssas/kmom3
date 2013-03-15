<?php
/**
* Helpers for the template file.
*/
$fn = 'base_url';
$RED->data['header'] = <<<EOD



<header id = "top">
	<!-- logo -->
	<div>
		<img src={$fn('img/logoRED.png')} alt="RED logo" > 
	</div>	
</header>

EOD;

/*
if($RED->data['main'] == "")
{
	$RED->data['main']   = '<div class = "temp"><p>Not much to report for now.</p></div>';
}
*/
$RED->data['footer'] = '<footer id = "bottom"><p>&copy; RED by Henrik Lundqvist. Inspired by and created with tutorial for &copy; Lydia by Mikael Roos (mos@dbwebb.se)</p></footer>';
  

