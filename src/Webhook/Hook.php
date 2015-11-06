<?php

namespace Webhook;

interface Hook
{
    /**
     * validate the config
     *
     * @return Booler
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

