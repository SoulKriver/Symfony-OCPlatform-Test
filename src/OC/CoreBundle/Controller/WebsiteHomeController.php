<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebsiteHomeController extends Controller
{
	public function indexAction()
	{
		return $this->render('OCCoreBundle:WebsiteHome:index.html.twig');
	}

	public function contactAction(Request $request)
	{
		$session = $request->getSession();

		$session->getFlashBag()->add('contactflashmessage', "Message Flash : La page de contact n'est pas encore disponible, merci de revenir plus tard !  Appuyer sur F5 pour me faire disparaître ! "); // le message Flash de la consigne

		return $this->redirectToRoute('oc_core_homepage'); // on redirige vers la route de la page d'accueil
	}

}
