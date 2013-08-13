<?php

	namespace ChickenTools;

	class File
	{

		public static function forDir($path, $regex, $callback)
		{

			$dh = opendir($path);
			if ($dh === false) return;
			while (false !== ($file = readdir($dh))) {
				if (preg_match($regex, $file)) {
					$callback($file);
				}
			}

		}


		public static function scanDir($path, $regex = null, $recursive = true, $includePath = false)
		{

			// Open dir
			$path = rtrim($path, '/ ');
			$dh = opendir($path);

			// Loop it.
			$files = array();
			while (false !== ($file = readdir($dh))) {
				
				// . or ..?
				if ($file == '.' || $file == '..') continue;

				// Is it a directory
				if (is_dir($path . '/' . $file)) {

					// Recursive?
					if ($recursive) {
						
						// Get subfiles
						$subFiles = File::scanDir($path . '/' . $file, $regex, $recursive, false);
						foreach ($subFiles as $subFile) {
							
							// Prefix with subdir
							$subFile = $file . '/' . $subFile;

							// Add file
							if ($includePath) {
								$files[] = $path . "/" . $subFile;
							} else {
								$files[] = $subFile;
							}

						}
						
					}

				} else {

					// Pattern to match?
					if ($regex && !preg_match($regex, $file)) continue;

					// Add file
					if ($includePath) {
						$files[] = $path . "/" . $file;
					} else {
						$files[] = $file;
					}

				}
				


			}

			return $files;

		}


	}


?>