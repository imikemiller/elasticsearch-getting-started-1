<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 09/09/18
 * Time: 21:44
 */

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create();
$client = Elasticsearch\ClientBuilder::create()->build();
$posts = [
    'index' => 'posts_1',
    'body' => [
        'settings' => [
            'number_of_shards' => 1,
            'number_of_replicas' => 1
        ],
        'mappings' => [
            'post' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'date' => [
                        'type' => 'object',
                        'properties'=>[
                            'date'=>[
                                'type'=>'keyword'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$client->indices()->create($posts);

$params = [];

/*
 * Fake some users
 */
for($i=0;$i<1000;$i++){
    $params['body'][] = [
        'index' => [
            '_index' => 'users_1',
            '_type' => 'user'
        ]
    ];

    $params['body'][] = [
        'name' => $faker->userName,
        'level' => collect(range(1,20))->random(1)->first(),
        'interests'=>[
            'video'=>collect([
                'arts','crime','action'
            ])->random(rand(0,3))->toArray(),
            'audio'=>collect([
                'classical','rock','country'
            ])->random(rand(0,3))->toArray(),
        ]
    ];
}

/*
 * Fake some posts
 */
for($u=0;$u<10000;$u++){

    $params['body'][] = [
        'index' => [
            '_index' => 'posts_1',
            '_type' => 'post',
        ]
    ];

    $params['body'][] = [
        'text' => $faker->sentence(15),
        'date' => $faker->dateTime(),
        'access'=>collect(range(1,20))->random(1)->first(),
        'categories'=>[
            'video'=>collect([
                'arts','crime','action'
            ])->random(rand(0,3))->toArray(),
            'audio'=>collect([
                'classical','rock','country'
            ])->random(rand(0,3))->toArray(),
        ]
    ];
}

$responses = $client->bulk($params);