<?php

class LineBot
{

    protected $channelId;

    protected $channelSecret;

    protected $channelMid;


    /**
     * LineBot constructor.
     *
     * @param array $config
     */
    function __construct(array $config)
    {
        $this->channelId        = $config['channel']['id'];
        $this->channelSecret    = $config['channel']['secret'];
        $this->channelMid       = $config['channel']['mid'];
    }

}

