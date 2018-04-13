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
$app->get( '/subscriber/get[/{id}]', Controller\FieldController::class. ':get' );

/**
 * Routes to get all field with a specific state and specif user
 */
$app->get( '/subscriber/filter[/{id_state}/subscriber/{id_subscriber}]', Controller\FieldController::class. ':filters');

/**
 * Routes to delete a Field
 */
$app->delete( '/subscriber/delete/{id}', Controller\FieldController::class. ':delete');


?>