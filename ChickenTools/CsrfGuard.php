<?php

	namespace ChickenTools;

	class CsrfGuard
	{

		public static $sessionName = "ChickenToolsCsrfGuard";


		public static function register(&$name, &$token)
		{

			// Read/create CSRF session
			if (!array_key_exists(self::$sessionName, $_SESSION)) {
				$_SESSION[self::$sessionName] = array("count" => 0);
			}


			// Count up!
			$count = ++$_SESSION[self::$sessionName]['count'];

			// Generate name
			if (is_null($name) || empty($name)) {
				$name = Str::random(12) . '/' . $count;
			}

			// Generate token
			$token = hash("sha512", mt_rand(0, mt_getrandmax()));

			// Add to session
			$_SESSION[self::$sessionName][$name] = $token;

		}

		
		public static function validate($name, $token) 
		{

			// Anything?
			if (!array_key_exists(self::$sessionName, $_SESSION)) {
				return false;
			}

			// Read the session
			$tokens = $_SESSION[self::$sessionName];
			if (array_key_exists($name, $tokens)) {

				// Does the token match?
				if ($tokens[$name] != $token) {
					return false;
				}

				// It matches; now remove it from the session
				unset($_SESSION[self::$sessionName][$name]);
				return true;

			} else {

				// No such name found...
				return false;
			}


		}



	}


?>