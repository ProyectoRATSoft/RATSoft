<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblProveedores;
use BackendBundle\Entity\TblHashes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProveedoresController extends Controller
{
	/**
	*	@Route("/Proveedores",name="proveedores_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblProveedores")->findAll();
		$hash = $em->getRepository("BackendBundle:TblHashes")->findOneBy(
			array(
				"id" => "1"
			)
		);
		$data = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'hash' => $hash->getHash(),
			'data' => $result,
		);
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);		
		
	}

	public function getHashAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		//$result = $em->getRepository("BackendBundle:TblProveedores")->findAll();
		$hash = $em->getRepository("BackendBundle:TblHashes")->findOneBy(
			array(
				"id" => "1"
			)
		);
		$data = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'hash' => $hash->getHash(),
			'data' => '',
		);
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);		
		
	}

}
?>