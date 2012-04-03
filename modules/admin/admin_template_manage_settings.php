<h1 class="page-header">Manage Settings</h1>

<form action="?module=admin&amp;m=process_settings" method="post" class="form-horizontal">
<fieldset>
<?php 
	$sql = 'select * from sitesetting';
	$result = DataManager::query($sql);
	if ($result)
	{
		$row = DataManager::fetch_array($result);
		while ($row)
		{
			echo '<div class="control-group">' . "\n";
			echo '<label class="control-label" for="input_' . $row['name'] . '">' . $row['name'] . '</label>' . "\n";
			echo '<div class="controls">' . "\n";
			echo '<input type="text" class="input-large" id="input_' . $row['name'] . '" name="input_' . $row['name'] . '" value="' . $row['value'] . '" />' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			$row = DataManager::fetch_array($result);
		}
	}
?>
	<div class="control-group">
		<div class="controls">
			<input type="submit" id="settings_submit" name="settings_submit" class="btn btn-primary" value="save settings" />
		</div>
	</div>
</fieldset>
</form>