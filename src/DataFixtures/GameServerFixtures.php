<?php

namespace App\DataFixtures;

use App\Entity\GameServer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Фикстуры информации об игровых серверах
 */
class GameServerFixtures extends Fixture
{
    const TEST_SERVER_NAME = 'test_server';
    const TEST_SERVER_ADDR = '127.0.0.1:7777';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager) {
        $server = new GameServer();
        $server->setName(static::TEST_SERVER_NAME);
        $server->setAddr(static::TEST_SERVER_ADDR);
        $server->setEnabled(true);

        $manager->persist($server);

        $manager->flush();
    }
}