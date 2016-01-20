<?php
namespace Example;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\Request;
use MultiRouting\Adapters\Soap\Adapter as SoapAdapter;
use MultiRouting\Adapters\JsonRpc\Adapter as JsonRpcAdapter;
use MultiRouting\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class App
{
    /**
     * The application IoC container
     *
     * @var Container
     */
    protected $container;

    /**
     * The application router
     *
     * @var Router
     */
    protected $router;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->container = new Container();

        $this->setRouter();
        $this->initRoutes();
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set the application router
     */
    protected function setRouter()
    {
        $eventsDispatcher = new EventsDispatcher($this->container);

        $this->router = new Router($eventsDispatcher, $this->container);
        $this->router->useAdapters([
            JsonRpcAdapter::name,
            SoapAdapter::name,
            'Rest'
        ]);
    }

    protected function initRoutes()
    {
        require __DIR__ . '/resources/routes.php';
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function run()
    {
        $request = Request::createFromBase(Request::createFromGlobals());

        return $this->handle($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        try {
            $response = $this->router->dispatch($request);
        } catch (NotFoundHttpException $e) {
            // parse request to return an error according to protocol
            exit();
        }

        // @todo re-enable or delete todo and commented line.
        // had an issue with php-fpm: "FastCGI: comm with server "/php-fpm" aborted: error parsing headers: duplicate header 'Content-Type'"
        // $response->sendHeaders();
        $response->send();

        return $response;
    }
}

