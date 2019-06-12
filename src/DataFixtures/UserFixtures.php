<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    const TEST_USER_1 = 'testUser1';
    const TEST_PASS_1 = 'testtest';

    const TEST_USER_2 = 'testUser2';
    const TEST_PASS_2 = 'testtest';

    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setLogin(static::TEST_USER_1);
        $user->setPassword($this->passwordEncoder->encodePassword($user, static::TEST_PASS_1));

        $manager->persist($user);

        $user = new User();
        $user->setLogin(static::TEST_USER_2);
        $user->setPassword($this->passwordEncoder->encodePassword($user, static::TEST_PASS_2));

        $manager->persist($user);

        $manager->flush();
    }
}
