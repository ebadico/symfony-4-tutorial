<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var array
     */
    private const USERS = [
        [
            'username'  => 'john_doe',
            'email'     => 'john_doe@doe.com',
            'password'  => 'john123',
            'fullName'  => 'John Doe',
            'roles'     => [User::ROLE_USER]
        ],
        [
            'username'  => 'rob_smith',
            'email'     => 'rob_smith@smith.com',
            'password'  => 'rob12345',
            'fullName'  => 'Rob Smith',
            'roles'     => [User::ROLE_USER]
        ],
        [
            'username'  => 'marry_gold',
            'email'     => 'marry_gold@gold.com',
            'password'  => 'marry12345',
            'fullName'  => 'Marry Gold',
            'roles'     => [User::ROLE_USER]
        ],
        [
            'username'  => 'super_admin',
            'email'     => 'super_admin@admin.com',
            'password'  => 'admin12345',
            'fullName'  => 'Super Admin',
            'roles'     => [User::ROLE_ADMIN]
        ],
    ];

    /**
     * @var array
     */
    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadMicroPosts(ObjectManager $manager): void
    {
        for ($i = 0; $i < 30; $i++) {
            $micropost = new MicroPost();
            $micropost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) -1)]);
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $micropost->setTime($date);
            $micropost->setUser($this->getReference(self::USERS[rand(0, count(self::USERS) -1)]['username']));
            $manager->persist($micropost);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadUsers(ObjectManager $manager)
    {
        foreach(self::USERS as $userData) {

            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userData['password']
                )
            );
            $user->setRoles($userData['roles']);

            $this->addReference($userData['username'], $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
