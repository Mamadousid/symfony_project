<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $contact = $this->createContact();

        $manager->persist($contact);

        $manager->flush();
    }

    private function createContact(): Contact
    {
        $contact = new Contact();

        $contact->setFirstName("Moussa");
        $contact->setLastName("Toure");
        $contact->setEmail("Moussa@gmail.com");
        $contact->setPhone("07 01 02 03 04");
        $contact->setMessage("Bonjour, je voudrais réserver une formules");

        return $contact;
        
    }
}
