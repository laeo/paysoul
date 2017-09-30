<?php

namespace Doubear\Paysoul\Utils;

class HttpClient
{
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function request(string $method, string $url, array $options = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 6);
        curl_setopt_array($ch, array_merge($this->options, $options));
        $return = curl_exec($ch);
        curl_close($ch);

        return $return;
    }

    public function set($opt, $value)
    {
        $this->options[$opt] = $value;
    }

    public function __call($method, $args)
    {
        array_unshift($args, $method);
        return call_user_func_array([$this, 'request'], $args);
    }
}
