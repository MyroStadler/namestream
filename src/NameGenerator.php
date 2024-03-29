<?php


namespace Myro\NameStream;


class NameGenerator implements StringGeneratorInterface
{
    // it's a rule, will never be shorter than this
    public $minWordLength = 2;

    // it's ideal, may be longer if ends in an illegal terminator
    public $idealMaxWordLength = 16;

    const ALL_LETTERS = 'abcdefghijklmnopqrst';
    const VOWEL_LETTERS = 'aeiou';
    const CONSONANT_LETTERS = 'bcdfghjklmnpqrstvxyz';
    const STREAMS = [
        'q' => 'u',
        'qu' => 'aeio',
        'd' => self::ALL_LETTERS,
        'dd' => 'abcefghijklmnopqrst',
        't' => self::ALL_LETTERS,
        'tt' => 'abcdefghijklmnopqrs',
        'td' => 'abcefghijklmnopqrs',
        'dt' => 'abcefghijklmnopqrs',
    ];
    const TERMINATORS = [
        'qu' => 0,
    ];
    const EXCLUDES = [
        'k' => 'c',
    ];

    public function g(): string {
        return $this->generate();
    }

    public function generate(): string {
        $word = '';
        while (!$this->isWordComplete($word)) {
            $word = $this->addLetterTo($word);
        }
        return ucfirst($word);
    }

    protected function isWordComplete(string $word): bool {
        $len = strlen($word);
        if ($len < $this->minWordLength) {
            return false;
        }
        if (array_key_exists($word, self::STREAMS)) {
            return false;
        }
        if (!$this->containsVowel($word)) {
            return false;
        }
        if ($this->isIllegalTerminator($word)) {
            return false;
        }
        if ($len >= $this->idealMaxWordLength) {
            return true;
        }
        $chance = 0.6 + 0.4 * ($len / $this->idealMaxWordLength);
        return $this->rand() >= $chance;
    }

    /**
     * 0.0 to 1.0 random value
     * @return float
     */
    protected function rand(): float {
        return mt_rand(0, mt_getrandmax()) / mt_getrandmax();
    }

    protected function addLetterTo(string $word): string {
        $i = 3;
        $from = null;
        while ($i > 0 && is_null($from)) {
            $n = $i--;
            if (strlen($word) < $n) {
                continue;
            }
            $last = substr($word, -$n);
            if (array_key_exists($last, self::STREAMS)) {
                $from = self::STREAMS[$last];
            }
        }
        if (is_null($from)) {
            if (strlen($word) > 1) {
                $last2 = substr($word, -2);
                if ($this->isAllVowels($last2)) {
                    $from = self::CONSONANT_LETTERS;
                } elseif ($this->isAllConsonants($last2)) {
                    $from = self::VOWEL_LETTERS;
                }
            }
        }
        $i = 3;
        while ($i > 0 && $from) {
            $n = $i--;
            if (strlen($word) < $n) {
                continue;
            }
            $last = substr($word, -$n);
            if (array_key_exists($last, self::EXCLUDES)) {
                $blocked = self::EXCLUDES[$last];
                for ($ii = 0; $ii < strlen($blocked); $ii++) {
                    $from = str_replace($blocked[$ii], '', $from);
                }
            }
        }
        return $word . $this->pick($from);
    }

    protected function pick(string $from = null): string {
        if (!$from) {
            $from = self::ALL_LETTERS;
        }
        return $from[mt_rand(0, strlen($from) - 1)];
    }

    protected function isIllegalTerminator(string $word): bool {
        $i = 3;
        while ($i > 0) {
            $n = $i--;
            if (strlen($word) < $n) {
                continue;
            }
            $last = substr($word, -$n);
            if (array_key_exists($last, self::TERMINATORS) && !self::TERMINATORS[$last]) {
                return true;
            }
        }
        return false;
    }

    protected function containsVowel(string $letters): bool {
        for ($i = 0; $i < strlen($letters); $i++){
            if (strpos(self::VOWEL_LETTERS, $letters[$i]) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function isAllVowels(string $letters): bool {
        for ($i = 0; $i < strlen($letters); $i++){
            if (strpos(self::VOWEL_LETTERS, $letters[$i]) === false) {
                return false;
            }
        }
        return true;
    }

    protected function isAllConsonants(string $letters): bool {
        for ($i = 0; $i < strlen($letters); $i++){
            if (strpos(self::CONSONANT_LETTERS, $letters[$i]) === false) {
                return false;
            }
        }
        return true;
    }
}