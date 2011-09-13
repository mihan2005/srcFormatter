<?php
	require_once('PHPUnit/Framework/TestCase.php');
	
	class EngineTest extends PHPUnit_Framework_TestCase
	{
		public function __constructor($name)
		{
			parent::__constructor($name);
		}
		
		public function testOutputFile()
		{
			$result = file_get_contents('../output/source.c.html');
			$standard = file_get_contents('testData/standard.html');
			$this->assertEquals($result, $standard);
		}
	}

?>
