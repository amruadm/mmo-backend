<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Данные регистрации пользователя.
 */
class RegisterData
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="32")
     * @Serializer\Type("string")
     * @SWG\Property(type="string", description="Имя пользователя")
     */
    public $login;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="32")
     * @Serializer\Type("string")
     * @SWG\Property(type="string", description="Пароль")
     */
    public $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="256")
     * @Assert\Regex("/.+@.+\..+/")
     * @Serializer\Type("string")
     * @SWG\Property(type="string", description="E-mail")
     */
    public $email;
}