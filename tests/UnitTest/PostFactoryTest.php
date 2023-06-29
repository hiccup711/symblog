<?php

namespace App\Tests\UnitTest;

use App\Entity\Post;
use App\Factory\PostFactory;
use PHPUnit\Framework\TestCase;

class PostFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $factory = $this->createMock(PostFactory::class);

        $postObj = new Post();
        $postObj->setTitle('这是一个标题');
        $postObj->setBody('这是正文');
        $postObj->setSummary('这是摘要');
        $postObj->setStatus('draft');

        $postObj2 = new Post();
        $postObj2->setTitle('这是第二个标题');
        $postObj2->setBody('这是第二正文');
        $postObj2->setSummary('这是第二摘要');
        $postObj2->setStatus('draft');

        // 这里设置 mock 预期，期望 PostFactory 执行一次 create 方法，期望返回一个 Post 对象，与 $postObj 相同
//        $factory->expects($this->once())->method('create')
//            ->with('这是一个标题', '这是正文', '这是摘要')
//            ->willReturn($postObj);
        $factory->expects($this->exactly(2))->method('create')
            ->withConsecutive(['这是一个标题', '这是正文', '这是摘要'], ['这是第二个标题', '这是第二正文', '这是第二摘要'])
            ->willReturn($postObj, $postObj2);

        $post = $factory->create('这是一个标题', '这是正文', '这是摘要');
        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('draft', $post->getStatus());

        $post2 = $factory->create('这是第二个标题', '这是第二正文', '这是第二摘要');
        $this->assertInstanceOf(Post::class, $post2);
        $this->assertSame('这是第二摘要', $post2->getSummary());
    }
}
