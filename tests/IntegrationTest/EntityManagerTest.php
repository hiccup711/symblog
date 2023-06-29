<?php

namespace App\Tests\IntegrationTest;

use App\Entity\Post;
use App\Factory\PostFactory;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityManagerTest extends KernelTestCase
{
    public \Doctrine\ORM\EntityManager $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->truncateEntities([
            Post::class
        ]);
    }

    public function testEntityManager(): void
    {
        $this->assertSame('test', self::$kernel->getEnvironment());
        $this->assertInstanceOf(EntityManagerInterface::class, $this->entityManager);

        $factory = static::getContainer()->get(PostFactory::class);
        $this->assertInstanceOf(PostFactory::class, $factory);

        $post1 = $factory->create('Post title 01', 'Post Body 01');
        $post2 = $factory->create('Post title 02', 'Post Body 02', null, 'published');
        $post3 = $factory->create('Post title 03', 'Post Body 03');
        $post4 = $factory->create('Post title 04', 'Post Body 04');

        $this->entityManager->persist($post1);
        $this->entityManager->persist($post2);
        $this->entityManager->persist($post3);
        $this->entityManager->persist($post4);

        $this->entityManager->flush();

        $postRepository = static::getContainer()->get(PostRepository::class);
        $this->assertInstanceOf(PostRepository::class, $postRepository);
        $posts = $postRepository->findAll();
        $this->assertCount(4, $posts);

        $findOneByPost = $postRepository->findOneBy(['title' => 'Post title 01']);
        $this->assertSame($post1, $findOneByPost);

        $findPost = $postRepository->find($findOneByPost->getId());
        $this->assertSame($findOneByPost, $findPost);

        $findByPosts = $postRepository->findBy(['status' => 'draft']);
        $this->assertCount(3, $findByPosts);
    }

    public function testQueryBuilder(): void
    {
        $factory = static::getContainer()->get(PostFactory::class);
        $post1 = $factory->create('Post title 01', 'Post Body 01');
        $post2 = $factory->create('Post title 02', 'Post Body 02', null, 'published');
        $post3 = $factory->create('Post title 03', 'Post Body 03');
        $post4 = $factory->create('Post title 04', 'Post Body 04');

        $this->entityManager->persist($post1);
        $this->entityManager->persist($post2);
        $this->entityManager->persist($post3);
        $this->entityManager->persist($post4);

        $this->entityManager->flush();

        $qb = $this->entityManager->createQueryBuilder();
        $posts = $qb->select('p')->from(Post::class, 'p')
            ->getQuery()
            ->getResult();

        $this->assertCount(4, $posts);

        $postRepository = static::getContainer()->get(PostRepository::class);

        $postsByStatus = $postRepository->findByStatus('published');
        $this->assertCount(1, $postsByStatus);

        $postByTitle = $postRepository->findByTitle('02');
        $this->assertSame('Post title 02', $postByTitle[0]->getTitle());

        $postByDQL = $postRepository->findByTitleDQL('03');
        $this->assertCount('1', $postByDQL);
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->entityManager->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->entityManager->getClassMetadata($entity)->getTableName()
            );
            $connection->executeQuery($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
