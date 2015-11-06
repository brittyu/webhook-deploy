<?php

namespace Webhook;

class ResolvePost
{
    public function __construct()
    {
        //
    }

    /**
     * Get the post data
     *
     * @return string
     */
    public function getPost()
    {
        return file_get_contents('php://input');
    }


    /**
     * Resolver the post data from github
     *
     * @return array
     */
    public function resolve($string_data)
    {
        if ($string_data != "") {
            $array_data = json_decode($string_data, true);

            return $array_data;
        }

        return [];
    }

    /**
     * Get the X-Hub-Signature data
     *
     * @return string
     */
    public function getSignature()
    {
        return $_SERVER['X-Hub-Signature'];
    }
}
