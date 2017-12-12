<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblRubros;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RubrosController extends Controller
{
	/**
	*	@Route("/rubros",name="rubros_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblRubros")->findAll();
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
			'servicio' => $request->request->get("servicio"),
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
			|| !is_numeric($respuesta["servicio"])
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
		
		// Busco en la DB si existe una rubros con el nombre ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_rubros = $em->getRepository("BackendBundle:TblRubros")->findOneBy(
			array(
				'nombre' => $respuesta["nombre"]
			)
		);

    	// Si el codigo no existe, se inserta en la DB.
		if (empty($isset_rubros)) {
	  	// Instanciamos un objeto rubros y seteamos sus datos.
			$rubros = new TblRubros();

			$rubros->setNombre($respuesta["nombre"]);
			$rubros->setServicio($respuesta["servicio"]);

			$em->persist($rubros);
			$em->flush();

		} else {
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Ya existe un rubro registrado con el nombre ingresado',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		}
		
		$result = $em->getRepository("BackendBundle:TblRubros")->findAll();
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
			'servicio' => $request->request->get("servicio"),
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'el rubro ha sido registrado con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( empty($respuesta["nombre"])
			|| !is_numeric($respuesta["servicio"])
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
	       ->from('BackendBundle:TblRubros', 'v')
	       ->where('v.nombre = :nombre AND v.servicio = :servicio AND v.id <> :id')
	       ->setParameters(
	       		array(
	       			//'empresa' => $id, 
	       			'servicio' => $respuesta["servicio"], 
	       			'nombre' => $respuesta["nombre"],
	       			'id' => $id
	       		)
	       	);
	    
	    $query = $qb->getQuery();
		$validacion = new TblRubros();	    
	    $validacion = $query->getResult();
		
	    
	    if (!empty($validacion)) {
      		$result = $em->getRepository("BackendBundle:TblRubros")->findBy(
			array(
				'id' => $id,
			));

      		$data = array(
					'status' => 'ERROR',
					'msg' => 'Este rubro esta duplicado',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' =>  $result,				
			);
			$serializer = SerializerBuilder::create()->build();
			$jsonResponse = $serializer->serialize($data, 'json');
			return new Response($jsonResponse);
			
			exit();
		}

		//------------------------------------------------------------------------------------------------


		$rubros = $em->getRepository("BackendBundle:TblRubros")->findOneBy(
			array(
				'id' => $id
			)
		);

		
		$rubros->setNombre($respuesta["nombre"]);
		$rubros->setServicio($respuesta["servicio"]);

		$em->persist($rubros);
		$em->flush();

		$result = $em->getRepository("BackendBundle:TblRubros")->findAll();
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
		
	}

}
?>