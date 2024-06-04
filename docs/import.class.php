<?php  

class MG_Import
{
	public $file = "" ; 
	public $columns = [] ; 
	public $matches = [] ; 
	public $lines = [] ; 
	public $elements = [] ; 
	public $limit = 2000 ; 
	public $step = 1 ; 
	
	function __construct()
	{
		
	}
	public function load_file($file, $delimiter = "semicolon")
	{
		if (empty($file))
			return false ; 
		
		switch($delimiter)
		{
			case "semicolon" : $delimiter = ";" ; break ; 
			case "comma" : $delimiter = "," ; break ; 
			case "sharp" : $delimiter = "#" ; break ; 
			case "at" : $delimiter = "@" ; break ; 
			case "tab" : $delimiter = "\t" ; break ; 
		}
		$content = [] ;
		
		if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) 
		{
			while (($temp = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
				$temp = serialize($temp);
				if(!mb_detect_encoding($temp, 'utf-8', true))
				{
				   $temp = unserialize($temp);
				   $content[] = array_map("utf8_encode", $temp) ;
				}
				else 
				{
				   $temp = unserialize($temp);
				   $content[] = array_map("htmlentities", $temp) ;
				}
			}
			fclose($handle);
		}
		$content = array_slice($content, 0, $this->limit) ;
		
		$this->file = $content ;
	}
	public function encode()
	{
		return base64_encode(serialize($this)) ;
	}
	public function decode($content)
	{
		$content = unserialize(base64_decode($content));
		
		if (!empty($content))
		{
			$this->doctype = $content->doctype ;
			$this->file = $content->file ;
			$this->limit = $content->limit ;
			$this->step = $content->step ;
			$this->columns = $content->columns ;
			$this->matches = $content->matches ;
			$this->lines = $content->lines ;
		}
	}
	public function set_matches($matches)
	{
		$this->matches = $matches ;
	}
	public function prepare()
	{
		unset($this->file[0]) ; 
		
		// dump($this->file) ;
		// dump($this->matches) ;
		
		/******************* init lines  ********************/
		
		$lines = [] ; 
		
		foreach ($this->file as $key => $value)
		{
			$key ++ ;
			
			foreach ($value as $index => $value2)
			{
				if ($this->matches[$index] != "ignore")
					$lines[$key][$this->matches[$index]] = trim($value2) ;
			}
			
			$lines[$key] = array_map("trim", $lines[$key]) ; 
			$lines[$key]['errors'] = [] ;
		}
		
		/******************* check required  ********************/
		
		$requireds = array() ;
		foreach($this->columns as $key => $value) {
			if (isset($value['required']))
				$requireds[$key] = $value['title'] ; 
		}
		
		// dump($requireds) ;
		
		foreach ($lines as $key => $line)
		{
			foreach ($requireds as $column => $title)
			{
				if (!isset($line[$column]) || $line[$column] == "")
				{
					$lines[$key]['errors'][] = "champ manquant : ".$title ;
				}
			}
		}
		
		$this->lines = $lines ;
		
		$this->map() ;		
	}
	public function map() 
	{
		
	}
	public function process()
	{
		
	}
}
