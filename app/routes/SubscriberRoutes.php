<?php

/**
 * Routes to get one or all Subscriber.
 */
$app->get( '/subscribers[/{id}]', Controller\SubscriberController::class. ':get' );

?>