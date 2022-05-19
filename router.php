<?php
require_once('includes/db.php');

function match_routes($uri, $routes)
{
  if (is_array($routes)) {
    foreach ($routes as $route) {
      if (($uri == $route) || ($uri == $route . '/')) {
        return True;
      }
    }
    return False;
  } else {
    return ($uri == $routes) || ($uri == $routes . '/');
  }
}

// Grabs the URI and separates it from query string parameters
error_log('');
error_log('HTTP Request: ' . $_SERVER['REQUEST_URI']);
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

if (preg_match('/^\/public\//', $request_uri)) {
  // serve the requested resource as-is.
  return False;
} else if (match_routes($request_uri, ['/', '/home'])) {
  require 'pages/index.php';
} else if (match_routes($request_uri, '/directions')) {
  require 'pages/directions.php';
} else if (match_routes($request_uri, '/covid')) {
  require 'pages/covid.php';
} else if (match_routes($request_uri, '/events')) {
  require 'pages/events.php';
} else if (match_routes($request_uri, '/faq')) {
  require 'pages/faq.php';
} else if (match_routes($request_uri, '/store')) {
  require 'pages/store.php';
} else if (match_routes($request_uri, '/vendors')) {
  require 'pages/vendors.php';
} else if (match_routes($request_uri, '/store/item')) {
  require 'pages/item.php';
} else {
  error_log("404 Not Found: " . $request_uri);
  http_response_code(404);
  require 'pages/404.php';
}
