<?php
namespace MultiRouting\Router;

use MultiRouting\Router\Exceptions\UrlException;
use MultiRouting\Router\Exceptions\SerializationException;
use MultiRouting\Router\Exceptions\VersionException;
use MultiRouting\Router\Exceptions\ResourceUrlException;

class Url
{
    const DEFAULT_SERIALIZATION = 'rest';
    
    protected static $availableSerializations = ['rpc', 'soap', 'rest'];
    
    /**
     * The entire raw URL
     * 
     * (SCRIPT_URL from $_SERVER)
     * 
     * @var string
     */
    protected $raw;
    
    /**
     * Serialization
     * 
     * (JSON RPC, SOAP, REST)
     *
     * @var string
     */
    protected $serialization;
    
    /**
     * Resource URL
     * 
     * (without serialization and version)
     *
     * @var string
     */
    protected $resourceUrl;
    
    public function __construct($rawUrl)
    {
        $this->setRaw($rawUrl);
        
        try {
            $this->parseRaw();
        } catch (UrlException $e) {
            throw new UrlException('The requested URL is invalid.', 400, $e);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }
    
    /**
     * 
     * @param string $raw
     */
    protected function setRaw($raw)
    {
        $this->raw = $raw;
    }
    
    /**
     * 
     * @throws UrlException
     */
    protected function parseRaw()
    {
        preg_match('/^[\/]?([a-z]*)?\/([0-9].[0-9])(\/.*)/', $this->raw, $parsedUrl);

        // prepare URL component structure
        $parsedUrl = is_array($parsedUrl) ? $parsedUrl : [];

        $array_key_value = function ($key, $array, $valueIfNotFound = null) {
            return array_key_exists($key, $array) ? $array[$key] : $valueIfNotFound;
        };

        $parsedUrl[1] = $array_key_value(1, $parsedUrl);
        $parsedUrl[2] = $array_key_value(2, $parsedUrl);
        $parsedUrl[3] = $array_key_value(3, $parsedUrl);

        // check URL component available set values
        try {
            $this->setSerialization($parsedUrl[1]);
        } catch (SerializationException $e) {
            throw new UrlException('The requested URL could not be parsed.', 400, $e);
        }
        
        try {
            $this->filterVersion($parsedUrl[2]);
        } catch (VersionException $e) {
            throw new UrlException('The requested URL could not be parsed.', 400, $e);
        }
        
        try {
            $this->setResourceUrl($parsedUrl[3]);
        } catch (ResourceUrlException $e) {
            throw new UrlException('The requested URL could not be parsed.', 400, $e);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getSerialization()
    {
        return $this->serialization;
    }
    
    /**
     * 
     * @param string $serialization
     * @throws SerializationException
     */
    protected function setSerialization($serialization)
    {
        try {
            $this->serialization = $this->filterSerialization($serialization);
        } catch (SerializationException $e) {
            throw new SerializationException('The requested serialization is invalid.', 400, $e);
        }
    }
    
    /**
     * 
     * @param string $serialization
     * @throws SerializationException
     * @return string
     */
    protected function filterSerialization($serialization)
    {
        $serialization = strtolower($serialization);
        
        if (empty($serialization)) {
            $serialization = static::DEFAULT_SERIALIZATION;
        }
        if (!in_array($serialization, static::$availableSerializations)) {
            throw new SerializationException('The requested serialization is not available.');
        }
        return $serialization;
    }
    
    /**
     * 
     * @return string
     */
    public function getVersion()
    {
        return CURRENT_API_VERSION;
    }
    
    /**
     * 
     * @param string $version
     * @throws VersionException
     */
    protected function filterVersion($version)
    {
        if (empty($version)) {
            throw new VersionException('Version cannot be empty.');
        }
        if ($version != CURRENT_API_VERSION) {
            throw new VersionException('Invalid requested version: ' . $version);
        }
    }

    /**
     * @return string
     */
    public function getResourceUrl()
    {
        return $this->resourceUrl;
    }

    /**
     * @param string $resourceUrl
     * @throws ResourceUrlException
     */
    protected function setResourceUrl($resourceUrl)
    {
        try {
            $this->resourceUrl = $this->filterResourceUrl($resourceUrl);
        } catch (ResourceUrlException $e) {
            throw new ResourceUrlException('The requested resource URL is invalid.', 400, $e);
        }
    }

    /**
     * @note Improvement needed. (This returns value, where filterVersion doesn't.)
     *
     * @param string $resourceUrl
     * @throws ResourceUrlException
     * @return string
     */
    protected function filterResourceUrl($resourceUrl)
    {
        if ('' === $resourceUrl) {
            throw new ResourceUrlException('The requested resource URL is empty.', 400);
        }

        return $resourceUrl;
    }
}