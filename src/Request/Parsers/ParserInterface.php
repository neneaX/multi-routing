<?php
namespace MultiRouting\Request\Parsers;

interface ParserInterface
{
    /**
     * @return mixed
     */
    public function getContent();
}