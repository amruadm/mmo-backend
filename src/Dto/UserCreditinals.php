<?php

namespace App\Dto;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class UserCreditinals {

    /**
     * @Assert\NotBlank()
     */
    public $login;

    /**
     * @Assert\NotBlank()
     * @UserPassword()
     */
    public $password;
}