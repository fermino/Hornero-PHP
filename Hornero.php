<?php
	require_once dirname(__FILE__) . '/class/Hornero.php';

	require_once dirname(__FILE__) . '/class/Lib/Std.php';
	require_once dirname(__FILE__) . '/class/Lib/Json.php';
	require_once dirname(__FILE__) . '/class/Lib/GetOpt.php';

	### Config ###
		$ConfigFileName = 'config.json';
	### Config ###

	$Config = Json::Decode($ConfigFileName);

	$Arguments = _GetOpt('n:t:d', $Remaining);

	if(!empty($Arguments['n']))
	{
		$Config['TeamName'] = $Arguments['n'];

		Json::Encode($ConfigFileName, $Config);
	}

	if(!empty($Arguments['t']))
	{
		$Config['TeamToken'] = $Arguments['t'];

		Json::Encode($ConfigFileName, $Config);
	}

	if(!empty($Config) && !empty($Config['TeamName']) && !empty($Config['TeamToken']))
	{
		Std::Out("[Info] Welcome, {$Config['TeamName']}", 2);

		$ProblemID = !empty($Remaining[0]) ? (is_numeric($Remaining[0]) ? (int) $Remaining[0] : null) : null;

		if(!empty($ProblemID))
		{
			Std::Out("[Info] Requesting parameters for problem with ID {$ProblemID}...", 2);

			$Hornero = new Hornero($Config['TeamToken']);

			$Problem = $Hornero->GetParameters($ProblemID);

			if($Problem instanceof Problem)
			{
				if(isset($Arguments['d']))
					Std::Out('[Info] Debug mode', 2);

				Std::Out("==> {$Problem->Name} ({$Problem->Token})");

				print_r($Problem->Parameters);

				if(!isset($Arguments['d']))
				{
					Std::Out();

					$Path = dirname(__FILE__) . "/problems/{$ProblemID}.php";

					if(is_readable($Path))
					{
						require_once $Path;

						if(isset($Solution))
						{
							Std::Out("[Info] Sending solution for problem with ID {$ProblemID} ({$Solution})...", 2);

							$SolutionResponse = $Hornero->SendSolution($Problem->Token, $Solution);

							if($SolutionResponse instanceof SolutionResponse)
								print_r($SolutionResponse);
							elseif(is_string($SolutionResponse))
								Std::Out("[Warning] {$SolutionResponse}");
							else
								Std::Out('[Warning] No se puede hacer la petición (solución) al servidor');
						}
						else
							Std::Out('[Warning] Debes poner la solución en la variable $Solution');
					}
					else
						Std::Out("[Warning] El archivo (problems/{$ProblemID}.php) no existe o no es legible");
				}
			}
			elseif(is_string($Problem))
				Std::Out("[Warning] {$Problem}");
			else
				Std::Out('[Warning] No se puede hacer la petición (parámetros) al servidor');
		}
		else
		{
			Std::Out("Usage: php Hornero.php [-n <Team Name>] [-t <Team Token>] [-d] <Problem ID>");
		}
	}
	else
		Std::Out('[Fatal] You must configure TeamName and TeamToken. Try with php Hornero.php -n <Name> -t <Token>');