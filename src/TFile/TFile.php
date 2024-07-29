<?php declare(strict_types=1);

namespace Torugo\Util\TFile;

use InvalidArgumentException;

class TFile
{
    /**
     * Manipulates text files parsing or adding content.
     * @param string $path Path to textfile
     * @throws \InvalidArgumentException
     */
    public function __construct(private string $path)
    {
        if (!self::exists($path)) {
            throw new InvalidArgumentException("TFile: The file does not exist or is not readable.");
        }
    }


    /**
     * Check if file exists
     * @param string $path
     * @param string $createIfNotExists
     * @return bool
     */
    public static function exists(string $path, bool $createIfNotExists = false): bool
    {
        $file = @fopen($path, "r");

        if ($file) {
            fclose($file);
            return true;
        }

        if ($createIfNotExists) {
            return self::create($path);
        }

        return false;
    }


    /**
     * Creates a file on a given path
     * @param string $path Path where file will be created
     * @return bool Return if file was created or not
     */
    public static function create(string $path): bool
    {
        $file = @fopen($path, "a");

        if ($file) {
            fclose($file);
            return true;
        }

        return false;
    }


    /**
     * Returns the lines of a text file as an array
     * @return array
     */
    public function getLines(): array
    {
        $lines = @file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $lines ?? [];
    }


    /**
     * Parses the content of an env file and returns it as an associative array.
     * @return array
     */
    public function parseEnv(): array
    {
        $lines = $this->getLines();

        $result = [];
        foreach ($lines as $line) {
            $line = trim($line);

            if (!$this->isEnvLineValid($line)) {
                continue;
            }

            [$key, $value] = $this->parseEnvLine($line);
            $result[$key] = $value;
        }

        return $result;
    }


    /**
     * Checks if a line of an env file is valid
     * @param string $line
     * @return bool
     */
    private function isEnvLineValid(string $line): bool
    {
        if (empty($line)) {
            return false;
        }

        // Commented lines
        if ($line[0] === "#") {
            return false;
        }

        // Invalid lines
        if (!preg_match("/^[a-zA-Z0-9_\s]{1,}=.*$/", $line)) {
            return false;
        }

        return true;
    }


    private function parseEnvLine(string $line): array
    {
        $parts = explode("=", $line);
        $key = $parts[0];
        $value = $parts[1] ?? "";

        $key = $this->clearString($key);
        $value = $this->clearString($value ?? "");

        return [$key, $value];
    }


    private function clearString(?string $str): string
    {
        $str = trim($str ?? "", " \n\r\t\v\x00");

        // Is between double or single quotes
        if (preg_match("/(^\".*\"$)|(^\'.*\'$)/", $str)) {
            $str = substr($str, 1, -1);
        }

        return $str;
    }


    /**
     * Loads a JSON file content and returns it as an associative array. In case of invalidation returns an empty array.
     * @param int $depth Maximum nesting depth of the structure being decoded. The value must be greater than `0`, and less than or equal to `2147483647`.
     * @param int $flags Bitmask of `JSON_BIGINT_AS_STRING`, `JSON_INVALID_UTF8_IGNORE`, `JSON_INVALID_UTF8_SUBSTITUTE`, `JSON_THROW_ON_ERROR`. The behaviour of these constants is described on the JSON constants page.
     * @return array
     */
    public function parseJson(
        int $depth = 512,
        int $flags = 0
    ): array {
        $content = @file_get_contents($this->path);

        if (!$content) {
            return [];
        }

        $json = json_decode($content, true, $depth, $flags);

        return $json ?? [];
    }


    /**
     * Checks whether a file is writable
     * @return bool
     */
    public function isWritable(): bool
    {
        return is_writable($this->path);
    }
}
