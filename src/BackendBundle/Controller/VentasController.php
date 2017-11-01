<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblVentas;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class VentasController extends Controller
{
	public function allAction($id,Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$ventas = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(array('empresa' => $id));
		$ventas["data"] = $result;
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($ventas, 'json');
		
		
		return $this->render(
			'FrontendBundle:Ventas:ventas-home.html.twig', 
        	array("respuesta" => $jsonResponse)
        );
        // return new Response($ventas);		
		//return new Response($id);
	}


}