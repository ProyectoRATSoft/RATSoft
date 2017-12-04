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

	public function newAction(Request $request) {

		$respuesta = array (
			'nombre' => $request->request->get("nombre"),
			'codigo' => $request->request->get("codigo"),
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'El rubro ha sido registrado con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( empty($respuesta["nombre"])
			|| empty($respuesta["codigo"])
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
		
		// Busco en la DB si existe una imputacion con el codigo ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_imputacion = $em->getRepository("BackendBundle:TblImputaciones")->findOneBy(
			array(
				'codigo' => $respuesta["codigo"]
			)
		);

    	// Si el codigo no existe, se inserta en la DB.
		if (empty($isset_imputacion)) {
	  	// Instanciamos un objeto imputacion y seteamos sus datos.
			$imputacion = new TblImputaciones();

			$imputacion->setNombre($respuesta["nombre"]);
			$imputacion->setCodigo($respuesta["codigo"]);

			$em->persist($imputacion);
			$em->flush();

		} else {
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Ya existe una imputacion registrado con el codigo ingresado',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		}
		
		$result = $em->getRepository("BackendBundle:TblImputaciones")->findAll();
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
		
	}

	public function editAction($id,Request $request) {

		$respuesta = array (
			'nombre' => $request->request->get("nombre"),
			'codigo' => $request->request->get("codigo"),
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'la imputacion ha sido registrado con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( empty($respuesta["nombre"])
			|| empty($respuesta["codigo"])
			) 
		{
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
		
		// Busco en la DB si existe un imputacion con el codigo ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_imputacion = $em->getRepository("BackendBundle:TblImputaciones")->findOneBy(
			array(
				'codigo' => $respuesta["codigo"]
			)
		);

    	// Si el codigo no existe, se inserta en la DB.
		if (empty($isset_imputacion)) {
	  	// Instanciamos un objeto imputacion y seteamos sus datos.
			$imputacion = $em->getRepository("BackendBundle:TblImputaciones")->findOneBy( array( 'id' => $id ) );

			$imputacion->setNombre($respuesta["nombre"]);
			$imputacion->setCodigo($respuesta["codigo"]);

			$em->persist($imputacion);
			$em->flush();
				
		} else {
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Ya existe una imputacion registrada con el codigo ingresado',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		}


		//------------------------------------------------------------------------------------------------
		// $em = $this->getDoctrine()->getManager();
		// $qb = $em->createQueryBuilder();
	 //    $qb->select('v')
	 //       ->from('BackendBundle:TblImputaciones', 'v')
	 //       ->where('v.nombre = :nombre AND v.codigo = :codigo AND v.id <> :id')
	 //       ->setParameters(
	 //       		array(
	 //       			//'empresa' => $id, 
	 //       			'codigo' => $respuesta["codigo"], 
	 //       			'nombre' => $respuesta["nombre"],
	 //       			'id' => $id
	 //       		)
	 //       	);
	    
	 //    $query = $qb->getQuery();
		// $validacion = new TblImputaciones();	    
	 //    $validacion = $query->getResult();
		
	    
	 //    if (!empty($validacion)) {
  //     		$result = $em->getRepository("BackendBundle:TblImputaciones")->findBy(
		// 	array(
		// 		'id' => $id,
		// 	));

  //     		$data = array(
		// 			'status' => 'ERROR',
		// 			'msg' => 'Este rubro esta duplicado',
		// 			'draw' => '',
		// 			'recordsTotal' => '',
		// 			'recordsFiltered' => '',
		// 			'data' =>  $result,				
		// 	);
		// 	$serializer = SerializerBuilder::create()->build();
		// 	$jsonResponse = $serializer->serialize($data, 'json');
		// 	return new Response($jsonResponse);
			
		// 	exit();
		// }

		// //------------------------------------------------------------------------------------------------


		// $imputacion = $em->getRepository("BackendBundle:TblImputaciones")->findOneBy(
		// 	array(
		// 		'id' => $id
		// 	)
		// );

		
		// $imputacion->setNombre($respuesta["nombre"]);
		// $imputacion->setCodigo($respuesta["codigo"]);

		// $em->persist($imputacion);
		// $em->flush();

		
		$result = $em->getRepository("BackendBundle:TblImputaciones")->findAll();
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
		
	}
}
?>