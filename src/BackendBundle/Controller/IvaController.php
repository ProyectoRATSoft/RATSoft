<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblSituacionIva;
use BackendBundle\Entity\TblHashes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class IvaController extends Controller
{
	/**
	*	@Route("/iva",name="iva_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblSituacionIva")->findAll();
		$hash = $em->getRepository("BackendBundle:TblHashes")->findOneBy(
			array(
				"id" => "4"
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
	/**
	*	@Route("/iva/{id}",name="iva_findById")
	*	@Method({"GET"})
	*/

	public function getHashAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$hash = $em->getRepository("BackendBundle:TblHashes")->findOneBy(
			array(
				"id" => "4"
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

	public function findByIdAction($id,Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblSituacionIva")->findBy(array('id' => $id));
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