<?php
	class SolutionResponse
	{
		public $Code = null;
		public $Message = null;

		public $StartTime = null;
		public $EndTime = null;
		public $Time = null;

		public function __construct($Code, $Message, $StartTime, $EndTime, $Time)
		{
			$this->Code = (int) $Code;
			$this->Message = (string) $Message;

			$this->StartTime = (float) $StartTime;
			$this->EndTime = (float) $EndTime;
			$this->Time = (int) $Time;
		}
	}