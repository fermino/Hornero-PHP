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
		Std::Out("[Info] Bienvenido, equipo {$Config['TeamName']}", 2);

		$ProblemID = !empty($Remaining[0]) ? (is_numeric($Remaining[0]) ? (int) $Remaining[0] : null) : null;

		if(!empty($ProblemID))
		{
			Std::Out("[Info] Pidiendo parámetros para el problema con ID {$ProblemID}...", 2);

			$Hornero = new Hornero($Config['TournamentToken']);

			$Problem = $Hornero->GetParameters($ProblemID);

			if($Problem instanceof Problem)
			{
				if(isset($Arguments['d']))
					Std::Out('[Info] Modo de pruebas', 2);

				Std::Out("==> {$Problem->Name} ({$Problem->Token})");

				print_r($Problem->Parameters);

				if(!isset($Arguments['d']))
				{
					Std::Out();

					$Path = dirname(__FILE__) . "/problems/{$ProblemID}.php";

					if(is_readable($Path))
					{
						$Parametros = $Problem->Parameters;

						require_once $Path;

						if(isset($Solucion))
						{
							Std::Out("[Info] Enviando solución para el problema con ID {$ProblemID} ({$Solucion})...", 2);

							$SolutionResponse = $Hornero->SendSolution($Problem->Token, $Solucion);

							if($SolutionResponse instanceof SolutionResponse)
								print_r($SolutionResponse);
							elseif(is_string($SolutionResponse))
								Std::Out("[Warning] {$SolutionResponse}");
							else
								Std::Out('[Warning] Error al enviar la solución al servidor');
						}
						else
							Std::Out('[Warning] Debes poner la solución en la variable $Solucion');
					}
					else
						Std::Out("[Warning] problems/{$ProblemID}.php no existe o no es legible");
				}
			}
			elseif(is_string($Problem))
				Std::Out("[Warning] {$Problem}");
			else
				Std::Out('[Warning] Error el pedir los parámetros al servidor');
		}
		else
		{
			Std::Out("Uso: php Hornero.php [-n <Nombre del equipo>] [-t <Token del torneo>] [-d] <Problem ID>");
		}
	}
	else
		Std::Out('[Fatal] Debes configurar el nombre del equipo y el token del torneo. Intenta con php Hornero.php -n <Nombre del equipo> -t <Token del torneo>');