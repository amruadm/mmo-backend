<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Dto\RegisterData;
use App\Dto\UserCreditinals;
use App\Dto\UserInfo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

    private function getClientResponse(): Response {
        return $this->client->getResponse();
    }

    protected function checkExistsFixtureUser() {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['login' => UserFixtures::TEST_USER_1]);

        $this->assertNotNull($user, 'Fixture user ' . UserFixtures::TEST_USER_1 . ' doesn\' exists');
    }

    public function testCheckAuth() {
        $this->checkExistsFixtureUser();

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

    public function testRegister() {
        $generatedLogin = 'testuser_' . time();

        $formData           = new RegisterData();
        $formData->login    = $generatedLogin;
        $formData->password = 'testpassword';
        $formData->email    = 'test@test.test';

        $jsonData = $this->getSerializer()->serialize($formData, 'json');

        $this->client->request('POST', '/api/v1/user/register', [], [], $this->getRequestHeaders(), $jsonData);
        $response = $this->getClientResponse();

        /** @var UserInfo $userInfo */
        $userInfo = $this->getSerializer()->deserialize($response->getContent(), UserInfo::class, 'json');

        $this->assertEquals($response->getStatusCode(), Response::HTTP_CREATED, 'Response code not is 201 (HTTP_CREATED)');
        $this->assertNotEmpty($userInfo->id, 'Response has not user id');
        $this->assertEquals($generatedLogin, $userInfo->login, 'Response has not correct user login');
    }

    public function testRegisterExistsUser() {
        $this->checkExistsFixtureUser();

        $formData           = new RegisterData();
        $formData->login    = UserFixtures::TEST_USER_1;
        $formData->password = 'testpassword';
        $formData->email    = 'test@test.test';

        $jsonData = $this->getSerializer()->serialize($formData, 'json');

        $this->client->request('POST', '/api/v1/user/register', [], [], $this->getRequestHeaders(), $jsonData);
        $response = $this->getClientResponse();

        $this->assertNotEquals(Response::HTTP_CREATED, $response->getStatusCode(), 'Response code must be error');

        $users = $this->entityManager->getRepository(User::class)->findBy(['login' => UserFixtures::TEST_USER_1]);

        $this->assertFalse(count($users) > 1, 'Too many users with identical username');
    }

    public function testCheckUsername() {
        $this->checkExistsFixtureUser();

        $this->client->request('POST', '/api/v1/user/check-username/' . UserFixtures::TEST_USER_1, [], [], $this->getRequestHeaders());
        $this->assertTrue('true' === $this->getClientResponse()->getContent(), 'Action must return true for exists user');

        $this->client->request('POST', '/api/v1/user/check-username/' . 'testuser_' . time(), [], [], $this->getRequestHeaders());
        $this->assertTrue($this->getClientResponse()->isClientError(), 'Response code must be an error for not exists user');
    }
}