<?php

namespace Webhook;

class Github extends Hook
{
    /*
     * Store your config
     *
     * @var array
     */
    private $config = [];

    /**
     * The data from github with post method
     *
     * @var object
     */
    private $post_data;

    /**
     * All post data 
     *
     * @var string
     */
    private $json_data;

    /**
     * The repository name
     *
     * @var string
     */
    private $name;

    /**
     * Define the log's date format
     *
     * @var string
     */
    private $date_format = 'Y-m-d H:i:sP';

    /**
     * Initialize some variables
     *
     * @param array $config
     * @param ResolvePost $object
     * @return void
     */
    public function __construct($config, ResolvePost $object)
    {
        date_default_timezone_set('Asia/Chongqing');

        $this->config = $config;

        $this->json_data = $object->getPost();

        $this->post_data = $object->resolve($json_data);

        $this->name = $this->post_data->repository->name;

        $this->signature = $object->getSignature();

    }

    /**
     * Validate the repo name and check the secret
     *
     * @return bool
     */
    protected function validate()
    {
        if (! isset($this->config[$this->name])) {
            return false;
        }

        list($algo, $hash) = explode("=", $this->signature, 2);

        $secret = $this->config[$this->name]['secret'];
        $post_data_hash = hash_hmac($algo, $this->json_data, $secret);

        if ($hash != $post_data_hash) {
            return false;
        }

        return true;

    }

    /**
     * Get data from github
     *
     * @return void
     */
    protected function execute()
    {
        $is_legal = $this->validate();

        if ($is_legal == false) {
            $msg = " secret isn't currect or name not found";
            $this->makeLog($msg, "ERROR");

            return;
        }

        $path = $this->config[$this->name]['path'];
        $remote = $this->config[$this->name]['remote'];
        $branch = $this->config[$this->name]['branch'];

        // begin get the data from github
        // use shell_exec to execute command
        try {

            // modify the script execution paths
            chdir($path);

            echo shell_exec('sudo git reset --hard HEAD');
            echo shell_exec('sudo git pull ' . $remote . ' ' . $branch . ' 2>&1');

            $msg = 'git pull about ' . $this->name . ' success';
            $this->makeLog($msg);

        } catch (Exception $e) {
            $this->makeLog($e->getMessage(), 'ERROR');
        }

    }
}

