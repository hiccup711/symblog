<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    public const LAST_POST = 'last_post';
    private PostFactory $postFactory;
    private $faker;

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $last_post = null;
        for ($i = 1; $i < 20; $i++) {
            $post = $this->postFactory->create(
                $this->faker->title(),
                $this->faker->paragraph()
            );
            if ($this->faker->boolean) {
                $post->setStatus('published');
            }
            $post->setPostImage('00' . $this->faker->randomDigit() . '.jpg');
            if ($i == 19) {
                $post->setStatus('published');
                $last_post = $post;
                $this->addReference(self::LAST_POST, $last_post);
            }
            $manager->persist($post);
        }
        $manager->flush();
    }
}
