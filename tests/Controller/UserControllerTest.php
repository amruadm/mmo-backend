<?php


namespace App\Tests\Controller;

use App\Dto\UserCreditinals;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;

    public function setUp() {
        $this->client = static::createClient();
    }

    public function testCheckAuth() {
        $userCreditinals = new UserCreditinals();
        $userCreditinals->login = '';

        $crawler = $this->client->request('POST', '/api/v1/user/check-auth/');
    }
}