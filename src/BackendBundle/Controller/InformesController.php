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

  public function chartVentasJurisdiccionAction(Request $request){
    //------------------------------------------------------------------------------------------------
    $em = $this->getDoctrine()->getManager();
    // select COUNT(v.id) AS ventas,j.nombre from tbl_ventas v 
    //   INNER JOIN tbl_empresas e on e.id = v.empresa
    //     INNER JOIN tbl_jurisdicciones j on j.id = e.provincia
    //     GROUP BY j.nombre

    $query = $em->createQuery('select COUNT(v.id) AS value, j.nombre AS label from BackendBundle:TblVentas v 
        INNER JOIN BackendBundle:TblEmpresas e with e.id = v.empresa
        INNER JOIN BackendBundle:TblJurisdicciones j with j.id = e.provincia
        GROUP BY j.nombre');

    $result = $query->getResult();
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

  public function chartComprasJurisdiccionAction(Request $request){
    //------------------------------------------------------------------------------------------------
    $em = $this->getDoctrine()->getManager();
    // select COUNT(v.id) AS ventas,j.nombre from tbl_compras v 
    //   INNER JOIN tbl_empresas e on e.id = v.empresa
    //     INNER JOIN tbl_jurisdicciones j on j.id = e.provincia
    //     GROUP BY j.nombre

    $query = $em->createQuery('select COUNT(v.id) AS value, j.nombre AS label from BackendBundle:TblCompras v 
        INNER JOIN BackendBundle:TblEmpresas e with e.id = v.empresa
        INNER JOIN BackendBundle:TblJurisdicciones j with j.id = e.provincia
        GROUP BY j.nombre');

    $result = $query->getResult();
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

}
