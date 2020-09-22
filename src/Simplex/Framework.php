<?php
// example.com/src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

class Framework extends HttpKernel\HttpKernel
{
    protected $dispatcher;
    protected $matcher;
    protected $controllerResolver;
    protected $argumentResolver;

    public function __construct($routes)
    {
        $context = new Routing\RequestContext();  
        $this->matcher = new Routing\Matcher\UrlMatcher($routes, $context);

        $this->controllerResolver = new HttpKernel\Controller\ControllerResolver();
        $this->argumentResolver = new HttpKernel\Controller\ArgumentResolver();

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($this->matcher, new RequestStack()));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));

        parent::__construct($dispatcher, $this->controllerResolver, new RequestStack(), $this->argumentResolver);
    }

    public function handle(Request $request, $type = HttpKernel\HttpKernel::MASTER_REQUEST, $catch = true)
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('An error occurred', 500);
        }

        $this->dispatcher->dispatch(new ResponseEvent($response, $request));

        return $response;
    }
}