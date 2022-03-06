<?php
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;
class AppFixtures extends Fixture 
{
    
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();
        $users =[];
        for ($i=0; $i < 10 ; $i++) { 
            $user = new User();
            $user->setUsername($faker->name);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastname);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password());
            $user->setCreatedAt(new \DateTime());
            $manager->persist($user);
            $users[] = $user;

        }

        $categories =[];
        for ($i=0; $i < 10; $i++) { 
            $randome =$faker->numberBetween(1,100);
            $category = new Category();
            $category->setTitle($faker->text(50));
            $category->setDescription($faker->text(250));
            $category->setImage("https://picsum.photos/id/$randome/200/300");
            $category->setCreatedAt(new \DateTime());
            $manager->persist($category);
            $categories[] = $category;
        }


        
        for ($i=0; $i < 100 ; $i++) { 
            $random =$faker->numberBetween(1,100);
            $article = new Article();
            $article->setTitle($faker->text(50));
            $article->setContent($faker->text(6000));
            $article->setImage("https://picsum.photos/id/$random/200/300");
            $article->addCategory($categories[$faker->numberBetween(0,9)]);
            $article->setAuthor($users[$faker->numberBetween(0,9)]);
            $article->setCreatedAt(new \DateTime());
            $manager->persist($article);
            

        }
        $manager->flush();
    }
}
?>