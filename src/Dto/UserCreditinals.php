<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class UserCreditinals
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
}