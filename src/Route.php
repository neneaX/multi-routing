<?php
namespace MultiRouting;

use Illuminate\Http\Request;
use Illuminate\Routing\Route as BaseRoute;

/**
 * Class Route
 * @package MultiRouting
 */
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

    /**
     * @param Request $request
     */
    public abstract function prepareRun(Request $request);

}