<?php
namespace MultiRouting\Adapters\JsonRpc\Response;

class ContentFactory
{

    /**
     * Build content from an application exception
     *
     * @param \Exception $exception
     * @return Content
     */
    public function buildFromException(\Exception $exception)
    {
        /**
         * @todo map specific errors
         */
        $error = new Error(
            Error::GENERAL_APPLICATION_CODE,
            $exception->getMessage()
        );

        return Content::buildError(0, $error);
    }

    /**
     * Build content from an application error
     *
     * @param $error
     * @return Content
     */
    public function buildFromError($error)
    {
        /**
         * @todo map specific errors
         */
        $error = new Error(
            Error::GENERAL_APPLICATION_CODE,
            $error->getMessage()
        );

        return Content::buildError(0, $error);
    }

    /**
     * @param $result
     * @return Content
     */
    public function buildFromResult($result)
    {
        return Content::buildResult(0, $result);
    }

}