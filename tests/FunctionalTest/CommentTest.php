<?php

namespace App\Tests\FunctionalTest;

use Symfony\Component\Panther\PantherTestCase;

class CommentTest extends PantherTestCase
{
    public function testReplyComment(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Read More →')->link();
        $pageDetailCrawler = $client->click($link);
        $form = $pageDetailCrawler->selectButton('Submit')->form();
        $form['comment[author]'] = 'John Doe';
        $form['comment[email]'] = 'jd@my.com';
        $form['comment[message]'] = 'Hello World!';

        $client->submit($form);
//        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('.media-body', 'John Doe');
        $this->assertSelectorTextContains('.media-body', 'Hello World!');

        $client->executeScript('document.querySelector(".js-replay-comment-btn").click()');
        $newPageDetailCrawler = $client->waitFor('div.reply-comment-card');

        $this->assertSelectorTextContains('div.reply-comment-card', '回复评论：');
        $replayCommentDivCrawler = $newPageDetailCrawler->filter('div.reply-comment-card');
        $replyForm = $replayCommentDivCrawler->selectButton('Submit')->form();
        $replyForm['comment[author]'] = 'John Doe2';
        $replyForm['comment[email]'] = 'jd2@my.com';
        $replyForm['comment[message]'] = 'Hello World!2';

        $client->submit($replyForm);
//        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('.media-body', 'John Doe2');
        $this->assertSelectorTextContains('.media-body', 'Hello World!2');

        $client->takeScreenshot('screen.png');
    }
}
