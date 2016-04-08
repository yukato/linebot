<?php

class LineRecv extends LineBot
{

    protected $channelSignature;

    protected $httpRequestBody;


    /**
     * LineRecv constructor.
     *
     * @param array $config
     */
    function __construct(array $config)
    {
        parent::__construct($config);
        $this->channelSignature = $_SERVER['HTTP_X_LINE_CHANNELSIGNATURE'];
        $this->httpRequestBody  = file_get_contents('php://input');
    }


    /**
     * @return bool
     */
    public function isValidSignature()
    {
        $signature = hash_hmac('sha256', $this->httpRequestBody, $this->channelSecret, true);
        $signature = base64_encode($signature);

        if ($signature !== $this->channelSignature) {
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    public function receive()
    {

        if ( ! $this->isValidSignature()) {
            return false;
        }

        return json_decode($this->httpRequestBody);
    }

}

