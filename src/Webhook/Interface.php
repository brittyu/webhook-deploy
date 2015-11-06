<?php

namespace Webhook;

interface webhook
{
    /**
     * validate the config
     *
     * @return void
     */
    public function validate();

    /**
     * log the process
     *
     * @return void
     */
    public function makeLog();

    /**
     * git pull 
     *
     * @return void
     */
    public function execute();

}

