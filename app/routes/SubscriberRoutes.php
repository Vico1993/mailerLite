<?php

/**
 * Create a new Subscriber
 */
$app->post( '/subscriber/add', Controller\SubscriberController::class. ':add' );

/**
 * Update a new Subscriber
 */
$app->put( '/subscriber/update/{id}', Controller\SubscriberController::class. ':update' );

/**
 * Routes to get one or all Subscriber.
 */
$app->get( '/subscriber/get[/{id}]', Controller\SubscriberController::class. ':get' );

/**
 * Routes to get all Subscriber with a specific state
 */
$app->get( '/subscriber/filter[/{id_state}]', Controller\SubscriberController::class. ':filters');

/**
 * Routes to delete a Subscriber
 */
$app->delete( '/subscriber/delete/{id}', Controller\SubscriberController::class. ':delete');


?>