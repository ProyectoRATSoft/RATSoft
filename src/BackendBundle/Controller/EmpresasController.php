<?php

namespace BackendBundle\Controller;

// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblEmpresas;
use BackendBundle\Entity\TblSituacionIva;
use BackendBundle\Entity\TblRubros;
use BackendBundle\Entity\TblJurisdicciones;
use BackendBundle\Entity\TblTiposiibb;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class EmpresasController extends Controller
{

	/**
	*	@Route("/empresa/{id}/edit",name="empresa_edit")
	*	@Method({"POST"})
	*/

	public function editAction($id,Request $request) {

		$respuesta = array (
			'nombre' => $request->request->get("nombre"),
			'domicilio' => $request->request->get("domicilio"),
			'localidad' => $request->request->get("localidad"),
			'cuit' => $request->request->get("cuit"),
			'iibbtipo' => $request->request->get("iibbtipo"),
			'iibb' => $request->request->get("iibb"),
			'titular' => $request->request->get("titular"),
			//'activo' => $request->request->get("activo"),			
			'iva' => (int)$request->request->get("iva"),
			'provincia' => $request->request->get("provincia"),
			'rubro' => $request->request->get("rubro"),
		);
		
		$data = array(
				'status' => 'OK',
				'msg' => 'La empresa ha sido registrada con exito'
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( empty($respuesta["nombre"])
			|| empty($respuesta["domicilio"])
			|| empty($respuesta["localidad"])
		 	|| empty($respuesta["cuit"])
		 	|| empty($respuesta["iibbtipo"])
			//|| empty($respuesta["iibb"])
			|| empty($respuesta["titular"])
			//|| empty($respuesta["activo"])
			|| empty($respuesta["iva"])
			|| empty($respuesta["provincia"])
			|| empty($respuesta["rubro"])
			) {
				$data = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios'
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
				'id' => $respuesta["provincia"]
			)
		);
		// Genero el objeto Rubro y lo cargo en base al ID recibido
		$rubro = new TblRubros();
		$rubro = $em->getRepository("BackendBundle:TblRubros")->findOneBy(
			array(
				'id' => $respuesta["rubro"]
			)
		);
		// Genero el objeto Tiposiibb y lo cargo en base al ID recibido
		$tipoiibb = new TblRubros();
		$tipoiibb = $em->getRepository("BackendBundle:TblTiposiibb")->findOneBy(
			array(
				'id' => $respuesta["iibbtipo"]
			)
		);

		// Instanciamos un objeto Empresa y seteamos sus datos.
		
		$empresa = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
			array(
				'id' => $id
			)
		);
		$empresa->setNombre($respuesta["nombre"]);
		$empresa->setDomicilio($respuesta["domicilio"]);
		$empresa->setLocalidad($respuesta["localidad"]);
		$empresa->setCuit($respuesta["cuit"]);
		$empresa->setIibbCod($tipoiibb);
		$empresa->setIibb($respuesta["iibb"]);
		$empresa->setTitular($respuesta["titular"]);
		$empresa->setActivo("1");
		// $empresa->setActivo($respuesta["activo"]);
		$empresa->setIva($situacionIva);
		$empresa->setProvincia($provincia);
		$empresa->setRubro($rubro);	

		$em->persist($empresa);
		$em->flush();	

		
		

		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($request);

	}


	/**
	*	@Route("/empresa/{id}/del",name="empresa_del")
	*	@Method({"GET"})
	*/
	
	public function delAction($id, Request $request){
		$em = $this->getDoctrine()->getManager();
		$empresa = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
			array(
				'id' => $id
			)
		);
		$empresa->setActivo(0);
		$em->persist($empresa);
		$em->flush();

		$data = array(
				'status' => 'OK',
				'msg' => 'La empresa ha sido Eliminada con exito'
			);

		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);

	}

	public function newAction(Request $request) {

		$respuesta = array (
			'nombre' => $request->request->get("nombre"),
			'domicilio' => $request->request->get("domicilio"),
			'localidad' => $request->request->get("localidad"),
			'cuit' => $request->request->get("cuit"),
			'iibbtipo' => $request->request->get("iibbtipo"),
			'iibb' => $request->request->get("iibb"),
			'titular' => $request->request->get("titular"),
			'activo' => $request->request->get("activo"),
			'iva' => (int)$request->request->get("iva"),
			'provincia' => $request->request->get("provincia"),
			'rubro' => $request->request->get("rubro"),
		);
		$data = array(
				'status' => 'OK',
				'msg' => 'La empresa ha sido registrada con exito'
			);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( empty($respuesta["nombre"])
			|| empty($respuesta["domicilio"])
			|| empty($respuesta["localidad"])
		 	|| empty($respuesta["cuit"])
		 	|| empty($respuesta["iibbtipo"])
			//|| empty($respuesta["iibb"])
			|| empty($respuesta["titular"])
			//|| empty($respuesta["activo"])
			|| empty($respuesta["iva"])
			|| empty($respuesta["provincia"])
			|| empty($respuesta["rubro"])
			) {
				$data = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios'
					);
				//w$data = empty($respuesta["nombre"]);
				$jsonResponse = $serializer->serialize($data, 'json');
				return new Response($jsonResponse);
				exit();
		}
		
		// Busco en la DB si existe una empresa con el cuit ingresado.
		$em = $this->getDoctrine()->getManager();
		$isset_empresa = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
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
				'id' => $respuesta["provincia"]
			)
		);
		// Genero el objeto Rubro y lo cargo en base al ID recibido
		$rubro = new TblRubros();
		$rubro = $em->getRepository("BackendBundle:TblRubros")->findOneBy(
			array(
				'id' => $respuesta["rubro"]
			)
		);
		// Genero el objeto Tiposiibb y lo cargo en base al ID recibido
		$tipoiibb = new TblRubros();
		$tipoiibb = $em->getRepository("BackendBundle:TblTiposiibb")->findOneBy(
			array(
				'id' => $respuesta["iibbtipo"]
			)
		);

    	// Si el cuit no existe, se inserta en la DB.
		if (empty($isset_empresa)) {
	  	// Instanciamos un objeto Empresa y seteamos sus datos.
			$empresa = new TblEmpresas();

			$empresa->setNombre($respuesta["nombre"]);
			$empresa->setDomicilio($respuesta["domicilio"]);
			$empresa->setLocalidad($respuesta["localidad"]);
			$empresa->setCuit($respuesta["cuit"]);
			$empresa->setIibbCod($tipoiibb);
			$empresa->setIibb($respuesta["iibb"]);
			$empresa->setTitular($respuesta["titular"]);
			$empresa->setActivo("1");
			$empresa->setIva($situacionIva);
			$empresa->setProvincia($provincia);
			$empresa->setRubro($rubro);	

			//$em->persist($empresa);
			//$em->flush();	
		} else {
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Ya existe una empresa registrada con el cuit ingresado'
			);
		}
		

		$jsonResponse = $serializer->serialize($data, 'json');
		return new Response($jsonResponse);

	}

	/**
	*	@Route("/empresa",name="empresa_all")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblEmpresas")->findBy(array('activo' => 1));
		$empresas = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => $result,
		);
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($empresas, 'json');
		return new Response($jsonResponse);		
	}

	/**
	*	@Route("/empresa/find",name="empresa_find")
	*	@Method({"POST"})
	*/

	public function findByCuitAction(Request $request){

				
		// guardamos la variable POST que viene en el request
		$cuit = $request->request->get("cuit");
    	
    	// obtenemos el JSON con el resultado de la búsqueda por cuit
    	$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(array('cuit' => $cuit));

		// armamos el JSON para mantener el formato que requiere un Datatable
		$empresas = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => $result,
		);

		// usamos el serializer para armar un string que contenga el JSON recursivo, es decir que se vean todos los niveles de profundidad del arbol. Si no hacemos esto solo mandariamos el nivel 0.
		
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($empresas, 'json');
		
		//Mandamos la respuesta.

		return new Response(
			$jsonResponse
		);
	} 
}