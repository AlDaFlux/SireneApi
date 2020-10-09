<?php

namespace Aldaflux\SireneApiBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestCollector extends DataCollector implements DataCollectorInterface
{
    
    private $container;

    /**
     * Constructor.
     *
     * We don't inject the message logger and mailer here
     * to avoid the creation of these objects when no emails are sent.
     *
     * @param ContainerInterface $container A ContainerInterface instance
  */
    public function __construct()
    {
//        $this->container = $container;
    }   
     public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'method' => $request->getMethod(),
            'acceptable_content_types' => $request->getAcceptableContentTypes(),
        ];
    }

    public function reset()
    {
        $this->data = [];
    }
   
    public function getName()
    {
        return 'aldaflux_sirene_api.request_collector';
    }

    
 public function getMethod()
    {
        return $this->data['method'];
    }

     public function LogCount()
    {
        return 666;
    }

    public function getAcceptableContentTypes()
    {
        return $this->data['acceptable_content_types'];
    }
    
    
}