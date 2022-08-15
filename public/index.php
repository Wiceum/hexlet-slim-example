<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$faker = \Faker\Factory::create();
$faker->seed(1234);
$companies = App\Generator::generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->write('go to the /companies');
});

$app->get('/companies', function ($request, $response) use ($companies) {
    $params = $request->getQueryParams();
    $page = $params['page'] ?? 1;
    $per = $params['per'] ?? 5;
    $sliced = array_slice($companies, ($page - 1) * $per, $per);
    return $response->write(json_encode($sliced));
});

$app->run();


/*$domains = [];
for ($i = 0; $i < 10; $i++) {
    $domains[] = $faker->domainName;
}

$phones = [];
for ($i = 0; $i < 10; $i++) {
    $phones[] = $faker->phoneNumber;
}*/

/*$app->get('/phones', function ($request, $response) use ($phones) {
    return $response->write(json_encode($phones));
});

$app->get('/domains', function ($request, $response) use ($domains) {
    return $response->write(json_encode($domains));
});

$app->post('/users', function ($request, $response) {
    return $response->withStatus(302);
});*/
