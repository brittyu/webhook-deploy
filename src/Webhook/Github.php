<?php

namespace Webhook;

class Github implements Hook
{

    private $config = [];

    private $post_data;

    private $name;

    private $date_format = 'Y-m-d H:i:sP';

    private $header = [];

    public function __construct($config, ResolvePost $object)
    {
        date_default_timezone_set('Asia/Chongqing');

        $this->config = $config;

        $this->post_data = $object->resolve();

        $this->name = $this->post_data->repository->name;

        $this->signature = $object->getSignature();

    }

    public function validate()
    {
        if (! isset($this->config[$this->name])) {
            return false;
        }

        list($algo, $hash) = explode("=", $this->signature, 2);

        $post_data_hash = hash_hmac($algo, $this->post_data, $secret);

        if ($this->config[$this->name]['secret'] != $post_data_hash) {
            return false;
        }

        return true;

    }

    public function makeLog($msg = "", $type = 'INFO')
    {
        $base_dir = $this->config->base_dir;

        $log_name = $base_dir . $this->config['log_name'];

        if (! file_exists($log_name)) {
            file_put_contents($log_name, '');

            chmod($log_name, 0666);
        }

        $msg_string = date($this->date_format) . ': ' . $type . $msg . PHP_EOL;
        file_put_contents($log_name, $msg_string, FILE_APPEND);

    }

    public function execute()
    {
        $is_legal = $this->validate();

        if (! $is_legal) {
            $msg = " secret isn't currect or name not found";
            $this->makeLog($msg, "ERROR");

            return;
        }

        $code_dir = $this->config[$this->name];

        try {
            chdir($code_dir);

            exec('git reset --hard HEAD', $output);
            exec('git pull '. $this->config[$this->name]['remote'] . ' ' . $this->config[$this->name]['branch'], $output);

            $msg = 'git pull about ' . $this->name . ' success';
            $this->makeLog($msg);

        } catch (Exception $e) {
            $this->makeLog($e->getMessage(), 'ERROR');
        }

    }
}

