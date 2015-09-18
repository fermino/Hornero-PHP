<?php
	class Problem
	{
		public $Name = null;
		public $Token = null;

		public $Parameters = array();

		public function __construct($Name, $Token, Array $Parameters)
		{
			$this->Name = (string) $Name;
			$this->Token = (string) $Token;
			$this->Parameters = $Parameters;
		}
	}