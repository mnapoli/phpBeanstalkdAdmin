<?php

use Proton\Application;
use Puli\TwigExtension\PuliTemplateLoader;
use Puli\TwigExtension\PuliExtension;

$app = new Application;

$puliFactoryClass = PULI_FACTORY_CLASS;
$puliFactory = new $puliFactoryClass();
$puliRepo = $puliFactory->createRepository();
$puliDiscovery = $puliFactory->createDiscovery($puliRepo);
$puliUrlGenerator = $puliFactory->createUrlGenerator($puliDiscovery);

$twigLoader = new \Twig_Loader_Chain([
    new PuliTemplateLoader($puliRepo),
    new \Twig_Loader_Filesystem(__DIR__.'/views'),
]);

$twig = new \Twig_Environment($twigLoader);

$twig->addExtension(new PuliExtension($puliRepo, $puliUrlGenerator));

$app['Twig_Environment'] = $twig;

$app->get('/', 'BeanstalkdAdmin\Controller\MainController::actionIndex');

return $app;
