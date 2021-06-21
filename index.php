<?php

$requestUrl = $_SERVER['REQUEST_URI'];
$requestParts = explode('/', rtrim($requestUrl, '/'));
$controllerName = count($requestParts) > 1 ? $requestParts[1] : "Home";
$controllerFileName = "controllers/" . $controllerName . ".php";
$methodName = count($requestParts) > 2 ? $requestParts[2] : "index";

if (!file_exists($controllerFileName)) {
    $controllerName = "CustomError";
    $controllerFileName = "controllers/" . $controllerName . ".php";
}

require_once $controllerFileName;
$controller = new $controllerName;
$controller->$methodName();

//TODO Check https://github.com/phprouter/main for a better example
// https://www.youtube.com/watch?v=6HJXR4G_szo&ab_channel=StoyanCheresharov

/**
 * "Routes" have nothing particularly to do with MVC.
 * A "route" as it is colloquially understood is some function/class/code that resolves a URL
 * to an executable piece of code, usually a controller. Well, you can have that "for free"
 * with PHP's standard behaviour by using URLs like /controllers/foo-controller.php,
 * in which is code which will execute a controller. Your controller doesn't even need to be a class,
 * it just needs to be something which receives a request and can decide which model actions to
 * invoke and/or what view/response to send.
 * That's all MVC is about: having a core model which contains everything your app can "do",
 * having views separately from that which provide a UI for your model, and lastly having
 * a controller which acts on user input (here: HTTP requests). That separation is simply so
 * you can adapt your app (the model) to different scenarios easily by swapping out the UI (view)
 * and input handlers (controllers). Nothing in that prescribes the use of classes, routes or anything else.
 *
 */