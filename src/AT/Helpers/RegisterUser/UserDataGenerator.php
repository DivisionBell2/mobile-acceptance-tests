<?php
/**
 * @description Создает случайные почту и телефон пользователя
 */

namespace AT\Helpers\RegisterUser;

class UserDataGenerator
{
    const PASSWORD = '123456789';

    public function getRandomPhoneNumber(): string
    {
        $prefix = 900;
        $generatePostfix1 = rand(100, 999);
        $generatePostfix2 = rand(10, 99);
        $generatePostfix3 = rand(10, 99);
        return '+7' . "({$prefix})" . $generatePostfix1 . '-' . $generatePostfix2 . '-' . $generatePostfix3;
    }

    public function getRandomEmail(): string
    {
        $prefix = (string) rand(100000, 999999);
        $generatePostfixNumber = '@test.test';
        return $prefix . $generatePostfixNumber;
    }
}