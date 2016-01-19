<?php
namespace MultiRouting\Adapters\JsonRpc\Response;

class ContentFactory
{

    /**
     * Build content from an application exception
     *
     * @param int $id
     * @param \Exception $exception
     * @return Content
     */
    public function buildFromException(\Exception $exception, $id = null)
    {
        $error = new Error(
            $exception->getCode(),
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
    public function buildFromError($error, $id = null)
    {
        $error = new Error(
            $error->getCode(),
            $error->getMessage()
        );

        return Content::buildError($id, $error);
    }

    /**
     * @param int $id
     * @param mixed $result
     * @return Content
     */
    public function buildFromResult($result, $id = null)
    {
        return Content::buildResult($id, $result);
    }

}