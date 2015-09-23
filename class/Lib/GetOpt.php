<?php
	// Thanks to http://php.net/manual/es/function.getopt.php#79286
	function _GetOpt($Options, &$Remaining)
	{
		$Options = getopt($Options);

		$Remaining = $GLOBALS['argv'];

		foreach($Options as $O => $A)
		{
			while($k = array_search('-' . $O . $A, $Remaining))
				if($k)
					unset($Remaining[$k]);

			while($k = array_search('-' . $O, $Remaining))
			{
				if($k)
				{
					unset($Remaining[$k]);
					unset($Remaining[$k + 1]);
				}
			}
		}

		if(isset($Remaining[0]))
			unset($Remaining[0]);

		$Remaining = array_values($Remaining);

		return $Options;
	}