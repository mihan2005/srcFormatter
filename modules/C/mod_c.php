<?php

	class C extends Engine
	{
		protected $operIn = '{';
		protected $operOut = '}';
		protected $tab = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		protected $keywords = array(
			"if" => "if",			
			"then" => "then",
			"else" => "else",
			"double" => "double",
			"return" => "return",
			"int" => "int",
			"long" => "long",
			"for" => "for",
			"#include" => "include",
			"NULL" => 'null',
			"pthread_t" => 'pthread_t',
		);
		public function printLang()
		{
			return "<h2>This is C Language</h2> <br />";
		}
		// Можно перекрывать наследованные методы своей реализацией		
		// В данном случае напечатаем еще название языка после шапки
		protected function createHead($outHandle)
		{
			$header = file_get_contents('resources/header.html');
			fputs($outHandle, $header);
			fputs($outHandle, $this->printLang());
		}
	}

?>