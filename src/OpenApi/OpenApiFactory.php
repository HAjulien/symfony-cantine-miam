<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {        
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        foreach($openApi->getPaths()->getPaths() as $key => $path){
            if( $path->getGet() && $path->getGet()->getSummary() === "hidden"){
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        //dd($openApi);
        return $openApi;
    }
}