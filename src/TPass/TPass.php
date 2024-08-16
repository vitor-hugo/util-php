<?php declare(strict_types=1);

namespace Torugo\Util\TPass;
use InvalidArgumentException;

/**
 * Generates random passwords, and checks password strength
 */
class TPass
{
    /**
     * Should include lowercased characters
     * @var bool
     */
    public bool $includeLowercase = true;

    /**
     * Should include uppercased characters
     * @var bool
     */
    public bool $includeUppercase = true;

    /**
     * Should include numeric characters
     * @var bool
     */
    public bool $includeNumbers = true;

    /**
     * Should include special characters
     * @var bool
     */
    public bool $includeSymbols = true;

    /**
     * Password should start with a letter character.
     * @var bool
     */
    public bool $beginWithALetter = false;

    /**
     * Should avoid sequential or identical characters side by side
     * @var bool
     */
    public bool $noSequentialChars = true;

    private string $lowers = "abcdefghijklmnopqrstuvwxyz";
    private string $uppers = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    private string $numbers = "0123456789";
    private string $symbols = '!;#$%&()*+,-./:;<=>?@[]^_{|}~';

    private string $letters = "";
    private string $chars = "";

    /**
     * Sets the special characters string. This method removes duplicated characters and spaces.
     * @param string $symbols Special characters used to generate the passowrd.
     * @return void
     */
    public function setSymbols(string $symbols)
    {
        $symbols = str_replace(" ", "", $symbols);

        if (empty($symbols)) {
            return;
        }

        // remove duplicated chars
        $this->symbols = count_chars($symbols, 3);
    }


    /**
     * Checks the strenght of a password
     * @param string $password
     * @return int 0 = very week, 1 = week, 2 = medium, 3 = good, 4 = strong
     */
    public function checkPasswordStrength(string $password): int
    {
        $score = 0;
        $length = strlen($password);

        if ($length >= 14) {
            $score += 4;
        } else if ($length > 8) {
            $score += 1;
        }

        // TEST => SCORE
        $tests = [
            "/[a-z]/" => 4,
            "/[A-Z]/" => 4,
            "/[0-9]/" => 4,
            "/\W/" => 4,
        ];

        foreach ($tests as $pattern => $points) {
            if (preg_match($pattern, $password)) {
                $score += $points;
            }
        }

        $finalScore = (int) floor($score / 5);

        if ($finalScore >= 4) {
            return 4;
        }

        $bad = [
            "/([a-zA-Z0-9\W])(\\1+){1,}/" => 4, // 2 or more consecutive repeated chars
            "/(01|12|23|34|45|56|67|78|89){1,}/" => 4, // sequential number
            "/(pass|word|admin|secret|my|qwe|senha|mudar){1,}/" => 4, // common mistakes
        ];

        foreach ($bad as $pattern => $points) {
            if (preg_match($pattern, $password, $matches)) {
                $score -= $points;
            }
        }

        $finalScore = (int) floor($score / 5);

        return $finalScore < 0 ? 0 : $finalScore;
    }


    /**
     * Generates random passwords with a given lentgh.
     * @param int $length Password length
     * @param int $quantity Quantity of generated passwords
     * @return string|array A string if quantity equals to one, otherwise an array
     */
    public function generate(int $length, int $quantity = 1): string|array
    {
        $this->validateOptions();
        $this->chars = $this->getActiveChars();
        $this->letters = $this->getActiveLetters();

        $passes = [];

        for ($i = 0; $i < $quantity; $i++) {
            $passes[] = $this->getRandomPassword($length);
        }

        return count($passes) == 1 ? $passes[0] : $passes;
    }


    private function validateOptions(): void
    {
        if (
            !$this->includeLowercase &&
            !$this->includeUppercase &&
            !$this->includeNumbers &&
            !$this->includeSymbols
        ) {
            throw new InvalidArgumentException("Can't generate a password, all source characters are disabled!");
        }

        if (
            !$this->includeLowercase &&
            !$this->includeUppercase &&
            $this->beginWithALetter
        ) {
            throw new InvalidArgumentException("Can't begin with a letter when Lowercase and Uppercase properties are disabled!");
        }
    }


    private function getActiveLetters(): string
    {
        $letters = "";

        if ($this->includeLowercase) {
            $letters .= $this->letters;
        }

        if ($this->includeUppercase) {
            $letters .= $this->uppers;
        }

        return $letters;
    }


    private function getActiveChars(): string
    {
        $chars = "";

        if ($this->includeLowercase) {
            $chars .= $this->lowers;
        }

        if ($this->includeUppercase) {
            $chars .= $this->uppers;
        }

        if ($this->includeNumbers) {
            $chars .= $this->numbers;
        }

        if ($this->includeSymbols) {
            $chars .= $this->symbols;
        }

        return $chars;
    }


    private function getRandomPassword(int $length): string
    {
        $pass = "";
        $char = " ";
        $lastChar = " ";

        for ($i = 0; $i < $length; $i++) {
            do {
                $char = $this->getRandomChar($i == 0 && $this->beginWithALetter);
            } while ($this->isNeighborOrEqual($char, $lastChar));

            $lastChar = $char;
            $pass .= $char;
        }

        return $pass;
    }


    private function getRandomChar(bool $onlyLetter = false): string
    {
        $lettersLen = strlen($this->letters) - 1;
        $charsLen = strlen($this->chars) - 1;

        if ($onlyLetter && $lettersLen > 0) {
            return $this->letters[mt_rand(0, $lettersLen)];
        }

        return $this->chars[mt_rand(0, $charsLen)];
    }


    private function isNeighborOrEqual(string $char1, string $char2): bool
    {
        $neighbor = mb_ord(mb_strtolower($char1)) - mb_ord(mb_strtolower($char2));
        return $neighbor >= -1 && $neighbor <= 1;
    }
}
