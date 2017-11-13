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

		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(array('empresa' => $id));
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
			'draw' => '',
			'recordsTotal' => '',
			'recordsFiltered' => '',
			'data' => '',
		);

		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(array('empresa' => $id));
		$ventas["data"] = $result;


		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($ventas, 'json');
		//return new Response($jsonResponse);		
		return new Response($jsonResponse);
	}
}