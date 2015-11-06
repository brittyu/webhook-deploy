<?php

namespace Webhook;

class ResolverPost
{

    $header = [];

    public function __construct()
    {
        //
    }


    /**
     * resolver the post data from github
     *
     * @return object|null
     */
    public function resolver()
    {
        $post_data = file_get_contents('php://input');
        if ($post_data != "") {
            $object_data = json_decode($post_data);

            return $object_data;
        }

        return null;
    }

    public function getHeader()
    {
        return getAllHeaders();
    }
}
