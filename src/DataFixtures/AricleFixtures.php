<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AricleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for($i =1; $i <=12; $i++){
            $article =new Article;
            $article->setTitle('Titre de l\'article n°' . $i)
                    ->setContent('Voici le contenu')
                    ->setImage('http://via.placeholder.com/150x150')
                    ->setCreatedAt(new \DateTime());
            
            $manager->persist($article);
        }

        $manager->flush();
    }
}
