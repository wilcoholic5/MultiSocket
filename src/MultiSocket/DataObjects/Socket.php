<?php
require 'vendor/autoload.php';
require 'SomeObject.php';

$someobj = new \SomeObject();
$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$socket1 = new React\Socket\Server($loop);
$socket2 = new React\Socket\Server($loop);
$app = function ($request, $response) use($someobj) {
    $response->writeHead(200, array('Content-Type' => 'text/plain'));
    $response->end('The name is currently'.$someobj->getName());
};

$http = new React\Http\Server($socket, $loop);

$http->on('request', $app);

echo "Server running locally on multiple sockets!\n";
// Add connections to SPLObjectStorage
$conns = new SplObjectStorage();
$socket1->on('connection', function ($conn) use ($conns, $someobj, $socket1, $loop) {
    $conns->attach($conn);
    echo 'Some info about conns: '. $conns->count().PHP_EOL;
    $conn->on('end', function () use ($conns, $conn){
        $conns->detach($conn);
        echo 'Some info about conns: '. $conns->count().PHP_EOL;
    });

    $someobj->setName(array("fname"=>"1338"));
    $conn->on('data', function($data) use($conn, $someobj, $socket1){
        $conn->write('The port is : '.$socket1->getPort().PHP_EOL);
        $conn->write('Our data is:'.$data.' '.$someobj->getName());
    });
});
$socket2->on('connection', function ($conn) use ($conns, $someobj, $socket2, $loop) {
    $conns->attach($conn);
    echo 'Some info about conns: '. $conns->count().PHP_EOL;
    $conn->on('end', function () use ($conns, $conn){
        $conns->detach($conn);
        echo 'Some info about conns: '. $conns->count().PHP_EOL;
    });

    $someobj->setName(array("fname"=>"1339"));
    $conn->on('data', function($data) use($conn, $someobj, $socket2){
        $conn->write('The port is : '.$socket2->getPort().PHP_EOL);
        $conn->write('Our data is:'.$data.' '.$someobj->getName());
    });
});

$socket->listen(1337);
$socket1->listen(1338);
$socket2->listen(1339);
$loop->run();
