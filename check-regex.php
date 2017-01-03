<?php


$pattern = '/^[a-z]*,\\ *[a-z]*$/';
$subject = 'php, html';
$result = preg_match( $pattern, $subject , $matches );

if ($result) {
    echo "Matches";
} else {
    echo "Does not match";
}
