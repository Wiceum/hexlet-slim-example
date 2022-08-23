<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use App\Validator;

$repo = new App\CourseRepository();

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/courses', function ($request, $response) use ($repo) {
    $params = [
        'courses' => $repo->all()
    ];
    return $this->get('renderer')->render($response, 'courses/index.phtml', $params);
});


$app->get('/courses/new', function ($request, $response) {
    $params = [
        'course' => ['title' => '', 'paid' => ''],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, 'courses/new.phtml', $params);
});


$app->post('/courses', function ($request, $response) use ($repo) {
    $validator = new Validator();
    $course = $request->getParsedBodyParam('course');
    $errors = [];
    $errors = $validator->validate($course);
    if (count($errors) === 0) {
        $repo->save($course);
        return $response->withRedirect('/courses', 302);
    }
    $params = [
        'course' => $course,
        'errors' => $errors
    ];
    return $this->get('renderer')->render($response, 'courses/new.phtml', $params)->withStatus(422);
});

$app->run();
