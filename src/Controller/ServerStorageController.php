<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Контроллер для работы с хранилищем данных игровых серверов.
 */
class ServerStorageController extends AbstractFOSRestController
{

	/**
	 * Получение состояния игрового мира (без состояния пользователей).
	 *
	 * @param int $serverId Идентификатор сервера.
	 *
	 * @Rest\Get("/api/v1/storage/load-world-data")
	 */
	public function loadWorldData(int $serverId) {
		// todo!
	}

	/**
	 * Сохранение состояния игрового мира.
	 *
	 * @param int    $serverId  Идентификатор сервера.
	 * @param string $worldData Данные.
	 *
	 * @Rest\Post("/api/v1/storage/save-world-data")
	 */
	public function saveWorldData(int $serverId, string $worldData) {
		// todo!
	}

	/**
	 * Получить состояние пользователя.
	 *
	 * @param int $serverId Идентификатор сервера.
	 * @param int $userId   Идентификатор пользователя.
	 *
	 * @Rest\Get("/api/v1/storage/get-user-data")
	 */
	public function getUserData(int $serverId, int $userId) {
		// todo!
	}

	/**
	 * Сохранить данные пользователя.
	 *
	 * @param int    $serverId Идентификатор сервера.
	 * @param int    $userId   Идентификатор пользователя.
	 * @param string $userData Пользовательские данные.
	 *
	 * @Rest\Post("/api/v1/storage/save-user-data")
	 */
	public function saveUserData(int $serverId, int $userId, string $userData) {
		// todo!
	}

	/**
	 * Получение всех пользовательских данных.
	 *
	 * @param int $serverId Идентификатор сервера.
	 *
	 * @Rest\Post("/api/v1/storage/get-all-user-data")
	 */
	public function getAllUserData(int $serverId) {
		// todo!
	}
}
