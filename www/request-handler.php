<?php
header('Content-Type: application/json; charset=utf-8');

require_once('Task.php');

use Telefonica\Task;

// Get URL string
$queryStr = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

// Create params array from query string
parse_str($queryStr, $params);

// Execute apropriate request
$response = match ($params['action']) {
    'fetchAll' => Task::fetchAll(),
    'delete' => Task::delete($params['id']),
    'save' => Task::save(isset($params['id']) ? $params['id'] : null),
};

echo $response;
