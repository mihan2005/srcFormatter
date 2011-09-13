#!/usr/bin/php
<?php
	error_reporting(0);
	define('OUTPUT_DIR', 'output');

	require_once('io.php');
        require_once('engine.php');
	require_once('resolvExt.php');
	require_once('ArgParser.php');

	$parser = new ArgParser($argv);
	$ioControll = new IO();

	if (($argc > 1) && ($parser->validate()))
	{		
		$filename = $parser->getArgByName('--file');
		if ($filename == '')
		{
			echo "Please choose filename \n";
		}
		else if (file_exists($filename))
		{
			$module = $parser->getArgByName('--module');
			if ($module == '')
			{
				$module = resolvExt($filename);
			}
			// Capitalizing first sign
	       	$className = ucfirst($module);
			require_once('modules/'.$module.'/mod_'.strtolower($module).'.php');
	       	// Creating an instance of required class
	       	$formater = new $className($filename, $module);
	       	// Do smth...
	       	$formater->prepare();
			$ioControll->write("Formatting is done \n");	
		}
		else
		{
			echo "Source file does not exist \n";
		}		
	}
	else
	{
		echo "If you want use CLI arguments: \n ./index.php --file <filepath> [--module <module_name>] \n\n";
			$menu = "Choose what u want: \n
		1. Open file
		2. Open directory
		3. Format
		4. Choose language
		5. Exit\n";

			$filename = '';
			$module = '';
			$result = 0;

			while (($result != 5) && ($result != 3))
			{
				$ioControll->write($menu);
				$ioControll->write("Type tour choise:");
				$result = $ioControll->read();
				switch ($result)
				{
					case 1: 
						$ioControll->write("Type here filepath:");
						$filename = $ioControll->readStr();
						$ioControll->write("Filename is read \n");
					break;
					case 2: 
						$ioControll->write("Type here path to dir:");
						$filename = $ioControll->readStr();
						$ioControll->write("DirPath is read \n");			
					break;		
					case 3: 
						$ioControll->write("Formatting... \n");
						if ($module == '')
						{
							if ($filename == '')
							{
								echo "Please choose filename \n";
							}
							else
							{
								$module = resolvExt($filename);	
							}
						}				
		               	// Capitalizing first sign
		               	$className = ucfirst($module);
						require_once('modules/'.$module.'/mod_'.strtolower($module).'.php');
		               	// Creating an instance of required class
		               	$formater = new $className($filename, $module);
		               	// Do smth...
		               	$formater->prepare();
						$ioControll->write("Formatting is done \n");
					break;
					case 4:
						$ioControll->write("Choose programming language:\n 1. C\n 2. C#\n Your choice:");
						$tmp = $ioControll->read();
						switch ($tmp)
						{
							case 1: $module = 'C'; break;
							case 2: $module = 'C#'; break;					
						}
					break;
					case 5:
						$ioControll->write("Exiting \n"); 
					break;		
				}
			}
		
	}
?>
