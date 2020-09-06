<?php


namespace App\Model;


class WebService
{

    private $request_headers = [];
    private $endpoint = "";
    private $isArray = true;
    private $timeOut = 30;

    /**
     * @param $header
     * @return $this
     */
    public function addHeaders($header)
    {
        $this->request_headers[] = $header;
        return $this;
    }

    /**
     * @param $header
     * @return $this
     */
    public function setHeader($header)
    {
        if (!is_array($header)) {
            $this->request_headers = [$header];
            return $this;
        }

        $this->request_headers = $header;
        return $this;
    }

    /**
     * @param null $timeOut
     */
    public function setTimeOut($timeOut)
    {
        $this->timeOut = $timeOut;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return bool|mixed|string
     */
    public function get()
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->endpoint,
            CURLOPT_TIMEOUT => $this->timeOut,
            CURLOPT_HTTPHEADER => $this->request_headers
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($this->isArray()) {
            return json_decode($response, false);
        }

        return $response;
    }

    /**
     * @param $postFiels
     * @return bool|mixed|string
     */
    public function post($postFiels)
    {

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->endpoint,
            CURLOPT_HTTPHEADER => $this->request_headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postFiels
        ]);

        $response = curl_exec($curl);

        if ($this->isArray()) {
            return json_decode($response, false);
        }

        return $response;
    }

    /**
     * @param bool $data
     * @return $this
     */
    public function isArray($data = true)
    {
        $this->isArray = $data;
        return $this;
    }

    /**
     * @param null $postFiels
     * @return bool|mixed|string
     */
    public function delete($postFiels = null)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->endpoint,
            CURLOPT_HTTPHEADER => $this->request_headers,
            CURLOPT_POSTFIELDS => $postFiels
        ]);

        $response = curl_exec($curl);

        if ($this->isArray()) {
            return json_decode($response, false);
        }

        return $response;
    }

    /**
     * @param null $postFiels
     * @return bool|mixed|string
     */
    public function put($postFiels = null)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->endpoint,
            CURLOPT_HTTPHEADER => $this->request_headers,
            CURLOPT_POSTFIELDS => $postFiels
        ]);

        $response = curl_exec($curl);

        if ($this->isArray()) {
            return json_decode($response, false);
        }

        return $response;
    }

}
