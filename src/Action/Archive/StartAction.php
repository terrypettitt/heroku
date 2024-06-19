<?php

function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // allow all origins
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
    echo "You have CORS!";
}



namespace OTHelloWorld\Action\Archive;

use OpenTok\OpenTok;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class StartAction
{
    /**
     * @var OpenTok
     */
    protected $opentok;

    public function __construct(ContainerInterface $container)
    {
        $this->opentok = $container->get(OpenTok::class);
    }

    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $sessionId = $data['sessionId'];
        $archive = $this->opentok->startArchive($sessionId, ['name' => 'Getting Started Sample Archive']);

        return new JsonResponse($archive->toArray());
    }
}
