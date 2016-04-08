<?php

class LineSend extends LineBot
{

    protected $endpointHost;

    protected $sendToChannel;

    protected $sendEventType;


    /**
     * LineSend constructor.
     *
     * @param array $config
     */
    function __construct(array $config)
    {
        parent::__construct($config);
        $this->endpointHost  = $config['endpointHost'];
        $this->sendToChannel = '1383378250';
        $this->sendEventType = '138311608800106203';
    }


    /**
     * @param string[] $userIds
     * @param string   $text
     *
     * @return bool|mixed
     */
    public function sendText(array $userIds, $text)
    {
        $data = [
            'contentType' => 1,
            'toType'      => 1,
            'text'        => $text,
        ];

        return $this->send($userIds, $data);
    }


    /**
     * @param string[] $userIds
     * @param string   $originalContentUrl
     * @param string   $previewImageUrl
     *
     * @return bool|mixed
     */
    public function sendImage(array $userIds, $originalContentUrl, $previewImageUrl)
    {
        $data = [
            'contentType'        => 2,
            'toType'             => 1,
            'originalContentUrl' => $originalContentUrl,
            'previewImageUrl'    => $previewImageUrl,
        ];

        return $this->send($userIds, $data);
    }


    /**
     * @param string[] $userIds
     * @param string   $originalContentUrl
     * @param string   $previewImageUrl
     *
     * @return bool|mixed
     */
    public function sendVideo(array $userIds, $originalContentUrl, $previewImageUrl)
    {
        $data = [
            'contentType'        => 3,
            'toType'             => 1,
            'originalContentUrl' => $originalContentUrl,
            'previewImageUrl'    => $previewImageUrl,
        ];

        return $this->send($userIds, $data);
    }


    /**
     * @param string[] $userIds
     * @param string   $originalContentUrl
     * @param string   $contentMetadata
     *
     * @return bool|mixed
     */
    public function sendAudio(array $userIds, $originalContentUrl, $contentMetadata)
    {
        if ( ! isset( $contentMetadata['AUDLEN'] ) || empty( $contentMetadata['AUDLEN'] )) {
            return false;
        }

        $data = [
            'contentType'        => 4,
            'toType'             => 1,
            'originalContentUrl' => $originalContentUrl,
            'contentMetada'      => [
                'AUDLEN' => $contentMetadata['AUDLEN']
            ],
        ];

        return $this->send($userIds, $data);
    }


    /**
     * @param string[] $userIds
     * @param string   $text
     * @param array    $location
     *
     * @return bool|mixed
     */
    public function sendLocation(array $userIds, $text, array $location)
    {
        if ( ! isset( $location['title'] ) || empty( $location['title'] )) {
            return false;
        }

        if ( ! isset( $location['latitude'] ) || empty( $location['latitude'] )) {
            return false;
        }

        if ( ! isset( $location['longitude'] ) || empty( $location['longitude'] )) {
            return false;
        }

        $data = [
            'contentType' => 7,
            'toType'      => 1,
            'text'        => $text,
            'location'    => [
                'title'     => $location['title'],
                'latitude'  => $location['latitude'],
                'longitude' => $location['longitude'],
            ],
        ];

        return $this->send($userIds, $data);
    }


    /**
     * @param string[] $userIds
     * @param array    $contentMetadata
     *
     * @return bool|mixed
     */
    public function sendSticker(array $userIds, array $contentMetadata)
    {
        if ( ! isset( $contentMetadata['STKID'] ) || empty( $contentMetadata['STKID'] )) {
            return false;
        }

        if ( ! isset( $contentMetadata['STKPKGID'] ) || empty( $contentMetadata['STKPKGID'] )) {
            return false;
        }

        if ( ! isset( $contentMetadata['STKVER'] ) || empty( $contentMetadata['STKVER'] )) {
            return false;
        }

        $data = [
            'contentType'     => 8,
            'toType'          => 1,
            'contentMetadata' => [
                'STKID'    => $contentMetadata['STKID'],
                'STKPKGID' => $contentMetadata['STKPKGID'],
                'STKVER'   => $contentMetadata['STKVER'],
            ],
        ];

        return $this->send($userIds, $data);
    }


    /**
     * @param string[] $userIds
     * @param array    $data
     *
     * @return bool|mixed
     */
    private function send(array $userIds, array $data)
    {
        if (empty( $data )) {
            return false;
        }

        $endpointUrl = '/v1/events';
        $url         = $this->endpointHost . $endpointUrl;

        $headers = [
            'Content-type: application/json; charset=utf-8',
            'X-Line-ChannelID: ' . $this->channelId,
            'X-Line-ChannelSecret: ' . $this->channelSecret,
            'X-Line-Trusted-User-With-ACL: ' . $this->channelMid,
        ];

        $sendingMessages = [
            'to'        => $userIds,
            'toChannel' => $this->sendToChannel,
            'eventType' => $this->sendEventType,
            'content'   => $data
        ];

        return $this->post($url, $sendingMessages, $headers);

    }


    /**
     * @param string $url
     * @param array  $postData
     * @param array  $headers
     *
     * @return mixed
     */
    private function post($url, $postData, array $headers = [ ])
    {
        $options = [
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,    // Disabled SSL Cert checks
            CURLOPT_HTTPHEADER     => $headers, // set header
            CURLOPT_POST           => true,     // POST REQUEST
            CURLOPT_CUSTOMREQUEST  => 'POST',   // POST REQUEST
            CURLOPT_POSTFIELDS     => json_encode($postData),   // post data
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errMsg  = curl_error($ch);
        $result  = curl_getinfo($ch);
        curl_close($ch);

        $result['errno']   = $err;
        $result['errmsg']  = $errMsg;
        $result['content'] = $content;

        return $result;
    }

}

