<?php

/** 
* Класс, производящий форматирование файла с исходником
* @version 1.0
*/	
class Engine
{
	/**
	* Счетчик операторов
	* @var int
	*/
	protected $operInCounter = 0;
	/**
	* Название модуля, с которым мы работаем
	* @var string
	*/
	protected $module;
	/**
	* Название форматируемого файла
	* @var string
	*/
	protected $filename;
	
	/**
	* Конструктор базового класса для форматирования
	* Принимает и сохраняет списки входящих/исходящих файлов и шаблонов для замены
	* @param string $filename, string $lang
	*/	
	public function __construct($filename, $lang)
	{
		$this->filename = $filename;
		$this->module = $lang;
	}	
	
	/**
	* Деструктор класса замены подстрок
	*
	* Может выполнять некоторые функции при завершении работы приложения,
	* такие, как например закрытие файлов и тп.
	*/
	public function __destruct()
	{
		// 
	}
	
	/**
	* Метод создания css файлов к форматируемому файлу
	*
	* Метод копирует из папки модуля css файл в папку с отформатированным текстом
	* @access protected
	* @param string $outputDir
	*/
	protected function createCSS($outputDir)
	{
		$data = file_get_contents('modules/'.$this->module.'/style.css');
		file_put_contents($outputDir.'/style.css', $data);
	}
	
	/**
	* Метод создания верхнней части отформатированной html страницы
	*
	* Записывает в исходящий файл стандартный шаблон страницы 
	* @access protected
	* @param $outHandle
	*/
	protected function createHead($outHandle)
	{
		$header = file_get_contents('resources/header.html');
		fputs($outHandle, $header);
	}

	/**
	* Метод создания нижней части html страницы
	*
	* Записывает в исходящий файл стандартный шаблон страницы
	* @access protected
	* @param $outHandle
	*/	
	protected function createFooter($outHandle)
	{
		$footer = file_get_contents('resources/footer.html');
		fputs($outHandle, $footer);			
	}
	
	/**
	* Метод форматирования строки
	*
	* Обрабатывает полученную строку, заворачивает в тег span с заданным id
	* @access protected
	* @param string $oper, string $id
	* @return string
	*/	
	protected function format($oper, $id)
	{
		$oper = htmlspecialchars($oper);
		// Проверка наличия оператора в списке элементов подлежащих форматированию
		$result = '<span class="'.$this->getSpanClass($oper).'">'.$oper.'</span>';
		// Уменьшить глубину вхождения в дерево
		if ($oper == $this->operOut)
		{
			$this->operInCounter--;
		}
		// Выводим табуляцию
		for ($i = 0; $i < $this->operInCounter; $i++)
		{
			if ($id == 0)
			{
				$result = $this->tab.$result;					
			}
		}
		// Увеличить глубину вхождения в дерево
		if ($oper == $this->operIn)
		{
			$this->operInCounter++;
		}
		return $result;
	}
	
	/**
	* Метод получения параметра class для тэга span
	*
	* @access protected
	* @param string $oper
	* @return string
	*/
	protected function getSpanClass($oper)
	{
		// Проверяем существование в базе данной сигнатуры
		if ($this->keywords[$oper] != '')
		{
			return $this->keywords[$oper];
		}
		else
		{
			return 'default';
		}		
	}
	
	/**
	* Метод парсинга переданной строки
	*
	* Разбивает заданную строку на токены по ключевому символу
	* @access protected
	* @param string $line
	* @return array
	*/
	protected function parseLine($line)
	{
		$arr = explode(' ', $line);
		return $arr;
	}
	
	/**
	* Метод получения директории файла
	*
	* Обрабатывает полученную строку, получает из нее название директории с файлом
	* @final
	* @access protected
	* @param string $longDir
	* @return string
	*/
	protected final function getShortDir($longDir)
	{
		$dir_arr = explode('/', $longDir);
		return $dir_arr[count($dir_arr) - 1];
	}
	
	/**
	* Метод получения пути к файлу
	*
	* Обрабатывает полученную строку, удаляя последний токен в массиве
	* @final
	* @access protected
	* @param string $path
	* @return string
	*/
	protected final function getPath($path)
	{
		$path_arr = explode('/', $path);
		unset($path_arr[count($path_arr) - 1]);
		return implode('/', $path_arr).'/';
	}
	
	/**
	* Метод форматирования файла
	*
	* Открывает файлы для чтения/записи, пишет в них заголовки, создает css файлы, читает из входящего файла данные, обрабатывает их и добавляет в массив, дл послкедующей записи
	* @access protected
	* @param string $filename, string $inputDir, string $outputDir
	*/	
	protected function formatFile($filename, $inputDir = '', $outputDir)
	{
		// Откроем исходящий и входящий файлы
		$inHandle = fopen($inputDir.$filename, "r");
		$outHandle = fopen($outputDir.$filename.'.html', "w");
		// Запишем заголовки в файл
		$this->createCSS($outputDir);
		$this->createHead($outHandle);
		// Форматирум строчки
		while(!feof($inHandle))
		{
			// Читаем строку из файла, обрезаем мусор по концам строки
			$line = trim(fgets($inHandle));			
			// Разбиваем строку в массив. 
			$line_arr = $this->parseLine($line);
			// Форматируем операторы в массиве
			$length = count($line_arr);
			for ($i = 0; $i < $length; $i++)
			{
				$line_arr[$i] = $this->format($line_arr[$i], $i);
			}			
			// Соединяем все элементы массива в строку
			$line = implode(' ', $line_arr);
			$line .= '<br />';
			// Пишем строку в файл
			fputs($outHandle, $line);
		}
		// Запишем футер
		$this->createFooter($outHandle);
		// 	Закроем файлы
		fclose($inHandle);
		fclose($outHandle);		
	}
	
	/**
	* Метод подготавливающий к форматированию
	*
	* Создает, если необходимо недостающие директории, читает файлы из входящей папки, передает из обработчику
	* @access public
	*/
	public function prepare()
	{
		if (is_dir($this->filename))
		{
			// Сделаем одноименную исходящую директорию
			$outputDir = 'output/'.$this->getShortDir($this->filename).'/';
			if (!file_exists($outputDir))
			{
				mkdir($outputDir);				
			}
			// Откроем папку со входящими данными
			if ($dir = opendir($this->filename)) 
			{
				// Читаем данные из папки
				while (false !== ($file = readdir($dir)))
				{					
					if ($file != "." && $file != "..") 
					{
						// Если все ок - запускаем
						$this->formatFile($file, $this->filename.'/', $outputDir);
					}
				}
			}
			//Закрываем директорию
			closedir($dir);
		}
		else
		{
			$outputDir = 'output/';
			$this->formatFile($this->getShortDir($this->filename), $this->getPath($this->filename), $outputDir);
		}
		
	}		
	
}

?>