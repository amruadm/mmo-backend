<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;

/**
 * Упрощённая модель информации об сервере.
 */
class ServerInfo
{
    /**
     * Наименование.
     *
     * @Serializer\Type("string")
     * @SWG\Property(type="string", description="Наименование сервера")
     */
    public $name;

    /**
     * Адрес.
     *
     * @Serializer\Type("string")
     * @SWG\Property(type="string", description="Адресс сервера")
     */
    public $addr;

    /**
     * Порт.
     *
     * @Serializer\Type("integer")
     * @SWG\Property(type="integer", description="Порт сервера")
     */
    public $port;
}