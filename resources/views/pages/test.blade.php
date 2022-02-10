@extends('layouts.app')

@section('title', 'Test')
@section('slug', 'test')

@section('content')
<?php

//use App\Models\TCG\Pagination;
//use App\Models\TCG\Pokemon;
use App\Services\API;

/**
 * Change 'verify' option to true to fix the following error:
 *
 * Fatal error: Uncaught exception 'GuzzleHttp\Exception\ConnectException' with message
 * 'cURL error 35: Unknown SSL protocol error in connection to api.pokemontcg.io:-9838
 * (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)'
 */
//Pokemon::Options( ['verify' => false] );
//Pokemon::ApiKey( 'ea92d5ff-9928-42ad-bd43-1dc3fc2c339b' );

        //TEST/\Debugbar::startMeasure('API');
        $api   = new API();
        $query = '';
        $api->tcg_get_cards( 1, $query );
        \Debugbar::info($api);
          //TEST/\Debugbar::stopMeasure('API');

/**
 * Get a single card
 */
//$response = Pokemon::Card()->find('xy7-54');
//$response = Pokemon::Card()->find('xyp-XY93');
//Pokemon::Card()->all();
//print_r($response->toArray());
//print_r($response->toJson());

/**
 * Get all cards
 */
//$response = Pokemon::Card()->all();
//foreach ($response as $model) {
//    print_r($model->toArray());
//    print_r($model->toJson());
//}

/**
 * Get Shaymin-EX cards
 */
//$response = Pokemon::Card()->where(['name' => 'shaymin'])->where(['subtype' => 'EX'])->all();
//$response = Pokemon::Card()->where(['name' => 'shaymin', 'subtypes' => 'EX'])->orderBy('id', Pokemon::DESCENDING_ORDER)->all();
//foreach ($response as $model) {
//    print_r($model->toArray());
//    print_r($model->toJson());
//}

/**
 * Get Vs Seeker cards
 */
//$response = Pokemon::Card()->where(['name' => 'vs seeker'])->all();
//foreach ($response as $model) {
//    print_r($model->toArray());
//    print_r($model->toJson());
//}

/**
 * Get Pagination
 */
/** @var Pagination $response */
//$response = Pokemon::Card()->pagination();
//print_r($response->toArray());
//print_r($response->getCount());

/**
 * Get a single set
 */
//$response = Pokemon::Set()->find('xy11');
//print_r($response->toArray());
//print_r($response->toJson());

/**
 * Get all sets
 */
//$response = Pokemon::Set()->all();
//foreach ($response as $model) {
//    print_r($model->toArray());
//    print_r($model->toJson());
//}

/**
 * Get all types
 */
//$response = Pokemon::Type()->all();
//print_r($response);

/**
 * Get all subtypes
 */
//$response = Pokemon::Subtype()->all();
//print_r($response);

/**
 * Get all supertypes
 */
//$response = Pokemon::Supertype()->all();
//print_r($response);

/**
 * Get all rarities
 */
//$response = Pokemon::Rarity()->all();
//print_r($response);
?>
@stop
