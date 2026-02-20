<?php 
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO("mysql:host=localhost;
                dbname=rafaelivansantosdb;charset=utf8",
                "root",
                "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath('/Santos_syllabus/day4/api');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    $response->getBody()->write("LOGIN GET WORKS");
    return $response;
});

$app->post('/login', function (Request $request, Response $response) use ($pdo) {

    $data = $request->getParsedBody();

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $stmt->execute([$username,$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password,$user['password'])){
        $_SESSION['user_id'] = $user['user_id'];
        $response->getBody()->write("ok");
    }else{
        $response->getBody()->write("fail");
    }

    return $response;
});

$app->post('/signup', function(Request $request, Response $response) use ($pdo){

    $d = $request->getParsedBody();

    $fullname = $d['fullname'] ?? '';
    $nickname = $d['nickname'] ?? '';          
    $address  = $d['address'] ?? '';
    $bdate    = $d['bdate'] ?? '';
    $tel      = $d['tel'] ?? '';
    $username = $d['username'] ?? '';          
    $email    = $d['email'] ?? '';
    $password = $d['psw'] ?? '';
    $confirm  = $d['confirm_password'] ?? '';

    if($password !== $confirm){
        $response->getBody()->write("Passwords mismatch");
        return $response;
    }

    $check = $pdo->prepare("SELECT user_id FROM users WHERE username=? OR email=?");
    $check->execute([$username,$email]);

    if($check->fetch()){
        $response->getBody()->write("exists");
        return $response;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users(fullname,nickname,address,birthday,phone,username,email,password)
        VALUES(?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $fullname,
        $nickname,
        $address,
        $bdate,
        $tel,
        $username,
        $email,
        $hash
    ]);

    $response->getBody()->write("ok");
    return $response;
});

$app->get('/session', function($req,$res){

    $res->getBody()->write(isset($_SESSION['user_id']) ? "ok":"no");
    return $res;
});

$app->get('/user', function($req,$res) use ($pdo){

    if(!isset($_SESSION['user_id'])){
        $res->getBody()->write(json_encode(["error"=>"not logged in"]));
        return $res->withHeader('Content-Type','application/json');
    }

    $stmt=$pdo->prepare("SELECT fullname,nickname,address,birthday,phone,username,email FROM users WHERE user_id=?");
    $stmt->execute([$_SESSION['user_id']]);

    $res->getBody()->write(json_encode($stmt->fetch(PDO::FETCH_ASSOC)));
    return $res->withHeader('Content-Type','application/json');
});

$app->post('/update-user', function($req,$res) use ($pdo){

    if(!isset($_SESSION['user_id'])){
        $res->getBody()->write("not logged in");
        return $res;
    }

    $d=$req->getParsedBody();

    // WITH PASSWORD CHANGE
    if(!empty($d['password'])){

        if($d['password']!==$d['confirm_password']){
            $res->getBody()->write("Passwords mismatch");
            return $res;
        }

        $hash=password_hash($d['password'],PASSWORD_DEFAULT);

        $stmt=$pdo->prepare("
            UPDATE users 
            SET fullname=?, nickname=?, address=?, birthday=?, phone=?, username=?, email=?, password=?
            WHERE user_id=?
        ");

        $stmt->execute([
            $d['fullname'],
            $d['nickname'],
            $d['address'],
            $d['birthday'],
            $d['phone'],
            $d['username'],
            $d['email'],
            $hash,
            $_SESSION['user_id']
        ]);

    }

    else{

        $stmt=$pdo->prepare("
            UPDATE users 
            SET fullname=?, nickname=?, address=?, birthday=?, phone=?, username=?, email=?
            WHERE user_id=?
        ");

        $stmt->execute([
            $d['fullname'],
            $d['nickname'],
            $d['address'],
            $d['birthday'],
            $d['phone'],
            $d['username'],
            $d['email'],
            $_SESSION['user_id']
        ]);
    }

    $res->getBody()->write("updated");
    return $res;
});

$app->post('/delete-user', function($req,$res) use ($pdo){
    if(isset($_SESSION['user_id'])){
        $stmt=$pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$_SESSION['user_id']]);
    }

    session_destroy();
    $res->getBody->write("deleted");
    return $res;
});

$app->get('/logout', function($req,$res){
    session_destroy();
    $res->getBody()->write("ok");
    return $res;
});

$app->run();
?>