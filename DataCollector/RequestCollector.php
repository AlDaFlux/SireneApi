<?php

namespace Aldaflux\SireneApiBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Aldaflux\SireneApiBundle\Service\SireneApiService;

class RequestCollector extends DataCollector implements DataCollectorInterface
{
    
    private $sireneApiService;

    
    public function __construct(SireneApiService $sireneApiService)
    {
        $this->sireneApiService = $sireneApiService;
    }   
    
    
     public function collect(Request $request, Response $response, \Throwable $exception = null) : void
    {
         if ($this->sireneApiService->GetUsed())
         {
            $this->data = [
                'acceptable_content_types' => $request->getAcceptableContentTypes(),
                'logs' =>  $this->sireneApiService->getLogs(),
                'errorlogs' =>  $this->sireneApiService->getErrorLogs(),
                'token' =>  $this->sireneApiService->GetToken(),
                'used' =>  $this->sireneApiService->GetUsed(),
            ];
         }
         else
         {
            $this->data = [
                'acceptable_content_types' => $request->getAcceptableContentTypes(),
                'logs' =>  $this->sireneApiService->getLogs(),
                'token' =>  "-----",
                'used' =>  $this->sireneApiService->GetUsed(),
            ];
             
         }
    }

    public function reset() : void 
    {
        $this->data = [];
    }
   
    public function getName() : string
    {
        return 'aldaflux_sirene_api.request_collector';
    }

    

    public function getToken()
    {
        return $this->data['token'];
    }
    
    public function getLogs()
    {
        return $this->data['logs'];
    }
    public function getErrorLogs()
    {
        return $this->data['errorlogs'];
    }
    
    public function getUsed()
    {
        return $this->data['used'];
    }
    
    

     public function LogCount()
    {
        return 111;
    }

    public function getAcceptableContentTypes()
    {
        return $this->data['acceptable_content_types'];
    }
    
    
}