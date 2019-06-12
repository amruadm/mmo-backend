<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Dto\UserCreditinals;
use App\Dto\UserInfo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserControllerTest extends WebTestCase {
    /** @var KernelBrowser */
    private $client;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SerializerInterface */
    private $serializer = null;

    public function setUp() {
        $this->client = static::createClient();
        $kernel       = $this->bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        parent::setUp();
    }

    private function getSerializer(): SerializerInterface {
        if (null === $this->serializer) {
            $this->serializer = SerializerBuilder::create()->build();
        }

        return $this->serializer;
    }

    private function getRequestHeaders(): array {
        return [
            'CONTENT_TYPE' => 'application/json',
        ];
    }

    private function getClientResponse(): JsonResponse {
        return $this->client->getResponse();
    }

    public function testCheckAuth() {
        $userCreditinals           = new UserCreditinals();
        $userCreditinals->login    = UserFixtures::TEST_USER_1;
        $userCreditinals->password = UserFixtures::TEST_PASS_1;

        $jsonData = $this->getSerializer()->serialize($userCreditinals, 'json');

        $this->client->request('POST', '/api/v1/user/check-auth', [], [], $this->getRequestHeaders(), $jsonData);

        $response = $this->getClientResponse();

        /** @var UserInfo $userInfo */
        $userInfo = $this->getSerializer()->deserialize($response->getContent(), UserInfo::class, 'json');

        $this->assertTrue($userInfo instanceof UserInfo, 'Response not is instance of UserInfo');

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['login' => UserFixtures::TEST_USER_1]);

        $isValidUser = ($userInfo->id === $user->getId() && $userInfo->login === $user->getLogin());
        $this->assertTrue($isValidUser, 'Result user is not valid');
    }

//    public function testRegister() {
//        $generatedLogin = 'testuser_' . time();
//
//        $formData           = new RegisterData();
//        $formData->login    = $generatedLogin;
//        $formData->password = 'testpassword';
//        $formData->confirm  = 'testpassword';
//        $formData->email    = 'test@test.test';
//
//        $jsonData = $this->serializer->serialize($formData, 'json');
//
//        $this->client->request('POST', '/api/v1/user/register', [], [], $this->getRequestHeaders(), $jsonData);
//        $response = $this->getClientResponse();
//
//        $response->getContent();
//    }
}