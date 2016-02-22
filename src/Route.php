<?php
namespace MultiRouting;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as BaseRoute;

class Route extends BaseRoute
{

    const COLLECTION_IDENTIFIER_SEPARATOR = '#';

    /**
     * @param array $pieces
     * @return string
     */
    protected function glueCollectionIdentifierPieces(array $pieces = [])
    {
        return implode(
            static::COLLECTION_IDENTIFIER_SEPARATOR,
            $pieces
        );
    }

    /**
     * @return string
     */
    public function getCollectionIdentifier()
    {
        return $this->glueCollectionIdentifierPieces([
            $this->domain(),
            $this->getUri()
        ]);
    }

    public function run(Request $request)
    {
        $this->container = $this->container ?: Container::getInstance();
        return parent::run($request);
    }
}