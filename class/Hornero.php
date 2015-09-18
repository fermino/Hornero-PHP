<?php
	require_once dirname(__FILE__) . '/Model/Problem.php';
	require_once dirname(__FILE__) . '/Model/SolutionResponse.php';

	class Hornero
	{
		const ENDPOINT = 'http://hornero.fi.uncoma.edu.ar';

		private $TeamToken = null;

		public function __construct($TeamToken)
		{
			$this->TeamToken = $TeamToken;
		}

		public function GetParameters($ProblemID)
		{
			$Response = $this->Request('index.php', array('r' => 'juego/solicitud', 'token' => $this->TeamToken, 'problema' => $ProblemID));

			if($Response !== false)
			{
				if(!isset($Response['error']))
					return new Problem($Response['nombreProblema'], $Response['token'], explode(',', $Response['parametrosEntrada']));
				else
					return $Response['error'];
			}

			return false;
		}

		public function SendSolution($ProblemToken, $Response)
		{
			$Response = $this->Request('index.php', array('r' => 'juego/respuesta', 'tokenSolicitud' => $ProblemToken, 'solucion' => $Response));

			if($Response !== false)
			{
				if(!isset($Response['error']))
					return new SolutionResponse($Response['codigo'], $Response['mensaje'], $Response['tiempoSolicitud'], $Response['tiempoRespuesta'], $Response['tiempo']);
				else
					return $Response['error'];
			}

			return false;
		}

		private function Request($Request, $Parameters)
		{
			$URL = self::ENDPOINT;

			if(is_array($Request))
			{
				foreach($Request as $Key => $Value)
				{
					if(is_int($Key))
						$URL .= "/{$Value}";
					else
						$URL .= "/{$Key}/{$Value}";
				}
			}
			else
				$URL .= '/' . $Request;

			if(!empty($Parameters) && is_array($Parameters))
				$URL .= '?' . http_build_query($Parameters);

			$Response = file_get_contents($URL);

			if($Response !== false)
			{
				$Response = json_decode($Response, true);

				if($Response !== null && is_array($Response))
					return $Response;
			}

			return false;
		}
	}