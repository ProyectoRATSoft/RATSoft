<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblVentas;
use BackendBundle\Entity\TblComprobantes;
use BackendBundle\Entity\TblEmpresas;
use BackendBundle\Entity\TblRubros;
use BackendBundle\Entity\TblProveedores;
use BackendBundle\Entity\TblTiposComp;
use BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class VentasController extends Controller
{
	/**
	*	@Route("/contable/ventas/{id}",name="ventas_all")
	*	@Method({"GET"})
	*/
	public function allAction($id,Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$ventas = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));
		$ventas["data"] = $result;
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($ventas, 'json');
		
		
		return $this->render(
			'FrontendBundle:Ventas:ventas-home.html.twig', 
        	array(
        		"respuesta" => $jsonResponse,
        		"empresa" => $id,
        ));
        // return new Response($ventas);		
		//return new Response($id);
	}
	/**
	*	@Route("/contable/ventas/{id}/new",name="ventas_new")
	*	@Method({"GET"})
	*/
	public function newAction($id,Request $request){

		$respuesta = array (
			
			'periodo_mes' => $request->request->get("periodo_mes"),
			'periodo_ano' => $request->request->get("periodo_ano"),
			'fecha' => new \DateTime($request->request->get("fecha")),
			'cod_comprobante' => $request->request->get("cod_comprobante"),
			'tipo_comprobante' => $request->request->get("tipo_comprobante"),
			'nro_comprobante' => $request->request->get("nro_comprobante"),
			'proveedor' => $request->request->get("proveedor"),
			'neto_105' => $request->request->get("neto_105"),
			'neto_21' => $request->request->get("neto_21"),
			'neto_exento' => $request->request->get("neto_exento"),
			'nogravado' => $request->request->get("nogravado"),
			'iva_105' => $request->request->get("iva_105"),
			'iva_21' => $request->request->get("iva_21"),
			'ret_gan' => $request->request->get("ret_gan"),
			'retencion' => $request->request->get("retencion"),
			'percepcion' => $request->request->get("percepcion"),
			'total' => $request->request->get("total"),						
			'empresa' => $id,
			'fecha_ingreso' => new \DateTime("now"),
			//'usuario' => $request->request->get("usuario"),
			'usuario' => '1',
			// 'rubro' => $request->request->get("rubro"),
		);

		$ventas = array(
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
				    !$respuesta["periodo_mes"]
				|| 	!$respuesta["periodo_ano"]
				||  !$respuesta["fecha"]	
				||  !$respuesta["cod_comprobante"]
				||  !$respuesta["tipo_comprobante"]
				||  !$respuesta["nro_comprobante"]
				||  !$respuesta["proveedor"]
				||  !is_numeric($respuesta["neto_105"])	
				||  !is_numeric($respuesta["neto_21"])	
				||  !is_numeric($respuesta["neto_exento"])
				||  !is_numeric($respuesta["nogravado"])
				||  !is_numeric($respuesta["iva_105"])
				||  !is_numeric($respuesta["iva_21"]) 	
				||  !is_numeric($respuesta["ret_gan"])	
				||  !is_numeric($respuesta["retencion"])
				||  !is_numeric($respuesta["percepcion"])
				||  !is_numeric($respuesta["total"])
			) {
				$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
						array(
							'empresa' => $id,
							'activo' => "1"
						));
				$ventas = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' =>  $result,				
			);
				
				$jsonResponse = $serializer->serialize($ventas, 'json');
				return new Response($jsonResponse);
				exit();
		}

		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();
	    $qb->select('v')
	       ->from('BackendBundle:TblVentas', 'v')
	       ->where('v.empresa = :empresa AND v.tipoComprobante = :tcomp AND v.nroComprobante = :ncomp')
	       ->setParameters(
	       		array(
	       			'empresa' => $id, 
	       			'tcomp' => $respuesta["tipo_comprobante"], 
	       			'ncomp' => $respuesta["nro_comprobante"]
	       		)
	       	);
	    
	    $query = $qb->getQuery();
		$validacion = new TblVentas();	    
	    $validacion = $query->getResult();
		
	    
	    if (!empty($validacion)) {
      		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));

      		$data = array(
					'status' => 'ERROR',
					'msg' => 'Este comprobante esta duplicado',
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

		$comprobantes = new TblComprobantes();
		$comprobantes = $em->getRepository("BackendBundle:TblComprobantes")->findOneBy(
			array(
				'id' => $respuesta["cod_comprobante"]
			)
		);

		$empresas = new TblEmpresas();
		$empresas = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
			array(
				'id' => $respuesta["empresa"]
			)
		);

		// $rubro = new TblRubros();
		// $rubro = $em->getRepository("BackendBundle:TblRubros")->findOneBy(
		// 	array(
		// 		'id' => $respuesta["rubro"]
		// 	)
		// );

		$clientes = new TblProveedores();
		$clientes = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
			array(
				'id' => $respuesta["proveedor"]
			)
		);

		$tipo_comprobantes = new TblTiposComp();
		$tipo_comprobantes = $em->getRepository("BackendBundle:TblTiposComp")->findOneBy(
			array(
				'id' => $respuesta["tipo_comprobante"]
			)
		);

		$usuario = new User();
		$usuario = $em->getRepository("BackendBundle:User")->findOneBy(
			array(
				'id' => $respuesta["usuario"]
			)
		);

    	
    	$ventas = new TblVentas();     	
    	$ventas->setFechaIngreso($respuesta["fecha_ingreso"]);
    	$ventas->setPeriodoMes($respuesta["periodo_mes"]);
    	$ventas->setPeriodoAno($respuesta["periodo_ano"]);
    	$ventas->setFecha($respuesta["fecha"]);
    	$ventas->setNroComprobante($respuesta["nro_comprobante"]);    	
    	$ventas->setNeto105($respuesta["neto_105"]);
    	$ventas->setNeto21($respuesta["neto_21"]);
    	$ventas->setIva105($respuesta["iva_105"]);
    	$ventas->setIva21($respuesta["iva_21"]);
    	$ventas->setNetoExento($respuesta["neto_exento"]);
    	$ventas->setNogravado($respuesta["nogravado"]);
    	$ventas->setRetGan($respuesta["ret_gan"]);
    	$ventas->setRetencion($respuesta["retencion"]);
    	$ventas->setPercepcion($respuesta["percepcion"]);
    	$ventas->setTotal($respuesta["total"]);
    	$ventas->setCodComprobante($comprobantes);
    	$ventas->setEmpresa($empresas);
    	// $ventas->setImputacion($rubro);
    	$ventas->setCliente($clientes);
    	$ventas->setTipoComprobante($tipo_comprobantes);
    	$ventas->setUsuario($usuario);
    	$ventas->setActivo("1");

    	$em->persist($ventas);
		$em->flush();

		$ventas = array(
			'status'=> 'OK',
			'msg' => 'Comprobante agregado con éxito',
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(array('empresa' => $id));
		$ventas["data"] = $result;

		$jsonResponse = $serializer->serialize($ventas, 'json');
		return new Response($jsonResponse);		
		//$response = new Response ();
		//$response->setContent($request);
		//$response->headers->set('Content-Type', 'application/json');
		//return $response;
	}
	/**
	*	@Route("/contable/ventas/{id}/update",name="ventas_update")
	*	@Method({"GET"})
	*/

	public function updateAction($id,Request $request){

		$respuesta = array (
			'id' => $request->request->get("id_ventas"),
			'periodo_mes' => $request->request->get("periodo_mes"),
			'periodo_ano' => $request->request->get("periodo_ano"),
			'fecha' => new \DateTime($request->request->get("fecha")),
			'cod_comprobante' => $request->request->get("cod_comprobante"),
			'tipo_comprobante' => $request->request->get("tipo_comprobante"),
			'nro_comprobante' => $request->request->get("nro_comprobante"),
			'proveedor' => $request->request->get("proveedor"),
			'neto_105' => $request->request->get("neto_105"),
			'neto_21' => $request->request->get("neto_21"),
			'neto_exento' => $request->request->get("neto_exento"),
			'nogravado' => $request->request->get("nogravado"),
			'iva_105' => $request->request->get("iva_105"),
			'iva_21' => $request->request->get("iva_21"),
			'ret_gan' => $request->request->get("ret_gan"),
			'retencion' => $request->request->get("retencion"),
			'percepcion' => $request->request->get("percepcion"),
			'total' => $request->request->get("total"),						
			'empresa' => $id,
			'fecha_ingreso' => new \DateTime("now"),
			//'usuario' => $request->request->get("usuario"),
			'usuario' => '1',
			// 'rubro' => $request->request->get("rubro"),
		);

		$serializer = SerializerBuilder::create()->build();

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( 
				    !$respuesta["periodo_mes"]
				|| 	!$respuesta["periodo_ano"]
				||  !$respuesta["fecha"]	
				||  !$respuesta["cod_comprobante"]
				||  !$respuesta["tipo_comprobante"]
				||  !$respuesta["nro_comprobante"]
				||  !$respuesta["proveedor"]
				||  !is_numeric($respuesta["neto_105"])	
				||  !is_numeric($respuesta["neto_21"])	
				||  !is_numeric($respuesta["neto_exento"])
				||  !is_numeric($respuesta["nogravado"])
				||  !is_numeric($respuesta["iva_105"])
				||  !is_numeric($respuesta["iva_21"]) 	
				||  !is_numeric($respuesta["ret_gan"])	
				||  !is_numeric($respuesta["retencion"])
				||  !is_numeric($respuesta["percepcion"])
				||  !is_numeric($respuesta["total"])
			) {
				$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
					array(
						'empresa' => $id,
						'activo' => "1"
					));
				$ventas = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' =>  $result,				
			);
				
				$jsonResponse = $serializer->serialize($ventas, 'json');
				return new Response($jsonResponse);
				exit();
		}

		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();
	    $qb->select('v')
	       ->from('BackendBundle:TblVentas', 'v')
	       ->where('v.empresa = :empresa AND v.tipoComprobante = :tcomp AND v.nroComprobante = :ncomp AND v.id <> :id')
	       ->setParameters(
	       		array(
	       			'empresa' => $id, 
	       			'tcomp' => $respuesta["tipo_comprobante"], 
	       			'ncomp' => $respuesta["nro_comprobante"],
	       			'id' => $respuesta["id"]
	       		)
	       	);
	    
	    $query = $qb->getQuery();
		$validacion = new TblVentas();	    
	    $validacion = $query->getResult();
		
	    
	    if (!empty($validacion)) {
      		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));

      		$data = array(
					'status' => 'ERROR',
					'msg' => 'Este comprobante esta duplicado',
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

		$comprobantes = new TblComprobantes();
		$comprobantes = $em->getRepository("BackendBundle:TblComprobantes")->findOneBy(
			array(
				'id' => $respuesta["cod_comprobante"]
			)
		);

		$empresas = new TblEmpresas();
		$empresas = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
			array(
				'id' => $respuesta["empresa"]
			)
		);

		// $rubro = new TblRubros();
		// $rubro = $em->getRepository("BackendBundle:TblRubros")->findOneBy(
		// 	array(
		// 		'id' => $respuesta["rubro"]
		// 	)
		// );

		$clientes = new TblProveedores();
		$clientes = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
			array(
				'id' => $respuesta["proveedor"]
			)
		);

		$tipo_comprobantes = new TblTiposComp();
		$tipo_comprobantes = $em->getRepository("BackendBundle:TblTiposComp")->findOneBy(
			array(
				'id' => $respuesta["tipo_comprobante"]
			)
		);

		$usuario = new User();
		$usuario = $em->getRepository("BackendBundle:User")->findOneBy(
			array(
				'id' => $respuesta["usuario"]
			)
		);

		$ventas = $em->getRepository("BackendBundle:TblVentas")->findOneBy(
			array(
				'id' => $respuesta["id"]
			)
		);

    	$ventas->setFechaIngreso($respuesta["fecha_ingreso"]);
    	$ventas->setPeriodoMes($respuesta["periodo_mes"]);
    	$ventas->setPeriodoAno($respuesta["periodo_ano"]);
    	$ventas->setFecha($respuesta["fecha"]);
    	$ventas->setNroComprobante($respuesta["nro_comprobante"]);    	
    	$ventas->setNeto105($respuesta["neto_105"]);
    	$ventas->setNeto21($respuesta["neto_21"]);
    	$ventas->setIva105($respuesta["iva_105"]);
    	$ventas->setIva21($respuesta["iva_21"]);
    	$ventas->setNetoExento($respuesta["neto_exento"]);
    	$ventas->setNogravado($respuesta["nogravado"]);
    	$ventas->setRetGan($respuesta["ret_gan"]);
    	$ventas->setRetencion($respuesta["retencion"]);
    	$ventas->setPercepcion($respuesta["percepcion"]);
    	$ventas->setTotal($respuesta["total"]);
    	$ventas->setCodComprobante($comprobantes);
    	$ventas->setEmpresa($empresas);
    	// $ventas->setImputacion($rubro);
    	$ventas->setCliente($clientes);
    	$ventas->setTipoComprobante($tipo_comprobantes);
    	$ventas->setUsuario($usuario);
    	$ventas->setActivo("1");

    	$em->persist($ventas);
		$em->flush();

		$ventas = array(
			'status'=> 'OK',
			'msg' => 'Comprobante editado con éxito',
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(array('empresa' => $id));
		$ventas["data"] = $result;
		
		$jsonResponse = $serializer->serialize($ventas, 'json');
		return new Response($jsonResponse);		
		//$response = new Response ();
		//$response->setContent($request);
		//$response->headers->set('Content-Type', 'application/json');
		//return $response;
	}

	/**
	*	@Route("/contable/ventas/{id}/del",name="ventas_del")
	*	@Method({"post"})
	*/
	
	public function delAction($id, Request $request){

		$respuesta = array (
			'id' => $request->request->get("id_ventas"),
		);
		$em = $this->getDoctrine()->getManager();
		$ventas = $em->getRepository("BackendBundle:TblVentas")->findOneBy(
			array(
				'id' => $respuesta["id"],
			)
		);
		$ventas->setActivo("0");
		$em->persist($ventas);
		$em->flush();

		$ventas = array(
				'status' => 'OK',
				'msg' => 'La compra ha sido Eliminada con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));
		$ventas["data"] = $result;


		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($ventas, 'json');
		//return new Response($jsonResponse);		
		return new Response($jsonResponse);

	}


}