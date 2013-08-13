<?php

	namespace ChickenTools;

	class Str
	{

		private static $plural = array(
			'/(quiz)$/i'               => "$1zes",
			'/^(ox)$/i'                => "$1en",
			'/([m|l])ouse$/i'          => "$1ice",
			'/(matr|vert|ind)ix|ex$/i' => "$1ices",
			'/(x|ch|ss|sh)$/i'         => "$1es",
			'/([^aeiouy]|qu)y$/i'      => "$1ies",
			'/(hive)$/i'               => "$1s",
			'/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
			'/(shea|lea|loa|thie)f$/i' => "$1ves",
			'/sis$/i'                  => "ses",
			'/([ti])um$/i'             => "$1a",
			'/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
			'/(bu)s$/i'                => "$1ses",
			'/(alias)$/i'              => "$1es",
			'/(octop)us$/i'            => "$1i",
			'/(ax|test)is$/i'          => "$1es",
			'/(us)$/i'                 => "$1es",
			'/s$/i'                    => "s",
			'/$/'                      => "s"
		);

		private static $singular = array(
			'/(quiz)zes$/i'             => "$1",
			'/(matr)ices$/i'            => "$1ix",
			'/(vert|ind)ices$/i'        => "$1ex",
			'/^(ox)en$/i'               => "$1",
			'/(alias)es$/i'             => "$1",
			'/(octop|vir)i$/i'          => "$1us",
			'/(cris|ax|test)es$/i'      => "$1is",
			'/(shoe)s$/i'               => "$1",
			'/(o)es$/i'                 => "$1",
			'/(bus)es$/i'               => "$1",
			'/([m|l])ice$/i'            => "$1ouse",
			'/(x|ch|ss|sh)es$/i'        => "$1",
			'/(m)ovies$/i'              => "$1ovie",
			'/(s)eries$/i'              => "$1eries",
			'/([^aeiouy]|qu)ies$/i'     => "$1y",
			'/([lr])ves$/i'             => "$1f",
			'/(tive)s$/i'               => "$1",
			'/(hive)s$/i'               => "$1",
			'/(li|wi|kni)ves$/i'        => "$1fe",
			'/(shea|loa|lea|thie)ves$/i'=> "$1f",
			'/(^analy)ses$/i'           => "$1sis",
			'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",
			'/([ti])a$/i'               => "$1um",
			'/(n)ews$/i'                => "$1ews",
			'/(h|bl)ouses$/i'           => "$1ouse",
			'/(corpse)s$/i'             => "$1",
			'/(us)es$/i'                => "$1",
			'/(us|ss)$/i'               => "$1",
			'/s$/i'                     => ""
		);

		private static $irregular = array(
			'move'   => 'moves',
			'foot'   => 'feet',
			'goose'  => 'geese',
			'sex'    => 'sexes',
			'child'  => 'children',
			'man'    => 'men',
			'tooth'  => 'teeth',
			'person' => 'people'
		);

		private static $uncountable = array(
			'sheep',
			'fish',
			'deer',
			'series',
			'species',
			'money',
			'rice',
			'information',
			'equipment',
			'media'
		);
		
		/**
		 * Generate a random string
		 * @param  integer $length (default: 10)
		 * @param  string  $charset (default: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789')
		 * @return string
		 */
		public static function random($length = 10, $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789") {
			srand((double)microtime()*1000000);
			$i = 0;
			$result = ''; $numChars = strlen($charset);
			while ($i <= $length) {

				$num = rand() % $numChars;
				$tmp = substr($charset, $num, 1);
				$result = $result . $tmp;
				$i++;

			}
			return $result;

		}

		/**
		 * Convert string to URL-safe slug
		 * @param  string $input The input string
		 * @param  string $spaceChar (default: '-') The character/string to use as a seperator
		 * @return string The resulting slug
		 */
		public static function slugify($input, $spaceChar = '-') {

			$str = strtolower(trim($input));
			$str = preg_replace('/[^a-z0-9-]/', $spaceChar, $str);
			$str = preg_replace('/-+/', "-", $str);
			return $str;

		}

		/**
		 * Titleize the given string (upper-case on first character)
		 * @param  string $input
		 * @return string
		 */
		public static function titleize($input) {
			return ucfirst($input);
		}


		/**
		 * Pluralize the given string (if count is not 1)
		 * @param  string  $input
		 * @param  integer $count The number of items. If this is 1, the singular will be returned.
		 * @return string
		 */
		public static function pluralize($input, $count = 2) {

			// Only one item?
			if ($count == 1) {
				return $input;
			}

			// Save some time in the case that singular and plural are the same
			if (in_array(strtolower($input), self::$uncountable)) {
				return $input;
			}

			// Check for irregular singular forms
			foreach (self::$irregular as $pattern => $result) {
				$pattern = '/' . $pattern . '$/i';
				if (preg_match($pattern, $input)) {
					return preg_replace( $pattern, $result, $input);
				}
			}

			// Check for matches using regular expressions
			foreach (self::$plural as $pattern => $result) {
				if (preg_match($pattern, $input)) {
					return preg_replace($pattern, $result, $input);
				}
			}

			return $input;

		}

		public static function singularize($input) {

			// Save some time in the case that singular and plural are the same
			if (in_array(strtolower($input), self::$uncountable)) {
				return $input;
			}

			// Check for irregular plural forms
			foreach (self::$irregular as $result => $pattern) {
				$pattern = '/' . $pattern . '$/i';
				if (preg_match($pattern, $input)) {
					return preg_replace( $pattern, $result, $input);
				}
			}

			// Check for matches using regular expressions
			foreach (self::$singular as $pattern => $result) {
				if (preg_match($pattern, $input)) {
					return preg_replace($pattern, $result, $input);
				}
			}

			return $input;
			
		}


		/**
		 * Remove accents from characters in given string
		 * @param  string $input The input string
		 * @param  string $encoding (default: UTF-8)
		 * @return string The string without accents
		 */
		public static function unaccent($input, $encoding = 'UTF-8') {
			return preg_replace('/&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);/i', '$1', htmlentities($input, ENT_COMPAT, $encoding));
		}


		/**
		 * Get the content extension for given filename
		 * @param  string $filename The filename to check
		 * @param  string $suffix   (default: '.php') Possible suffix, that is not to be treated as an extension
		 * @return string|false           The extension, or false when no extension was found.
		 */
		public static function getContentExtension($filename, $suffix = '.php') 
		{

			// Extension?
			if (preg_match('/\.([a-z]{2,5})(' . preg_quote($suffix) . ')?$/', $filename, $extension)) {
				return $extension[1];	
			} else {
				return false;
			}


		}


		/**
		 * Get the namespaces for the given className
		 * @param  string   The qualified class name 
		 * @return array 	Array of namespaces contained in the classname (the last element will be the classname itself)
		 */
		public static function getNamespaces($className)
		{
			// Are there any namespaces?
			if (self::hasNamespace($className)) {
				return explode('\\', $className);
			} else {
				return null;
			}

		}

		/**
		 * Check if given classname is qualified with a namespace.
		 * @param  string  		The classname
		 * @return boolean      True when a namespace is defined in the classname, otherwise false.
		 */
		public static function hasNamespace($className)
		{
			// Any \'s in there?
			return (strpos($className, '\\') !== false);
		}

		/**
		 * Check if given classname has an absolute namespace defined (meaning it starts with a backslash)
		 * @param  string   The classname
		 * @return boolean  True when classname starts with a backslash.
		 */
		public static function hasAbsoluteNamespace($className)
		{
			return $className[0] == '\\';
		}

		/**
		 * Remove namespace qualification from given classname
		 * @param  string  The classname
		 * @return string            The classname without any namespaces.
		 */
		public static function removeNamespace($className) 
		{
			$index = strrpos($className, "\\");
			if ($index == false) return $className;
			return substr($className, $index + 1);
		}

	}

?>