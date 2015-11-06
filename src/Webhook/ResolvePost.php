<?php

namespace Webhook;

class ResolvePost
{
    public function __construct()
    {
        //
    }


    /**
     * resolver the post data from github
     *
     * @return object|null
     */
    public function resolve()
    {
        $post_data = file_get_contents('php://input');
        if ($post_data != "") {
            $object_data = json_decode($post_data);

            return $object_data;
        }

        return null;
    }

    public function getSignature()
    {
        return $_SERVER['X-Hub-Signature'];
    }
}