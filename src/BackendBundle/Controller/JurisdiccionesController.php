<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblJurisdicciones;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class JurisdiccionesController extends Controller
{
	/**
	*	@Route("/jurisdicciones",name="provincia_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblJurisdicciones")->findAll();
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
			'jurisdiccion' => $request->request->get("jurisdiccion"),
			'codigo' => $request->request->get("codigo"),
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

		if ( empty($respuesta["jurisdiccion"])
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
		
		// Busco en la DB si existe una jurisdiccion con el cuit ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_jurisdiccion = $em->getRepository("BackendBundle:TblJurisdicciones")->findOneBy(
			array(
				'codigo' => $respuesta["codigo"]
			)
		);

    	// Si el codigo no existe, se inserta en la DB.
		if (empty($isset_jurisdiccion)) {
	  	// Instanciamos un objeto jurisdiccion y seteamos sus datos.
			$jurisdiccion = new TblJurisdicciones();

			$jurisdiccion->setNombre($respuesta["jurisdiccion"]);
			$jurisdiccion->setCodigo($respuesta["codigo"]);

			$em->persist($jurisdiccion);
			$em->flush();

		} else {
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Ya existe una jurisdiccion registrada con el codigo ingresado',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		}
		
		$result = $em->getRepository("BackendBundle:TblJurisdicciones")->findAll();
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
		
	}

	public function editAction($id,Request $request) {

		$respuesta = array (
			'jurisdiccion' => $request->request->get("jurisdiccion"),
			'codigo' => $request->request->get("codigo"),
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'La jurisdiccion ha sido registrada con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( empty($respuesta["jurisdiccion"])
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

		//------------------------------------------------------------------------------------------------
		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();
	    $qb->select('v')
	       ->from('BackendBundle:TblJurisdicciones', 'v')
	       ->where('v.id != :id')
           ->andWhere('v.codigo = :codigo OR v.nombre = :jurisdiccion')
	       // ->where('v.codigo = :codigo AND v.id <> :id')
	       ->setParameters(
	       		array(
	       			//'empresa' => $id, 
	       			'codigo' => $respuesta["codigo"], 
	       			'jurisdiccion' => $respuesta["jurisdiccion"],
	       			'id' => $id
	       		)
	       	);
	    
	    $query = $qb->getQuery();
		$validacion = new TblJurisdicciones();	    
	    $validacion = $query->getResult();
		
	    
	    if (!empty($validacion)) {
      		$result = $em->getRepository("BackendBundle:TblJurisdicciones")->findBy(
			array(
				'id' => $id,
			));

      		$data = array(
					'status' => 'ERROR',
					'msg' => 'Esta jurisdiccion esta duplicada',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' =>  $result,				
			);
			$serializer = SerializerBuilder::create()->build();
			// $jsonResponse = $serializer->serialize($data, 'json');
			// return new Response($jsonResponse);
			$result = $em->getRepository("BackendBundle:TblJurisdicciones")->findAll();
			$data["data"] = $result;

			$jsonResponse = $serializer->serialize($data, 'json');
			$response = new Response ();
			$response->setContent($jsonResponse);
			$response->headers->set('Content-Type', 'application/json');
			return $response;
			
			exit();
		}

		//------------------------------------------------------------------------------------------------

		$jurisdiccion = $em->getRepository("BackendBundle:TblJurisdicciones")->findOneBy( array( 'id' => $id ) );

		$jurisdiccion->setNombre($respuesta["jurisdiccion"]);
		$jurisdiccion->setCodigo($respuesta["codigo"]);

		$em->persist($jurisdiccion);
		$em->flush();
			
		
		$result = $em->getRepository("BackendBundle:TblJurisdicciones")->findAll();
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	// public function delAction($id, Request $request){
	// 	$em = $this->getDoctrine()->getManager();
	// 	$jurisdiccion = $em->getRepository("BackendBundle:TblJurisdicciones")->findOneBy(
	// 		array(
	// 			'id' => $id
	// 		)
	// 	);
	// 	$jurisdiccion->setActivo(0);
	// 	$em->persist($jurisdiccion);
	// 	$em->flush();

	// 	$data = array(
	// 			'status' => 'OK',
	// 			'msg' => 'La jurisdiccion ha sido Eliminada con exito',
	// 			'draw' => '',
	// 			'recordsTotal' => '',
	// 			'recordsFiltered' => '',
	// 			'data' => '',
	// 		);
	// 	$em = $this->getDoctrine()->getManager();
	// 	$result = $em->getRepository("BackendBundle:TblJurisdicciones")->findBy(array('activo' => 1));
	// 	$data["data"] = $result;
	// 	$serializer = SerializerBuilder::create()->build();
	// 	$jsonResponse = $serializer->serialize($data, 'json');
	// 	return new Response($jsonResponse);

	// }
	
}
?>