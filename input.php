<?php

	class CustomIO
	{
		private $inputStream;
		private $input;
		
		// public function __construct()
		// {

		// }
		
		public function read()	
		{
			$stdin = fopen('php://stdin', 'r');
			fscanf(STDIN, "%d\n", $num);
			// $this->output($this->input);
		}
		
		public function write($output)
		{
			echo $output;
		}
	}

?>