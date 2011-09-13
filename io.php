<?php

	class IO
	{
		private $inputStream;
		private $stdin;
		
		public function __construct()
		{
			$this->stdin = fopen('php://stdin', 'r');
		}
		
		public function read()	
		{
			fscanf(STDIN, "%d\n", $input);
			return $input;
		}
		
		public function readStr()
		{
			return trim(fgets(STDIN));
		}
		
		public function write($output)
		{
			echo $output;
		}
	}

?>