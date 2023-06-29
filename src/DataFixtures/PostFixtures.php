<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    private PostFactory $postFactory;

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 5; $i++) {
            $post = $this->postFactory->create('Post title 0' . $i, 'Post body 0' . $i);
            if ($i == 2) {
                $post->setStatus('published');
            }
            $manager->persist($post);
        }
        $manager->flush();
    }
}
