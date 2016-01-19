<?php
namespace MultiRouting\Adapters\Soap\Response;

class ContentFactory
{

    /**
     * Build content from an application exception
     *
     * @param int $id
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

        return Content::buildError($id, $error);
    }

    /**
     * Build content from an application error
     *
     * @param int $id
     * @param $error
     * @return Content
     */
    public function buildFromError($id = 0, $error)
    {
        /**
         * @todo map specific errors
         */
        $error = new Error(
            Error::GENERAL_APPLICATION_CODE,
            $error->getMessage()
        );

        return Content::buildError($id, $error);
    }

    /**
     * @param int $id
     * @param mixed $result
     * @return Content
     */
    public function buildFromResult($id = 0, $result)
    {
        return Content::buildResult($id, $result);
    }

}