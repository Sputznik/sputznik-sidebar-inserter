<?php

$screens = array(
	'general'	=> array(
		'label'			=> 'General',
		'template'	=> plugin_dir_path(__FILE__).'general.php'
	),
	// 'sample-tab'	=> array(
	// 	'label'			=> 'Sample Tab',
	// 	'tab'				=> 'sample-tab',
	// 	'template'	=> plugin_dir_path(__FILE__).'sample-tab.php'
	// )
);
?>
<div class="wrap">
	<h1>Sputznik Sidebar Inserter Settings</h1>
	<?php $this->tabs( $screens );?>
</div>
