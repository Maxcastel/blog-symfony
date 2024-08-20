<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("admin@admin.com");
        $user->setFirstName("admin");
        $user->setName("admin");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user, 
                "admin"
            )
        );
        
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail("admin2@admin.com");
        $user2->setFirstName("admin2");
        $user2->setName("admin2");
        $user2->setRoles(["ROLE_ADMIN"]);
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2, 
                "admin"
            )
        );
        
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail("user@user.com");
        $user3->setFirstName("user");
        $user3->setName("user");
        $user3->setRoles(["ROLE_USER"]);
        $user3->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user3, 
                "user"
            )
        );
        
        $manager->persist($user3);

        $user4 = new User();
        $user4->setEmail("user2@user.com");
        $user4->setFirstName("user2");
        $user4->setName("user2");
        $user4->setRoles(["ROLE_USER"]);
        $user4->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user4, 
                "user"
            )
        );
        
        $manager->persist($user4);

        $manager->flush();
    }
}
