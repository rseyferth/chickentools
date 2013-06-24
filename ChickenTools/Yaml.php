<?php

	namespace ChickenTools;

	class Yaml
	{

		public static function load($filename)
		{

			// Yaml lib loaded?
			if (function_exists("yaml_parse_file")) {

				return yaml_parse_file($filename);

			} else {

				// Create deserializer
				return \Symfony\Component\Yaml\Yaml::parse($filename);

			}

		}


	}


?>