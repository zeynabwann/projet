<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Dette;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 100; $i++) {

            $client = new Client();
            $client->setSurname('Nom' . $i);
            $client->setTelephone('78100111' . $i); 
            $client->setAdresse('Adresse' . $i);

            if ($i % 2 == 0) {
                $user = new User();
                $user->setNom('Nom' . $i);
                $user->setPrenom('Prenom' . $i);
                $user->setLogin('login' . $i);
                $plaintextPassword = "password";

                $hashedPassword = $this->encoder->hashPassword(
                    $user, 
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $client->setUser($user);

                for ($j = 1; $j <= 2; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(150000 * $j);
                    $dette->setMontantVerser(150000 * $j);
                    $client->addDette($dette);
                }
            }
            else{

                for ($j = 1; $j <= 2; $j++) {
                    $dette = new Dette();
                    $dette->setMontant(150000 * $j);
                    $dette->setMontantVerser(150000);
                    $client->addDette($dette);
                }
            }
            $manager->persist($client);
        }


        $manager->flush(); 
    }
}