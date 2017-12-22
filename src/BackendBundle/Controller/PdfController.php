<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\TblSituacionIva;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PdfController extends Controller
{
	/**
	*	@Route("/pdf",name="pdf_base")
	*	@Method({"GET"})
	*/

	public function allAction(Request $request){
		$html = $request->request->get("html");
    	$snappy = $this->get("knp_snappy.pdf");
    	$filename = "archivoPdf";
    	
    	//$webSiteUrl = "http://127.0.0.1:8000/contable";
    	//$snappy->generateFromHtml($this->renderView('FrontendBundle:Compras:compras-home.html.twig',array(
    	//		"title" => "Awesome PDF Title"
    	//		)
    	//	),
    	//	'archivo.pdf'
    	//);

    	return new Response(
    		//$snappy->getOutput($webSiteUrl),
    		$snappy->getOutputFromHtml($html),
    		200,
    		array(
    			'Content-Type' => 'application/pdf',
    			'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
    		)
    	);
		
	}
	
}
?>