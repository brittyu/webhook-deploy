<?php

namespace Webhook;

abstract class Hook
{
    /**
     * validate the config
     *
     * @return Booler
     */
    protected function validate()
    {

    }

    /**
     * log the process
     *
     * @param string msg
     * @param string type
     * @return void
     */
    protected function makeLog($msg = '', $type = 'INFO')
    {
        $base_dir = $this->config['base_dir'];

        $log_name = $base_dir . '/' . $this->config['log_name'];

        if (! file_exists($log_name)) {
            file_put_contents($log_name, '');

            chmod($log_name, 0666);
        }

        $msg_string = date($this->date_format) . ': ' . $type . $msg . PHP_EOL;
        file_put_contents($log_name, $msg_string, FILE_APPEND);

    }

    /**
     * Get data from github
     *
     * @return void
     */
    public function execute()
    {

    }

}

