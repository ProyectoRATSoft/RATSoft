<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblCompras;
use BackendBundle\Entity\TblComprobantes;
use BackendBundle\Entity\TblEmpresas;
use BackendBundle\Entity\TblImputaciones;
use BackendBundle\Entity\TblProveedores;
use BackendBundle\Entity\TblTiposComp;
use BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class ComprasController extends Controller
{
	/**
	*	@Route("/contable/compras/{id}",name="compras_all")
	*	@Method({"GET"})
	*/

	public function allAction($id,Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$compras = array(
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));
		$compras["data"] = $result;
		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($compras, 'json');
		
		
		return $this->render(
			'FrontendBundle:Compras:compras-home.html.twig', 
        	array(
        		"respuesta" => $jsonResponse,
        		"empresa" => $id,
        	));
		//return new Response($compras);		
		//return new Response($id);
	}

	/**
	*	@Route("/contable/compras/{id}/new",name="compras_new")
	*	@Method({"GET"})
	*/

	public function newAction($id,Request $request){

		$respuesta = array (
			'fecha_ingreso' => new \DateTime("now"),
			'periodo_mes' => $request->request->get("periodo_mes"),
			'periodo_ano' => $request->request->get("periodo_ano"),
			'fecha' => new \DateTime($request->request->get("fecha")),
			'nro_comprobante' => $request->request->get("nro_comprobante"),
			'cai' => $request->request->get("cai"),
			'neto_105' => $request->request->get("neto_105"),
			'neto_21' => $request->request->get("neto_21"),
			'neto_27' => $request->request->get("neto_27"),
			'iva_105' => $request->request->get("iva_105"),
			'iva_21' => $request->request->get("iva_21"),
			'iva_27' => $request->request->get("iva_27"),
			'nogravado' => $request->request->get("nogravado"),
			'exento' => $request->request->get("exento"),
			'perc_iva' => $request->request->get("perc_iva"),
			'perc_iibb' => $request->request->get("perc_iibb"),
			'ret_ganancia' => $request->request->get("ret_ganancia"),
			'total' => $request->request->get("total"),
			'cod_comprobante' => $request->request->get("cod_comprobante"),
			'empresa' => $id,
			'imputacion' => $request->request->get("imputacion"),
			'proveedor' => $request->request->get("proveedor"),
			'tipo_comprobante' => $request->request->get("tipo_comprobante"),
			//'usuario' => $request->request->get("usuario"),
			'usuario' => '1',
		);

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( 
				    !$respuesta["periodo_mes"]
				|| 	!$respuesta["periodo_ano"]
				||  !$respuesta["fecha"]
				||  !$respuesta["cai"]
				||  !$respuesta["cod_comprobante"]
				||  !$respuesta["tipo_comprobante"]
				||  !$respuesta["nro_comprobante"]
				||  !$respuesta["proveedor"]
				||	!$respuesta["imputacion"]
				||  !is_numeric($respuesta["neto_105"])	
				||  !is_numeric($respuesta["neto_21"])
				||  !is_numeric($respuesta["neto_27"])	
				||  !is_numeric($respuesta["exento"])
				||  !is_numeric($respuesta["nogravado"])
				||  !is_numeric($respuesta["iva_105"])
				||  !is_numeric($respuesta["iva_21"])
				||  !is_numeric($respuesta["iva_27"]) 	
				||  !is_numeric($respuesta["ret_ganancia"])
				||  !is_numeric($respuesta["perc_iva"])
				||  !is_numeric($respuesta["perc_iibb"])	
				||  !is_numeric($respuesta["total"])
			) {
				$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
					array(
						'empresa' => $id,
						'activo' => "1"
					));
				$data = array(
					'status' => 'ERROR',
					'msg' => 'Hubo campos mandatorios que se enviaron vacios',
					'draw' => '',
					'recordsTotal' => '',
					'recordsFiltered' => '',
					'data' =>  $result,				
			);
					
				$jsonResponse = $serializer->serialize($data, 'json');
				return new Response($jsonResponse);
				exit();
		}

		$em = $this->getDoctrine()->getManager();


	    $qb = $em->createQueryBuilder();
	    $qb->select('c')
	       ->from('BackendBundle:TblCompras', 'c')
	       ->where('c.proveedor = :proveedor AND c.tipoComprobante = :tcomp AND c.nroComprobante = :ncomp')
	       ->setParameters(
	       		array(
	       			'proveedor' => $respuesta["proveedor"], 
	       			'tcomp' => $respuesta["tipo_comprobante"], 
	       			'ncomp' => $respuesta["nro_comprobante"]
	       		)
	       	);
	    
	    $query = $qb->getQuery();
		$validacion = new TblCompras();	    
	    $validacion = $query->getResult();
		
	    
	    if (!empty($validacion)) {
      		$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
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

		$imputacion = new TblImputaciones();
		$imputacion = $em->getRepository("BackendBundle:TblImputaciones")->findOneBy(
			array(
				'id' => $respuesta["imputacion"]
			)
		);

		$proveedores = new TblProveedores();
		$proveedores = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
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

    	
    	$compras = new TblCompras();     	
    	$compras->setFechaIngreso($respuesta["fecha_ingreso"]);
    	$compras->setPeriodoMes($respuesta["periodo_mes"]);
    	$compras->setPeriodoAno($respuesta["periodo_ano"]);
    	$compras->setFecha($respuesta["fecha"]);
    	$compras->setNroComprobante($respuesta["nro_comprobante"]);
    	$compras->setCai($respuesta["cai"]);
    	$compras->setNeto105($respuesta["neto_105"]);
    	$compras->setNeto21($respuesta["neto_21"]);
    	$compras->setNeto27($respuesta["neto_27"]);
    	$compras->setIva105($respuesta["iva_105"]);
    	$compras->setIva21($respuesta["iva_21"]);
    	$compras->setIva27($respuesta["iva_27"]);
    	$compras->setNogravado($respuesta["nogravado"]);
    	$compras->setExento($respuesta["exento"]);
    	$compras->setPercIva($respuesta["perc_iva"]);
    	$compras->setPercIibb($respuesta["perc_iibb"]);
    	$compras->setRetGanancia($respuesta["ret_ganancia"]);
    	$compras->setTotal($respuesta["total"]);
    	$compras->setCodComprobante($comprobantes);
    	$compras->setEmpresa($empresas);
    	$compras->setImputacion($imputacion);
    	$compras->setProveedor($proveedores);
    	$compras->setTipoComprobante($tipo_comprobantes);
    	$compras->setUsuario($usuario);
    	$compras->setActivo("1");

    	$em->persist($compras);
		$em->flush();

		$compras = array(
			'status'=> 'OK',
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'msg' => 'compra agregada con éxito',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));
		$compras["data"] = $result;


		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($compras, 'json');
		//return new Response($jsonResponse);		
		return new Response($jsonResponse);
	}

	/**
	*	@Route("/contable/compras/{id}/update",name="compras_update")
	*	@Method({"GET"})
	*/

	public function updateAction($id,Request $request){

		$respuesta = array (
			'id' => $request->request->get("id_compras"),
			'fecha_ingreso' => new \DateTime("now"),
			'periodo_mes' => $request->request->get("periodo_mes"),
			'periodo_ano' => $request->request->get("periodo_ano"),
			'fecha' => new \DateTime($request->request->get("fecha")),
			'nro_comprobante' => $request->request->get("nro_comprobante"),
			'cai' => $request->request->get("cai"),
			'neto_105' => $request->request->get("neto_105"),
			'neto_21' => $request->request->get("neto_21"),
			'neto_27' => $request->request->get("neto_27"),
			'iva_105' => $request->request->get("iva_105"),
			'iva_21' => $request->request->get("iva_21"),
			'iva_27' => $request->request->get("iva_27"),
			'nogravado' => $request->request->get("nogravado"),
			'exento' => $request->request->get("exento"),
			'perc_iva' => $request->request->get("perc_iva"),
			'perc_iibb' => $request->request->get("perc_iibb"),
			'ret_ganancia' => $request->request->get("ret_ganancia"),
			'total' => $request->request->get("total"),
			'cod_comprobante' => $request->request->get("cod_comprobante"),
			'empresa' => $id,
			'imputacion' => $request->request->get("imputacion"),
			'proveedor' => $request->request->get("proveedor"),
			'tipo_comprobante' => $request->request->get("tipo_comprobante"),
			//'usuario' => $request->request->get("usuario"),
			'usuario' => '1',
		);

		// Me aseguro que no me hayan mandado ningun campo vacío

		if ( 
				    !$respuesta["periodo_mes"]
				|| 	!$respuesta["periodo_ano"]
				|| 	!$respuesta["id"]
				||  !$respuesta["fecha"]
				||  !$respuesta["cai"]
				||  !$respuesta["cod_comprobante"]
				||  !$respuesta["tipo_comprobante"]
				||  !$respuesta["nro_comprobante"]
				||  !$respuesta["proveedor"]
				||	!$respuesta["imputacion"]
				||  !is_numeric($respuesta["neto_105"])	
				||  !is_numeric($respuesta["neto_21"])
				||  !is_numeric($respuesta["neto_27"])	
				||  !is_numeric($respuesta["exento"])
				||  !is_numeric($respuesta["nogravado"])
				||  !is_numeric($respuesta["iva_105"])
				||  !is_numeric($respuesta["iva_21"])
				||  !is_numeric($respuesta["iva_27"]) 	
				||  !is_numeric($respuesta["ret_ganancia"])
				||  !is_numeric($respuesta["perc_iva"])
				||  !is_numeric($respuesta["perc_iibb"])	
				||  !is_numeric($respuesta["total"])
			) {
			$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
				array(
					'empresa' => $id,
					'activo' => "1"
				));
			$data = array(
				'status' => 'ERROR',
				'msg' => 'Hubo campos mandatorios que se enviaron vacios',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' =>  $data,				
				);
				
			$jsonResponse = $serializer->serialize($data, 'json');
			return new Response($jsonResponse);
			exit();
		}

		$em = $this->getDoctrine()->getManager();


	    $qb = $em->createQueryBuilder();
	    $qb->select('c')
	       ->from('BackendBundle:TblCompras', 'c')
	       ->where('c.proveedor = :proveedor AND c.tipoComprobante = :tcomp AND c.nroComprobante = :ncomp AND c.id <> :id')
	       ->setParameters(
	       		array(
	       			'proveedor' => $respuesta["proveedor"], 
	       			'tcomp' => $respuesta["tipo_comprobante"], 
	       			'ncomp' => $respuesta["nro_comprobante"],
	       			'id' => $respuesta["id"]
	       		)
	       	);
	    
	    $query = $qb->getQuery();
		$validacion = new TblCompras();	    
	    $validacion = $query->getResult();
		
	    
	    if (!empty($validacion)) {
      		$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
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

		$imputacion = new TblImputaciones();
		$imputacion = $em->getRepository("BackendBundle:TblImputaciones")->findOneBy(
			array(
				'id' => $respuesta["imputacion"]
			)
		);

		$proveedores = new TblProveedores();
		$proveedores = $em->getRepository("BackendBundle:TblProveedores")->findOneBy(
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

		// Instanciamos un objeto Compras y seteamos sus datos.
		
		$compras = $em->getRepository("BackendBundle:TblCompras")->findOneBy(
			array(
				'id' => $respuesta["id"]
			)
		);
    	
    	//$compras = new TblCompras();     	
    	$compras->setFechaIngreso($respuesta["fecha_ingreso"]);
    	$compras->setPeriodoMes($respuesta["periodo_mes"]);
    	$compras->setPeriodoAno($respuesta["periodo_ano"]);
    	$compras->setFecha($respuesta["fecha"]);
    	$compras->setNroComprobante($respuesta["nro_comprobante"]);
    	$compras->setCai($respuesta["cai"]);
    	$compras->setNeto105($respuesta["neto_105"]);
    	$compras->setNeto21($respuesta["neto_21"]);
    	$compras->setNeto27($respuesta["neto_27"]);
    	$compras->setIva105($respuesta["iva_105"]);
    	$compras->setIva21($respuesta["iva_21"]);
    	$compras->setIva27($respuesta["iva_27"]);
    	$compras->setNogravado($respuesta["nogravado"]);
    	$compras->setExento($respuesta["exento"]);
    	$compras->setPercIva($respuesta["perc_iva"]);
    	$compras->setPercIibb($respuesta["perc_iibb"]);
    	$compras->setRetGanancia($respuesta["ret_ganancia"]);
    	$compras->setTotal($respuesta["total"]);
    	$compras->setCodComprobante($comprobantes);
    	$compras->setEmpresa($empresas);
    	$compras->setImputacion($imputacion);
    	$compras->setProveedor($proveedores);
    	$compras->setTipoComprobante($tipo_comprobantes);
    	$compras->setUsuario($usuario);
    	$compras->setActivo("1");

    	$em->persist($compras);
		$em->flush();

		$compras = array(
			'status'=> 'OK',
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'msg' => 'compra editada con éxito',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));
		$compras["data"] = $result;


		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($compras, 'json');
		//return new Response($jsonResponse);		
		return new Response($jsonResponse);
	}

	/**
	*	@Route("/contable/compras/{id}/del",name="compras_del")
	*	@Method({"post"})
	*/
	
	public function delAction($id, Request $request){

		$respuesta = array (
			'id' => $request->request->get("id_compras"),
		);
		$em = $this->getDoctrine()->getManager();
		$compras = $em->getRepository("BackendBundle:TblCompras")->findOneBy(
			array(
				'id' => $respuesta["id"],
			)
		);
		$compras->setActivo("0");
		$em->persist($compras);
		$em->flush();

		$compras = array(
				'status' => 'OK',
				'msg' => 'La compra ha sido Eliminada con exito',
				'draw' => '',
				'recordsTotal' => '',
				'recordsFiltered' => '',
				'data' => '',
			);
		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblCompras")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));
		$compras["data"] = $result;


		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($compras, 'json');
		//return new Response($jsonResponse);		
		return new Response($jsonResponse);

	}


}