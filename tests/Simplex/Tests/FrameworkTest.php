<?php
// example.com/tests/Simplex/Tests/FrameworkTest.php
namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends TestCase
{
    protected $matcher;
    protected $controllerResolver;
    protected $argumentResolver;

    public function testNotFoundHanding()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    private function getFrameworkForException($exception)
    {
        $matcher = $this->createMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception));
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock('Symfony\Component\Routing\RequestContext')));
        $controllerResolver = $this->createMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
        $argumentResolver = $this->createMock('Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface');

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }

    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());

        $response = $framework->handle(new Request());

        $this->assertEquals(500, $response->getStatusCode());
    }
}
