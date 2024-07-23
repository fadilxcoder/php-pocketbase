<?php

namespace App;

class Collection
{
    private string $collection;

    private string $url;

    private static string $token = '';

    public function __construct(string $url, string $collection)
    {
        $this->url = $url;
        $this->collection = $collection;
    }

    public function getList(int $start = 1, int $end = 50, array $queryParams = []): array
    {
        $queryParams['perPage'] = $end;
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');

        return json_decode($response, JSON_FORCE_OBJECT);
    }

    // TODO ¯\_(ツ)_/¯
    public function upload(string $recordId, string $field, string $filepath): void
    {
        $ch = curl_init($this->url . "/api/collections/".$this->collection."/records/" . $recordId);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => array(
                $field => new \CURLFile($filepath)
            )
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array('Content-Type: multipart/form-data');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        // var_dump($response);
    }

    public function authAsUser(string $email, string $password)
    {
        $result = $this->doRequest($this->url . "/api/collections/users/auth-with-password", 'POST', ['identity' => $email, 'password' => $password]);
        if (!empty($result['token'])) {
            self::$token = $result['token'];
        }
    }

    public function getFullList(int $batch = 200, array $queryParams = []): array
    {
        $queryParams = [... $queryParams, ['perPage' => $batch]];
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');

        return json_decode($response, JSON_FORCE_OBJECT);
    }

    public function getFirstListItem(string $filter, array $queryParams = []): array
    {
        $queryParams['perPage'] = 1;
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');
        return json_decode($response, JSON_FORCE_OBJECT)['items'][0];
    }

    public function create(array $bodyParams = [], array $queryParams = []): string
    {
        return $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records", 'POST', $bodyParams);
    }

    public function update(string $recordId, array $bodyParams = [], array $queryParams = []): string
    {
        return $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'PATCH', $bodyParams);
    }

    public function delete(string $recordId, array $queryParams = []): string
    {
        return $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'DELETE');
    }

    public function getOne(string $recordId, array $queryParams = []): array
    {
        $output = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'GET');
        return json_decode($output, JSON_FORCE_OBJECT);
    }

    public function authAsAdmin(string $email, string $password): void
    {
        $bodyParams['identity'] = $email;
        $bodyParams['password'] = $password;
        $output = $this->doRequest($this->url . "/api/admins/auth-with-password", 'POST', $bodyParams);
        self::$token = json_decode($output, true)['token'];
    }

    private function doRequest(string $url, string $method, $bodyParams = []): string
    {
        $ch = curl_init();

        if (self::$token != '') {
            $headers = array(
                'Content-Type: application/json',
                'Authorization: ' . self::$token
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($bodyParams) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyParams);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}