<?php

namespace App\Tests\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostDetailTest extends WebTestCase
{
    public function testCommentSubmit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $link = $crawler->selectLink('Read More →')->link();
        $pageDetailCrawler = $client->click($link);

        $this->assertResponseIsSuccessful();

        $form = $pageDetailCrawler->selectButton('Submit')->form();
        $form['comment[author]'] = 'John Doe';
        $form['comment[email]'] = 'jd@my.com';
        $form['comment[message]'] = 'Hello World!';

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('John Doe', $client->getResponse()->getContent());
        $this->assertStringContainsString('Hello World!', $client->getResponse()->getContent());
    }
}
