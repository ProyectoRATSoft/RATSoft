<?php

namespace BackendBundle\Controller;

// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

use BackendBundle\Entity\TblVentas;

class InformesController extends Controller
{

  // Devuelve todas las compras. La lógica de búsqueda la controla Front.
  public function ventasAction($id, Request $request) {
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
    return new Response($jsonResponse);
  }



}
