<?php

/**
 * Create a new Field
 */
$app->post( '/field/add', Controller\FieldController::class. ':add' );

/**
 * Update a field
 */
$app->put( '/field/update/{id}', Controller\FieldController::class. ':update' );

/**
 * Routes to get one or all field.
 */
$app->get( '/field/get[/{id}]', Controller\FieldController::class. ':get' );

/**
 * Routes to get all field with a specific state and specif subscriber
 */
$app->get( '/field/filter[/{id_type}/subscriber/{id_subscriber}]', Controller\FieldController::class. ':filters');

/**
 * Routes to get all field from a subscriber
 */
$app->get( '/field/subscriber/{id_subscriber}', Controller\FieldController::class. ':subscriber');

/**
 * Routes to delete a Field
 */
$app->delete( '/field/delete/{id}', Controller\FieldController::class. ':delete');


?>