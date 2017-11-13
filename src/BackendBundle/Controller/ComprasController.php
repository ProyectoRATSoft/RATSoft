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

		$em = $this->getDoctrine()->getManager();

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

		$em = $this->getDoctrine()->getManager();

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