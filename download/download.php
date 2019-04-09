<?php

require __DIR__.'/../vendor/autoload.php';

if(isset($argv[1])) {
    $name = '\Controller\\' . strtoupper($argv[1]);
    if(isset($argv[2]) && isset($argv[3]))
        $controller = new $name($argv[2], $argv[3]);
    else
        $controller = new $name();

    $controller->getData();
}
else
{
    print_r("\nphp download.php [CONTROLLER_FILE]\n\n-->Controllers List: \n\n");
    foreach (scandir(__DIR__."/../src/Controller/") as $item)
    {
        if($item != "." && $item != ".." && $item != "Controller.php")
        print_r("---->".$item."\n");
    }
    print_r("\n\n");
}
