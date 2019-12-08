<?php
/**
 * @description Класс, расширяющий возможности работы с массивами
 */

namespace Core\Utils;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\WB;

class Arrays extends \AcceptanceCore\Core\Utils\Arrays
{
    public static function checkForEmptyArray(
        array $targetArray,
        string $errorText = 'Массив пустой',
        bool $throwExceptionIfEmpty = true
    ) {
        if (count($targetArray) < 1) {
            if ($throwExceptionIfEmpty) {
                throw new ATException($errorText);
            }
            WB::debug($errorText);
        }
    }
}