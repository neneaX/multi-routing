<?php
namespace MultiRouting\Adapters\Main;

use Illuminate\Http\Request;
use MultiRouting\Route as BaseRoute;

/**
 * Class Route
 * @package MultiRouting\Adapters\Main
 */
class Route extends BaseRoute
{

    /**
     * @param Request $request
     */
    public function prepareRun(Request $request)
    {
        // do nothing
    }

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