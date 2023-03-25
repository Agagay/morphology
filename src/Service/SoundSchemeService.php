<?php

namespace App\Service;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class SoundSchemeService
{
    const ALPHABET = [
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н',
        'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
    ];

    const VOWELS = ['а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я'];

    const IOTATED = ['e', 'ё', 'ю', 'я'];

    const SOFTENING_VOWELS = ['е', 'ё', 'и', 'ю', 'я'];

    public function parse(string $text): array {
        $chars = mb_str_split($text);
        $result = [];
        for ($i=0; $i<count($chars); $i++) {
            if (empty(trim($chars[$i]))) {
                $result[] = $this->parseEmptyChar();
            } else if ($this->isVowel($chars[$i])) {
                $result[] = $this->parseVowelChar($chars[$i]);
            } else {
                $result[] = $this->parseConsonantChar(
                    $chars[$i],
                    array_key_exists($i+1, $chars) ? $chars[$i+1] : '',
                );
            }
        }

        return $result;
    }

    private function parseEmptyChar(): array {
        return [
            'letter' => '',
            'sounds' => [
                [
                    'val' => '',
                    'params' => [
                        'vowel' => false,
                        'consonant' => false,
                        'soft' => false,
                        'solid' => false,
                    ],
                ],
            ],
        ];
    }

    private function parseVowelChar(string $char): array {
        return [
            'letter' => $char,
            'sounds' => [
                [
                    'val' => $char,
                    'params' => [
                        'vowel' => true,
                        'consonant' => false,
                        'soft' => false,
                        'solid' => false,
                    ],
                ],
            ],
        ];
    }

    private function parseConsonantChar(string $char, string $nextChar): array {
        $isSoft = $this->isSoft($char,  $nextChar);
        return [
            'letter' => $char,
            'sounds' => [
                [
                    'val' => $char,
                    'params' => [
                        'vowel' => false,
                        'consonant' => true,
                        'soft' => $isSoft,
                        'solid' => !$isSoft,
                    ],
                ],
            ],

        ];
    }

    private function isVowel(string $char): bool {
        return in_array(strtolower($char), self::VOWELS, true);
    }

    private function isIotated(string $char): bool {
        return in_array(strtolower($char), self::IOTATED, true);
    }

    #[Pure]
    private function isSoft(string $char, string $nextChar): bool {
        if ($this->isVowel($char)) {
            return false;
        }

        if ($nextChar === '') {
            return false;
        }

        return $this->isVowel($nextChar) && $this->isSoftening($nextChar);
    }

    #[Pure]
    private function isSoftening(string $char): bool {
        if (!$this->isVowel($char)) {
            return false;
        }

        return in_array($char, self::SOFTENING_VOWELS, true);
    }
}