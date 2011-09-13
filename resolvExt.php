<?php

	function resolvExt($filename)
	{
		$data = explode('.', $filename);
		return strtoupper($data[count($data) - 1]);
	}

?>