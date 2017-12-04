<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblComprobantes;
use BackendBundle\Entity\TblTiposComp;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ComprobantesController extends Controller
{
	/**
	*	@Route("/comprobantes",name="provincia_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblComprobantes")->findAll();
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
	*	@Route("/comprobantes/tipos",name="comprobantes_tipos")
	*	@Method({"GET"})
	*/

	public function tiposAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblTiposComp")->findAll();
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

	public function newAction(Request $request){
  //   	$em = $this->getDoctrine()->getManager();
		// $result = $em->getRepository("BackendBundle:TblTiposComp")->findAll();
		// $data = array(
		// 	'draw' => '',
		// 	'recordsTotal' => '',
		// 	'recordsFiltered' => '',
		// 	'data' => $result,
		// );
		// $serializer = SerializerBuilder::create()->build();
		// $jsonResponse = $serializer->serialize($data, 'json');
		// return new Response($jsonResponse);

	 		 

		$respuesta = array (
			 'detalle' => $request->request->get("detalle"),
	         'codigo' => $request->request->get("codigo"),
	         'tipo_comp' => $request->request->get("tipo_comp"),
	         'blk_exe' => $request->request->get("blk_exe"),
	         'blk_perciva' => $request->request->get("blk_perciva"),
	         'blk_perciibb' => $request->request->get("blk_perciibb"),
	         'blk_ret' => $request->request->get("blk_ret"),
	         'blk_netos' => $request->request->get("blk_netos"),
	         'blk_iva' => $request->request->get("blk_iva"),
	         'blk_nograv' => $request->request->get("blk_nograv"),
	         'blk_total' => $request->request->get("blk_total"),
	         'autoiva' => $request->request->get("autoiva"),
	         'autoneto' => $request->request->get("autoneto"),
	         'autototal'=> $request->request->get("autototal")
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'jurisdiccion ha sido registrada con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if (   !$respuesta["detalle"]
			|| !$respuesta["codigo"]
			|| !$respuesta["tipo_comp"]
			|| !is_numeric($respuesta["blk_exe"])
			|| !is_numeric($respuesta["blk_perciva"])
			|| !is_numeric($respuesta["blk_perciibb"])
			|| !is_numeric($respuesta["blk_ret"])
			|| !is_numeric($respuesta["blk_netos"])
			|| !is_numeric($respuesta["blk_iva"])
			|| !is_numeric($respuesta["blk_nograv"])
			|| !is_numeric($respuesta["blk_total"])
			|| !is_numeric($respuesta["autoneto"])
			|| !is_numeric($respuesta["autototal"])
			//|| empty($respuesta["codigo"])
			) {
				$data = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' => '',
					);
				
				$jsonResponse = $serializer->serialize($data, 'json');
				return new Response($jsonResponse);
				exit();
		}
		
		// Busco en la DB si existe una jurisdiccion con el cuit ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_comprobante = $em->getRepository("BackendBundle:TblComprobantes")->findOneBy(
			array(
				'detalle' => $respuesta["detalle"]
			)
		);

		$isset_codigo = $em->getRepository("BackendBundle:TblComprobantes")->findOneBy(
			array(
				'codigo' => $respuesta["codigo"]
			)
		);

		$isset_tipo_comprobante = $em->getRepository("BackendBundle:TblTiposComp")->findOneBy(
			array(
				'tipo_comp' => $respuesta["tipo_comp"]
			)
		);

    	// Si el codigo no existe, se inserta en la DB.
		if (empty($isset_comprobante) || empty($isset_codigo)) {
	  	// Instanciamos un objeto comprobante y seteamos sus datos.
			$comprobante = new TblComprobantes();
			$comprobante->setDetalle($respuesta["detalle"]);
			$comprobante->setCodigo($respuesta["codigo"]);
			// $em->persist($comprobante);
			// $em->flush();

		} 
		else 
		{
			
		}
		

		if (empty($isset_tipo_comprobante)) {
	  	// Instanciamos un objeto comprobante y seteamos sus datos.
			$tipo_comprobante = new TblTiposComp();
			$tipo_comprobante->setTipoComp($respuesta["tipo_comp"]);
			$tipo_comprobante->setCodComp($comprobante);
			$tipo_comprobante->setBlkExe($respuesta["blk_exe"]);
			$tipo_comprobante->setBlkPerciva($respuesta["blk_perciva"]);
			$tipo_comprobante->setBlkPerciibb($respuesta["blk_perciibb"]);
			$tipo_comprobante->setBlkRet($respuesta["blk_ret"]);
			$tipo_comprobante->setBlkNetos($respuesta["blk_netos"]);
			$tipo_comprobante->setBlkIva($respuesta["blk_iva"]);
			$tipo_comprobante->setBlkTotal($respuesta["blk_total"]);
			$tipo_comprobante->setBlkNograv($respuesta["blk_nograv"]);
			$tipo_comprobante->setAutoiva($respuesta["autoiva"]);
			$tipo_comprobante->setAutoneto($respuesta["autoneto"]);
			$tipo_comprobante->setAutototal($respuesta["autototal"]);

			// $em->persist($tipo_comprobante);
			// $em->flush();

		} 
		// else {
		// 	$data = array(
		// 		'status' => 'ERROR',
		// 		'msg' => 'Ya existe una jurisdiccion registrada con el codigo ingresado',
		// 		'draw' => '',
		// 		'recordsTotal' => '',
		// 		'recordsFiltered' => '',
		// 		'data' => '',
		// 	);
		// }
		
		$result = $em->getRepository("BackendBundle:TblTiposComp")->findAll();
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
			
		
	}
}
?>