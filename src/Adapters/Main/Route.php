<?php
namespace MultiRouting\Adapters\Main;

use MultiRouting\Route as BaseRoute;

class Route extends BaseRoute
{

    /**
     * @return string
     */
    public function getCollectionIdentifier()
    {
        return $this->glueCollectionIdentifierPieces([
            Adapter::name,
            $this->domain(),
            $this->getUri()
        ]);
    }
}