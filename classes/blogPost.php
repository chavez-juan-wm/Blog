<?php
    class blogPost
    {
        public $id;
        public $title;
        public $body;
        public $authorId;
        public $tags;
        public $datePosted;

        public function __construct($id, $title, $body, $authorId, $datePosted)
        {

        }
    }