<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EmpresasController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontendBundle:Empresas:empresaABM.html.twig');
    }
}
