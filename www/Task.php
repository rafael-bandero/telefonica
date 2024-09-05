<?php

namespace Telefonica;

/**
 * Class responsible for make the API requests and return their JSON responses
 */
class Task {

    const API_URL = 'https://jsonplaceholder.typicode.com/todos';

    /**
     * Fetch all tasks from the API and return them
     * 
     * @return string in JSON format
     */
    public static function fetchAll(): string
    {
        return self::request(self::API_URL);
    }
            
    /**
     * Delete a task from the API
     *
     * @param  int $id
     * @return string in JSON format
     */
    public static function delete(int $id): string
    {
        return self::request("{self::API_URL}/$id");
    }

    /**
     * Save a task in the API. If ID is given update an existing task,
     * otherwise create a new one.
     *
     * @param ?int $id
     * @return string in JSON format
     */
    public static function save(?int $id): string
    {
        $requestData = file_get_contents('php://input');
        return self::request("{self::API_URL}/$id", $requestData);
    }

    /**
     * Make a cURL request and return its response
     * 
     * @param string $url API URL
     * @param string $data Request data in JSON format
     * @return string in JSON format
     */
    protected static function request(string $url, string $data = null): string
    {
        // Init cURL
        $curl = curl_init($url);

        // Set Return Transfer to true
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Set request method an data
        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }

        // Execute request to the API
        $response = curl_exec($curl);

        // Close connection
        curl_close($curl);

        return $response;
    }
}
