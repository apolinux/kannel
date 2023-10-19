<?php

if(! isset($_GET['type'])){
  http_response_code(500);
  echo 'type not defined' ;
  exit(0) ;
}

switch($_GET['type']){
    case 0 :
        http_response_code(202);
        echo '0: Accepted for delivery' ;
        exit(0);

    case 1 :
        http_response_code(202);
        echo '3: Queued for later delivery' ;
        exit(0);

    case 2 :
        http_response_code(401);
        echo 'Input error' ;
        exit(0);

    case 3:
        http_response_code(503);
        echo 'Temporal failure, try again later.' ;
        exit(0);

    case 4:
        sleep(5);
        die();

}
