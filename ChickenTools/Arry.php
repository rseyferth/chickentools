<?php

	namespace ChickenTools;

	class Arry
	{


		public static function traverseKeys($array, $keys)
		{

			// Already an array?
			if (is_string($keys)) {
				$keys = explode(".", $keys);				
			}

			// First key exist?
			if (array_key_exists($keys[0], $array)) {
				
				// Last key?
				if (sizeof($keys) == 1) {

					// We found it
					return $array[$keys[0]];

				} else {

					// Look deeper
					$key = $keys[0];
					array_shift($keys);
					return self::traverseKeys($array[$key], $keys);

				}


			} else {
				return null;
			}

		}

		// http://snippets.dzone.com/posts/show/4660
		public static function flatten(array $array)
		{
			$i = 0;

			while ($i < count($array))
			{
				if (is_array($array[$i]))
					array_splice($array,$i,1,$array[$i]);
				else
					++$i;
			}
			return $array;
		}

		public static function mergeStatic($className, $propName, $recursiveMerge = true)
		{

			// Collection of arrays
			$arrays = array();

			// Find the class
			$reflClass = new \ReflectionClass($className);
			
			// Then loop parents
			do {
				$arrays[] = $reflClass->getStaticPropertyValue($propName, array());
			} while (false !== ($reflClass = $reflClass->getParentClass()));

			// Merge it!
			$arrays = array_reverse($arrays, true);
			if ($recursiveMerge) {
				$merged = call_user_func_array(array(__CLASS__, "mergeRecursiveDistinct"), $arrays);	
			} else {
				$merged = call_user_func_array("array_merge", $arrays);	
			}
			return $merged;

		}

		public static function contains($needle, array $haystack, $caseSensitive = true)
		{

			// Case sensitive?
			if ($caseSensitive) {
				return in_array($needle, $haystack);
			}

			// Loop through it
			foreach ($haystack as $value) {
				if (strtolower($needle) == strtolower($value)) {
					return true;
				}
			}
			return false;

		}


		public static function &mergeRecursiveDistinct()
		{
			$aArrays = func_get_args();
			$aMerged = $aArrays[0];
	
			for($i = 1; $i < count($aArrays); $i++)
			{
				if (is_array($aArrays[$i]))
				{
					foreach ($aArrays[$i] as $key => $val)
					{
						if (is_array($aArrays[$i][$key]))
						{
							$aMerged[$key] = array_key_exists($key, $aMerged) && is_array($aMerged[$key]) ? self::mergeRecursiveDistinct($aMerged[$key], $aArrays[$i][$key]) : $aArrays[$i][$key];
						}
						else
						{
							$aMerged[$key] = $val;
						}
					}
				}
			}
	
			return $aMerged;
		}

		/**
		 * Somewhat naive way to determine if an array is a hash.
		 */
		public static function isHash(&$array)
		{
			if (!is_array($array))
				return false;

			$keys = array_keys($array);
			return @is_string($keys[0]) ? true : false;
		}

		/**
		 * Wrap string definitions (if any) into arrays.
		 */
		public static function wrapStringsInArrays(&$strings)
		{
			if (!is_array($strings))
				$strings = array(array($strings));
			else 
			{
				foreach ($strings as &$str)
				{
					if (!is_array($str))
						$str = array($str);
				}
			}
			return $strings;
		}

	}






?>