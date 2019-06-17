<?php

namespace App\Controller;

use App\Dto\ServerInfo;
use App\Entity\GameServer;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Точка работы со списками игровых серверов.
 */
class ServersController extends AbstractFOSRestController
{
    /**
     * Получение списка доступных серверов.
     *
     * @param EntityManagerInterface $entityManager
     *
     * @Rest\Get("/api/v1/servers/available")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Список доступных игровых серверов",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=ServerInfo::class))
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function available(EntityManagerInterface $entityManager): JsonResponse {
        /** @var GameServer[] $servers */
        $servers = $entityManager
            ->getRepository(GameServer::class)
            ->findBy(['enabled' => true])
        ;

        $result = [];

        foreach ($servers as $server) {
            $result[] = $server->toInfo();
        }

        return $this->json($result);
    }
}