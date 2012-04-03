<h1 class="page-header">Manage Modules</h1>

<?php 
$sql = 'select * from module order by name';
$result = DataManager::query($sql);
if ($result)
{
	$row = DataManager::fetch_array($result);
	while ($row)
	{
		echo '<div class="well">' . "\n";
		echo '<div class="module-name">' . $row['name'] . '</div>' . "\n";
		echo '</div>' . "\n";
		$row = DataManager::fetch_array($result);
	}
}
?>