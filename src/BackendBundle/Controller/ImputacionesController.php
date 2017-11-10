<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblImputaciones;
use BackendBundle\Entity\TblImputProv;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ImputacionesController extends Controller
{
	/**
	*	@Route("/imputaciones",name="provincia_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblImputaciones")->findAll();
		$data = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => $result,
		);
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);		
		
	}
	/**
	*	@Route("/imputaciones/prov",name="provincia_all")
	*	@Method({"GET"})
	*/

	public function allProvAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblImputProv")->findAll();
		$data = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => $result,
		);
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);		
		
	}
}
?>