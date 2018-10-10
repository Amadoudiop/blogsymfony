<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("list", name="listBlog")
     */
    public function listAction()
    {
        return $this->render(
            'Blog/list.html.twig'
        );
    }
}
