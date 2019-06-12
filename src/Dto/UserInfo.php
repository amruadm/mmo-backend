<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;

/**
 * Краткая информация об пользователе.
 */
class UserInfo {
    /**
     * @Serializer\Type("integer")
     * @SWG\Property(type="integer", description="Идентификатор пользователя")
     */
    public $id;

    /**
     * @Serializer\Type("string")
     * @SWG\Property(type="string", description="Имя пользователя")
     */
    public $login;
}