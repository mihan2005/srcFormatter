<?php

   /** 
	* Парсер аргументов командной строки
	* 
	* 
	* @version 1.0
	* @package lib
	*/
	class ArgParser 
	{
		private $argv;
		
	   /**
		* Конструктор класса
		* @param array $argv
		*/
		public function __construct($argv)
		{
			$this->argv = $argv;
		}
		
	   /**
		* Функция валидации строки введенных аргументов
		* 
		* Суть в следующем: после каждого названия аргумента, начинающегося с "--" должно идти его значение, которое начинается с других символов
		* @return bool
		*/
		public function validate()
		{
			$result = 1;
			$argc = count($this->argv);
			$isVal = 0;
			for ($i = 0; $i < $argc; $i++)
			{	
				if ($isVal == $this->isVal($this->argv[$i]))
				{
					$result = 0;
					break;
				}
				else
				{
					$isVal = $this->isVal($this->argv[$i]);
				}
			}
			return $result;
		}
		
	   /** 
		* Функция проверки, является ли аргумент названием или значением
		* 
		* @param string $str
		* @return bool
		*/
		private function isVal($str)
		{
			if (($str[0] != '-') && ($str[1] != '-'))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		
		/**
		* Получение значения аргумента
		* Функция по ключевому слову аргумента дает его значение, если оно есть
		* @param string $name
		* @return bool
		*/
		public function getArgByName($name)
		{
			$value = '';
			$found = 0;
			// Идем по листу аргументов
			foreach($this->argv as $arg)
			{
				// Если предыдущим было название нужного аргумента - запоминаем его текущее значение
				if ($found == 1) 
				{ 
					$value = $arg; 
					break; 
				}
				// Если название совпадает с тем, что мы ищем - делаем отметочку
				if ($arg == $name)
				{
					$found = 1;
				}
			}
			return $value;
		}
		
		/**
		* Проверка, является ли путь маской к файлу(ам)
		*
		* Проверяем просто по наличию звездочки во введенной строке
		* @param string $str
		* @return bool
		*/
		
	}


?>