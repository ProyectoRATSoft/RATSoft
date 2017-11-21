<?php

namespace BackendBundle\Controller;

// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblProveedores;
use BackendBundle\Entity\TblSituacionIva;
use BackendBundle\Entity\TblJurisdicciones;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RazonsocialController extends Controller
{
	
	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblProveedores")->findBy(array('activo' => 1));
		$proveedores = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => $result,
		);
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($proveedores, 'json');
		return new Response($jsonResponse);		
	}
	
	public function newAction(Request $request) {

		$respuesta = array (
			'nombre' => $request->request->get("nombre"),
			'cuit' => $request->request->get("cuit"),
			'activo' => $request->request->get("activo"),
			'iva' => (int)$request->request->get("iva"),
			'jurisdiccion' => $request->request->get("jurisdiccion"),
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'La empresa ha sido registrada con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( 
			   empty($respuesta["nombre"])
		 	|| empty($respuesta["cuit"])
			|| empty($respuesta["iva"])
			|| empty($respuesta["jurisdiccion"])
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
		
		// Busco en la DB si existe una empresa con el cuit ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_empresa = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
			array(
				'cuit' => $respuesta["cuit"]
			)
		);

		// Genero el objeto SituacionIva y lo cargo en base al ID recibido
		$situacionIva = new TblSituacionIva();
		$situacionIva = $em->getRepository("BackendBundle:TblSituacionIva")->findOneBy(
			array(
				'id' => $respuesta["iva"]
			)
		);
		// Genero el objeto Provincia y lo cargo en base al ID recibido
		$provincia = new TblJurisdicciones();
		$provincia = $em->getRepository("BackendBundle:TblJurisdicciones")->findOneBy(
			array(
				'id' => $respuesta["jurisdiccion"]
			)
		);
		
    	// Si el cuit no existe, se inserta en la DB.
		if (empty($isset_empresa)) {
	  	// Instanciamos un objeto Empresa y seteamos sus datos.
			$empresa = new TblProveedores();

			$empresa->setNombre($respuesta["nombre"]);
			$empresa->setCuit($respuesta["cuit"]);
			$empresa->setActivo("1");
			$empresa->setIva($situacionIva);
			$empresa->setJurisdiccion($provincia);

			$em->persist($empresa);
			$em->flush();	
		} else {
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Ya existe una empresa registrada con el cuit ingresado',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		}
		
		$result = $em->getRepository("BackendBundle:TblProveedores")->findBy(array('activo' => 1));
		$data["data"] = $result;

		$jsonResponse = $serializer->serialize($data, 'json');
		$response = new Response ();
		$response->setContent($jsonResponse);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
		
	}

	public function delAction($id, Request $request){
		$em = $this->getDoctrine()->getManager();
		$empresa = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
			array(
				'id' => $id
			)
		);
		$empresa->setActivo(0);
		$em->persist($empresa);
		$em->flush();

		$data = array(
				'status' => 'OK',
				'msg' => 'el cliente ha sido Eliminado con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblProveedores")->findBy(array('activo' => 1));
		$data["data"] = $result;
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);

	}

	public function editAction($id,Request $request) {

		$respuesta = array (
			'nombre' => $request->request->get("nombre"),
			'cuit' => $request->request->get("cuit"),			
			'iva' => (int)$request->request->get("iva"),
			'jurisdiccion' => $request->request->get("jurisdiccion"),
		);
		
		$data = array(
				'status' => 'OK',
				'msg' => 'El cliente ha sido editado con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( 
			   empty($respuesta["nombre"])
		 	|| empty($respuesta["cuit"])
		 	|| empty($respuesta["iva"])
			|| empty($respuesta["jurisdiccion"])
			) {
				$data = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' => '',
					);
				//$data = empty($respuesta["nombre"]);
				$jsonResponse = $serializer->serialize($data, 'json');
				return new Response($request);
				exit();
		}


		$em = $this->getDoctrine()->getManager();
		// Genero el objeto SituacionIva y lo cargo en base al ID recibido
		$situacionIva = new TblSituacionIva();
		$situacionIva = $em->getRepository("BackendBundle:TblSituacionIva")->findOneBy(
			array(
				'id' => $respuesta["iva"]
			)
		);
		// Genero el objeto Provincia y lo cargo en base al ID recibido
		$provincia = new TblJurisdicciones();
		$provincia = $em->getRepository("BackendBundle:TblJurisdicciones")->findOneBy(
			array(
				'id' => $respuesta["jurisdiccion"]
			)
		);
		// Instanciamos un objeto Empresa y seteamos sus datos.
		
		$empresa = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
			array(
				'id' => $id
			)
		);
		$empresa->setNombre($respuesta["nombre"]);
		$empresa->setCuit($respuesta["cuit"]);
		$empresa->setActivo("1");
		// $empresa->setActivo($respuesta["activo"]);
		$empresa->setIva($situacionIva);
		$empresa->setJurisdiccion($provincia);

		$em->persist($empresa);
		$em->flush();	

		$result = $em->getRepository("BackendBundle:TblProveedores")->findBy(array('activo' => 1));
		$data["data"] = $result;
		

		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);

	}

}