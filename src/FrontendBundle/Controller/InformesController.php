<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InformesController extends Controller
{
	public function ventasAction()
	{
		return $this->render('FrontendBundle:Informes:ventas.html.twig');
	}
}
