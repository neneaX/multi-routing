<?php
namespace MultiRouting\Helpers;

class WsdlParser
{

    /**
     * Path to server WSDL
     *
     * @var string
     */
    protected $wsdl;

    /**
     * The request object
     *
     * @var \DOMDocument
     */
    protected $request;

    /**
     * Set the path to server WSDL
     *
     * @param string $path            
     * @throws \Exception
     */
    public function setWsdl($path)
    {
        if (! file_exists($path)) {
            throw new \Exception('The path does not exist.');
        }
        $this->wsdl = $path;
    }
    
    /**
     * 
     * @return string
     */
    public function getRequestString()
    {
        return $this->request->saveXML();
    }

    /**
     * Set the request object
     *
     * @param mixed $request            
     */
    public function setRequest($request)
    {
        switch (true) {
            case is_string($request):
                $this->setRequestFromString($request);
                break;
            
            case ($request instanceof \DOMDocument):
                $this->request = $request;
                break;
            
            default:
                break;
        }

        if (!$this->request) {
            throw new \InvalidArgumentException('The input is not allowed.');
        }
    }

    /**
     * Validate if the WSDL path is set
     *
     * @throws \Exception
     */
    protected function validateWsdl()
    {
        if (empty($this->wsdl)) {
            throw new \Exception('WSDL not set');
        }
    }

    /**
     * Validate if the request is set
     *
     * @throws \Exception
     */
    protected function validateRequest()
    {
        if (! $this->request instanceof \DOMDocument) {
            throw new \Exception('Request not set');
        }
    }

    /**
     * Get the called method from the request
     *
     * @throws \Exception
     * @return mixed
     */
    public function getCalledMethod()
    {
        $this->validateRequest();
        
        $bodyNodeList = $this->request->getElementsByTagName('Body');
        foreach ($bodyNodeList as $bodyNode) {
            // get elements with namespaces
            $methodNode = $bodyNode->firstChild;
            if ($methodNode && $methodNode instanceof \DOMElement) {
                $methodNameParts = explode(':', $methodNode->nodeName);
                return end($methodNameParts);
            }
        }
        
        throw new \Exception('No method found');
    }

    /**
     * Get all the parameters from the called method (from the request)
     *
     * @param string $name            
     * @return array
     */
    public function getCalledParams()
    {
        $this->validateRequest();
        
        $params = [];
        
        $bodyNodeList = $this->request->getElementsByTagName('Body');
        foreach ($bodyNodeList as $bodyNode) {
            // get elements with namespaces
            $methodNode = $bodyNode->firstChild;
            if ($methodNode && $methodNode instanceof \DOMElement) {
                foreach ($methodNode->childNodes as $paramNode) {
                    if ($paramNode && $paramNode instanceof \DOMElement) {
                        $params[$paramNode->nodeName] = $paramNode->nodeValue;
                    }
                }
            }
        }
        
        return $params;
    }

    /**
     * Get a specific parameter from the called method (from the request)
     *
     * @param string $name            
     * @return multitype:string|null
     */
    public function getCalledParam($name)
    {
        $this->validateRequest();
        
        $bodyNodeList = $this->request->getElementsByTagName('Body');
        foreach ($bodyNodeList as $bodyNode) {
            // get elements with namespaces
            $methodNode = $bodyNode->firstChild;
            if ($methodNode && $methodNode instanceof \DOMElement) {
                foreach ($methodNode->childNodes as $paramNode) {
                    if ($paramNode && $paramNode instanceof \DOMElement) {
                        if ($paramNode->nodeName == $name) {
                            return $paramNode->nodeValue;
                        }
                    }
                }
            }
        }
        
        return null;
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function removeParam($name)
    {
        $this->validateRequest();
        $oldRequest = $this->request->saveXML();
        
        $bodyNodeList = $this->request->getElementsByTagName('Body');
        foreach ($bodyNodeList as $bodyNode) {
            // get elements with namespaces
            $methodNode = $bodyNode->firstChild;
            if ($methodNode && $methodNode instanceof \DOMElement) {
                foreach ($methodNode->childNodes as $paramNode) {
                    if ($paramNode && $paramNode instanceof \DOMElement) {
                        if ($paramNode->nodeName == $name) {
                            $methodNode->removeChild($paramNode);
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Validate if a specific method exists in the server WSDL
     *
     * @param string $methodName            
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function validateMethodExists($methodName)
    {
        $this->validateWsdl();
        $this->validateRequest();
        
        if (empty($methodName)) {
            throw new \InvalidArgumentException('Invalid method');
        }
        $methodNames = [];
        $xmlCargo = simplexml_load_file($this->wsdl);
        for ($i = 0; $i < count($xmlCargo->portType->operation); $i ++) {
            $node = $xmlCargo->portType->operation[$i];
            $methodNames[] = trim((string) $node->attributes()->name);
        }
        
        if (! in_array($methodName, $methodNames)) {
            throw new \Exception('Method does not exists in WSDL');
        }
    }

    /**
     * Set the request object from a parsed XML (string)
     *
     * @param string $request            
     * @throws \Exception
     */
    protected function setRequestFromString($request)
    {
        $DOM = new \DOMDocument('1.0', 'UTF-8');
        $DOM->preserveWhiteSpace = false;
        $status = $DOM->loadXML($request);
        
        if (! $status) {
            // try to convert to utf-8
            $request = iconv('iso-8859-1', 'utf-8', $request);
            $status = $DOM->loadXML($request);
        }
        if (! $status) {
            throw new \Exception('String is not valid DOM.');
        }
        
        $this->request = $DOM;
    }
}