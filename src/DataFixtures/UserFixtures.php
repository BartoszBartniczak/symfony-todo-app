<?php
/**
 * Created by PhpStorm.
 * User: bartosz
 */

namespace App\DataFixtures;


use App\Entity\User;
use App\Service\UuidGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var
     */
    private $uuidGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UuidGenerator $uuidGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User($this->uuidGenerator->generate(), 'user', '');
        $passwordHash = $this->passwordEncoder->encodePassword($user, 'zaq12wsx');
        $user->setPassword($passwordHash);

        $manager->persist($user);
        $manager->flush();
    }


}
