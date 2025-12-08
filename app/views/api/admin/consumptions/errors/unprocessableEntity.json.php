<?php

$status = $status ?? 422;
http_response_code($status);
$json = [
  'message' => $message ?? 'Bathroom not found!',
  'code' => $status
];
