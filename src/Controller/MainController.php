<?php

/*
 * This file is part of the Beanstalkd Admin package.
 *
 * (c) Matthieu Napoli
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BeanstalkdAdmin\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * This is the main controller serving the main content
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class MainController
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function actionIndex(Request $request, Response $response, array $args)
    {
        $response->setContent($this->twig->render('index.twig'));

        return $response;
    }
}
