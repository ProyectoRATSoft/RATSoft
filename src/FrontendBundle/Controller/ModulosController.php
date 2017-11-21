<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ModulosController extends Controller
{
    public function contableAction()
    {
        return $this->render('FrontendBundle:Empresas:contable-home.html.twig');
    }

    public function impositivoAction()
    {
        return $this->render('FrontendBundle:Empresas:impositivo-home.html.twig');
    }

    public function razonsocialAction()
    {
        return $this->render('FrontendBundle:Empresas:razones_sociales-home.html.twig');
    }


    public function userAction()
    {
    	return $this->render('FrontendBundle:Users:register.html.twig');
    }
}
