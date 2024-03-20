<?php
    
    namespace App\EventListener;
    
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Twig\Environment;
    
    class ExceptionListener
    {
        private $twig;
        
        public function __construct(Environment $twig)
        {
            $this->twig = $twig;
        }
        
        public function onKernelException(ExceptionEvent $event)
        {
            // Get the exception object from the event
            $exception = $event->getThrowable();
            
            // Check if it's a NotFoundHttpException (404)
            if ($exception instanceof NotFoundHttpException) {
                // Render your custom 404 template
                $response = new Response($this->twig->render('bundles/TwigBundle/Exception/index.html.twig'));
                $event->setResponse($response);
            }
        }
    }
