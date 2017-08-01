<?php


    class BlogPost extends ObjectModel
    {

        public $id_blog;
        public $blog_name;
        public $blog_description;
        public $date_blog;
        public static $definition = array(
            'table' => 'blog',
            'primary' => 'id_blog',
            'fields' => array(
                'blog_name' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'blog_description' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
                'date_blog' => array('type' => self::TYPE_DATE, 'validate' => 'isString'),
            ),
        );
    }
