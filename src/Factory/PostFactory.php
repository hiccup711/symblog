<?php

namespace App\Factory;

use App\Entity\Post;

class PostFactory
{
    public function create(string $title, string $body, string $summary = null, string $status = 'draft'): Post
    {
        if (is_null($summary)) {
            $summary = $this->generateSummary($body);
        }
        $post = new Post();
        $post->setTitle($title);
        $post->setBody($body);
        $post->setSummary($summary);
        $post->setStatus($status);
        return $post;
    }

    private function generateSummary(string $body, int $length = 140): string
    {
        return mb_substr($body, 0, $length);
    }
}