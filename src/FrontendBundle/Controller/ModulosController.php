<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use BackendBundle\Entity\TblEmpresas;



class ModulosController extends Controller
{
    public function contableAction()
    {
        return $this->render('FrontendBundle:Empresas:contable-home.html.twig');
    }

    public function impositivoAction()
    {
        return $this->render('FrontendBundle:Empresas:impositivo-home.html.twig');
    }

    public function razonsocialAction()
    {
        return $this->render('FrontendBundle:Empresas:razones_sociales-home.html.twig');
    }

    public function jurisdiccionAction()
    {
        return $this->render('FrontendBundle:Jurisdicciones:jurisdicciones-home.html.twig');
    }

    public function comprobantesAction()
    {
        return $this->render('FrontendBundle:Comprobantes:comprobantes-home.html.twig');
    }

    /**
    *   @Route("/impositivo/iva/{id}",name="iva_byId")
    *   @Method({"GET"})
    */

    public function ivaAction($id,Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
            array(
                'id' => $id,
            ));
        $serializer = SerializerBuilder::create()->build();
        $jsonResponse = $serializer->serialize($result, 'json');
        return $this->render('FrontendBundle:Iva:iva-home.html.twig',
            array(
                "empresa" => $jsonResponse,
            ));
    }

    // public function iibbAction($id,Request $request)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $result = $em->getRepository("BackendBundle:TblEmpresas")->findOneBy(
    //         array(
    //             'id' => $id,
    //         ));
    //     $serializer = SerializerBuilder::create()->build();
    //     $jsonResponse = $serializer->serialize($result, 'json');
    //     return $this->render('FrontendBundle:Iibb:iibb-home.html.twig',
    //         array(
    //             "empresa" => $jsonResponse,
    //         ));
    // }

     public function iibbAction()
    {
        return $this->render('FrontendBundle:Iibb:iibb-home.html.twig');
    }

    public function userAction()
    {
    	return $this->render('FrontendBundle:Users:register.html.twig');
    }

    public function imputacionesAction()
    {
        return $this->render('FrontendBundle:Imputaciones:imputaciones-home.html.twig');
    }

}
