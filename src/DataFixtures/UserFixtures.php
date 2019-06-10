<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    const TEST_USER_1 = '';

    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setLogin('testUser1');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'testtest'));

        $manager->persist($user);

        $user = new User();
        $user->setLogin('testUser2');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'testtest'));

        $manager->persist($user);

        $manager->flush();
    }
}
