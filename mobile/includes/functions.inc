<?php
	//database connection with parameters coming from constants.inc in the same folder
	function sanitize($data)
	{
		$data = htmlspecialchars(addslashes(trim($data)));
		return $data;
	}
?>
