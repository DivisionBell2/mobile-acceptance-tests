<?php
/**
 * @description Класс, регистирующий пользователя в системе
 */

namespace AT\Helpers\RegisterUser;

use AcceptanceCore\Core\ATException;
use AcceptanceCore\Core\Env;
use AcceptanceCore\Core\WB;
use GuzzleHttp\Client;

class RegisteredUser
{
    private $requestedNameEntered;
    private $createdEmail;
    private $createdNameEntered;
    private $createdPassword;

    public function __construct($userNameEntered = 'Test User', Client $guzzleClient = null)
    {
        if ($guzzleClient) {
            $this->guzzleClient = $guzzleClient;
        } else {
            $this->guzzleClient = new Client(['defaults' => ['verify' => false]]);
        }
        $this->createdPassword = UserDataGenerator::PASSWORD;
        $this->setRequestedNameEntered($userNameEntered);
        $registerUserResult = $this->generateUser();
        $this->createdEmail = $registerUserResult['email'];
        $this->createdNameEntered = $registerUserResult['name_entered'];
    }

    public function generateUser(): array
    {
        $registerUrl = Env::getInstance()->url() . '/api/register';
        $userDataGenerator = new UserDataGenerator();
        $userEmail = $userDataGenerator->getRandomEmail();
        $response = $this->guzzleClient->request('POST', $registerUrl, [
            'form_params' => [
                'subscribe' => 0,
                'sex' => 2,
                'password' => $this->createdPassword,
                'phone' => $userDataGenerator->getRandomPhoneNumber(),
                'email' => $userEmail,
                'name_entered' => $this->requestedNameEntered,
            ]
        ]);
        $bodyResponse = $response->getBody()->getContents();
        $resultArr = json_decode($bodyResponse, true);
        if (isset($resultArr['user']['email'])) {
            WB::step('Создан новый пользователь c email: ' . $resultArr['user']['email']);
        } else {
            throw new ATException('Пользователь с запрошенным email ' . $this->getRandomEmail() . ' не был создан');
        }

        return $resultArr['user'];
    }

    public function getCreatedUserEmail(): string
    {
        return $this->createdEmail;
    }

    public function getCreatedUserPassword(): string
    {
        return $this->createdPassword;
    }

    public function getCreatedNameEntered(): string
    {
        return $this->createdNameEntered;
    }

    public function setRequestedNameEntered(string $userName)
    {
        $this->requestedNameEntered = $userName;
    }

    public function getRandomPhoneNumber(): string
    {
        $prefix = 900;
        $generatePostfixNumber = rand(10000000, 99999999);
        return $prefix . $generatePostfixNumber;
    }

    public function getRandomEmail(): string
    {
        $prefix = (string) rand(100000, 999999);
        $generatePostfixNumber = '@test.test';
        return $prefix . $generatePostfixNumber;
    }
}
