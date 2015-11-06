<?php

namespace Webhook;

use webhook;

class Github implements Webhook
{

    private $config = [];

    private $post_data;

    private $name;

    private $date_format = 'Y-m-d H:i:sP';

    private $header = [];

    public function __construct($config, ResolvePost $object)
    {
        $this->config = $config;

        $this->post_data = $object->resolve();

        $this->name = $this->post_data->repository->name;

        $this->header = $object->getHeader();

    }

    public function validate()
    {
        if (! isset($this->config[$this->name])) {
            return false;
        }

        $signature = $this->header['X-Hub-Signature'];

        list($algo, $hash) = explode("=", $signature, 2);

        $post_data_hash = hash_hmac($algo, $this->post_data, $secret);

        if ($this->config[$this->name]['secret'] != $post_data_hash) {
            return false;
        }

        return true;

    }

    public function makeLog($msg = "", $type = 'INFO')
    {
        $base_dir = $this->config->base_dir;

        $log_name = $base . $this->config['log_name'];

        if (! file_exists($log_name)) {
            exec("touch $log_name");

            chmod($log_name, 0666);
        }

        file_put_contents($log_name, date($this->data_format) . $type . $msg. PHP_EOL, FILE_APPEND);

    }

    public function execute()
    {
        $is_legal = $this->validate();

        if (! $is_legal) {
            $msg = "secret isn't currect or name not found";
            $this->log($msg, "ERROR");

            return;
        }

        $code_dir = $this->config[$this->name];

        try {
            chdir($code_dir);

            exec('git reset --hard HEAD', $output);
            exec('git pull '. $this->config[$this->name]['remote'] . ' ' . $this->config[$this->name]['branch'], $output);

            $msg = 'git pull about ' . $this->name . ' success';
            $this->log($msg);

        } catch (Exception $e) {
            $this->log($e->getMessage(), 'ERROR');
        }

    }
}

