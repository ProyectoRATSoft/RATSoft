<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblSituacionIva;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use BackendBundle\Entity\TblCompras;
use BackendBundle\Entity\TblVentas;

class ImpositivoController extends Controller
{
	/**
	*	@Route("/impositivo/iva/{id}/{periodo}",name="iva_all")
	*	@Method({"GET"})
	*/

	public function ivaAction($id,$periodo,Request $request){

		$em = $this->getDoctrine()->getManager();
		$explodePeriodo = explode("-",$periodo);
		$mes = $explodePeriodo[1];
		$ano = $explodePeriodo[0];
    	$iva = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'status'=> '',
			'msg' => '',
			'data' => '',
		);
		$compras = $em->getRepository("BackendBundle:TblCompras")->findBy(
			array(
				'empresa' => $id,
				'periodoAno' => $ano,
				'periodoMes' => $mes,
				'activo' => "1",
			));
		$ventas = $em->getRepository("BackendBundle:TblVentas")->findBy(
			array(
				'empresa' => $id,
				'periodoAno' => $ano,
				'periodoMes' => $mes,
				'activo' => "1",
			));
		$iva["data"]["compras"] = $compras;
		$iva["data"]["ventas"] = $ventas;

		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($iva, 'json');

		return new Response($jsonResponse);
    	
	}
	

	
}
?>