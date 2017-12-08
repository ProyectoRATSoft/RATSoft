<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\SerializerBuilder;

use BackendBundle\Entity\TblVentas;

class InformesController extends Controller
{
	public function ventasAction()
	{
		return $this->render('FrontendBundle:Informes:ventas.html.twig');
	}

	public function ventasPorPeriodoAction($id, Request $Request)
	{
		$jsonResponse = $this->consultaDB($id);
		return $this->render(
			'FrontendBundle:Informes:ventas-por-periodo.html.twig',
					array(
						"ventas" => $jsonResponse
					));
	}

	public function ventasPorJurisdiccionAction($id, Request $Request)
	{
		$jsonResponse = $this->consultaDB($id);
		return $this->render(
			'FrontendBundle:Informes:ventas-por-jurisdiccion.html.twig',
					array(
						"ventas" => $jsonResponse
					));
	}

	public function ventasPorClienteAction($id, Request $Request)
	{
		$jsonResponse = $this->consultaDB($id);
		return $this->render(
			'FrontendBundle:Informes:ventas-por-cliente.html.twig',
					array(
						"ventas" => $jsonResponse
					));
	}

	public function ventasPorComprobanteAction($id, Request $Request)
	{
		$jsonResponse = $this->consultaDB($id);
		return $this->render(
			'FrontendBundle:Informes:ventas-por-comprobante.html.twig',
					array(
						"ventas" => $jsonResponse
					));
	}

	private function consultaDB($id) {
		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository("BackendBundle:TblVentas")->findBy(
			array(
				'empresa' => $id,
				'activo' => "1"
			));

		$ventas = array(
			'respuesta' => 'OK',
			'ventas' => $result
		);

		$serializer = SerializerBuilder::create()->build();
		$jsonResponse = $serializer->serialize($ventas, 'json');

		return $jsonResponse;
	}
}
