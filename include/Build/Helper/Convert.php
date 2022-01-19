<?php

namespace Build\Helper;

class Convert {

	/** @var int $byteCountGB Number of bytes in a GB */
	private static int $byteCountGB = 1073741824;

	/** @var int $byteCountMB Number of bytes in a MB */
	private static int $byteCountMB = 1048576;

	/** @var int $byteCountKB Number of bytes in a KB */
	private static int $byteCountKB = 1024;

	/** @var int $numDecimalPlaces Number of decimal places to round to. */
	private static int $numDecimalPlaces = 2;

	/** @var int $minByteCount Minimum byte count */
	private static int $minByteCount = 1;

	/**
	 * Convert file size from bytes into a readable format (GB, MB, KB)
	 *
	 * @param int $bytes File size in bytes
	 * @return string $string Formatted file size
	 */
	public static function fileSize(int $bytes = 0) {
		if ($bytes >= self::$byteCountGB) { // 1GB or greater
			$string = round($bytes / self::$byteCountGB, self::$numDecimalPlaces) . ' GB';

		} elseif ($bytes >= self::$byteCountMB) { // 1MB or greater
			$string = round($bytes / self::$byteCountMB, self::$numDecimalPlaces) . ' MB';

		} elseif ($bytes >= self::$byteCountKB) { // 1KB or greater
			$string = round($bytes / self::$byteCountKB, self::$numDecimalPlaces) . ' KB';

		} elseif ($bytes > self::$minByteCount) { // Greater than 1 byte
			$string = $bytes . ' bytes';

		} elseif ($bytes === self::$minByteCount) { // 1 byte
			$string = $bytes . ' byte';

		} else { // 0 bytes
			$string = '0 bytes';
		}

		return $string;
	}
}
