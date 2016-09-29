<?php
    class blogPost
    {
        public $postId;
        public $title;
        public $body;
        public $authorId;
        public $tags;
        public $datePosted;

        public function __construct($inPostId = null, $inTitle = null, $inBody = null, $inAuthorId = null, $inDatePosted = null)
        {
            $this->postId = $inPostId;
            $this->title = $inTitle;
            $this->body = $inBody;
            $this->authorId = $inAuthorId;
            $this->datePosted = $inDatePosted;
        }
    }