<?php
/**
 * get days of the week
 */
function getDaysOfTheWeek() {
  return array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
}

/**
 * make json response
 */
 function json_response($message = null, $code = 200) {
     // clear the old headers
     header_remove();
     // set the actual code
     http_response_code($code);
     // set the header to make sure cache is forced
     header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
     // treat this as json
     header('Content-Type: application/json');
     $status = array(
         200 => '200 OK',
         400 => '400 Bad Request',
         422 => 'Unprocessable Entity',
         500 => '500 Internal Server Error'
         );
     // ok, validation error, or failure
     header('Status: '.$status[$code]);
     // return the encoded json
     return json_encode(array(
         'status' => $code < 300, // success or not?
         'data' => $message
         ));
}
/**
 * get header authorization
 */
function getAuthorizationHeader(){
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  }
  else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['Authorization'])) {
          $headers = trim($requestHeaders['Authorization']);
      }
  }
  return $headers;
  }
/**
 * get access token from header
 */
function getBearerToken() {
  $headers = getAuthorizationHeader();
  // HEADER: Get the access token from the header
  if (!empty($headers)) {
      if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
          return $matches[1];
      }
  }
  return null;
}
?>
