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
		$Config['TournamentToken'] = $Arguments['t'];

		Json::Encode($ConfigFileName, $Config);
	}

	if(!empty($Config) && !empty($Config['TeamName']) && !empty($Config['TournamentToken']))
	{
		Std::Out("[Info] Welcome, {$Config['TeamName']} team", 2);

		$ProblemID = !empty($Remaining[0]) ? (is_numeric($Remaining[0]) ? (int) $Remaining[0] : null) : null;

		if(!empty($ProblemID))
		{
			Std::Out("[Info] Requesting parameters for problem with ID {$ProblemID}...", 2);

			$Hornero = new Hornero($Config['TournamentToken']);

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
						$Parameters = $Problem->Parameters;

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
								Std::Out('[Warning] Error while sending solution to server');
						}
						else
							Std::Out('[Warning] You must put the solution in $Solution');
					}
					else
						Std::Out("[Warning] problems/{$ProblemID}.php does not exists or is not readable");
				}
			}
			elseif(is_string($Problem))
				Std::Out("[Warning] {$Problem}");
			else
				Std::Out('[Warning] Error while requesting data from server');
		}
		else
		{
			Std::Out("Usage: php Hornero.php [-n <Team Name>] [-t <Tournament Token>] [-d] <Problem ID>");
		}
	}
	else
		Std::Out('[Fatal] You must configure TeamName and TournamentToken. Try with php Hornero.php -n <Name> -t <Token>');