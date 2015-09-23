<?php
	class Std
	{
		public static function In()
		{
			$String = trim(fgets(STDIN));

			if(!empty($String))
				return $String;

			return false;
		}

		public static function Out($String, $NewLines = 1)
		{
			for($i = 0; $i < $NewLines; $i++)
				$String .= PHP_EOL;

			if(fwrite(STDOUT, $String) === strlen($String))
				return true;

			return false;
		}
	}