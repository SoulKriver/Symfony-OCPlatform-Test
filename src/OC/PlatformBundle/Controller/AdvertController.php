<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Skill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
	public function testAction(){

	}

	public function indexAction($page)
	{
		if ($page <1) {
			throw new NotFoundHttpException('Page"'.$page.'" inexistante.');
		}
		$nbPerPage = 2;


		$listAdverts = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert')->getAdverts2($page, $nbPerPage);

		$nbPages = ceil(count($listAdverts) / $nbPerPage);

		if($page > $nbPages) { throw $this->createNotFoundException("La page ".$page. " n'existe pas.");}
		

		return $this->render('OCPlatformBundle:Advert:index.html.twig', array ('listAdverts' =>$listAdverts, 'nbPages' => $nbPages, 'page' => $page, ));
	}

	public function viewAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if(null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id".$id." n'existe pas.");
		}

		$listApplications = $em->getRepository('OCPlatformBundle:Application')->findBy(array('advert'=> $advert));

		$listAdvertSkills = $em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert'=> $advert));

		return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=> $advert, 'listApplications' =>$listApplications, 'listAdvertSkills' => $listAdvertSkills));
	}

	public function addAction(Request $request)
	{





		$advert = new Advert();
		$advert->setTitle('Offre de stage webdesigner');
		$advert->setAuthor('Giselle');
		$advert->setEmail('soulofkriver@live.fr');
		$advert->setContent("Nous recherchons un web designer pour un stage débutant sur Montpellier. Blablabla");

		$application1 = new Application();
		$application1->setAuthor('Marine');
		$application1->setContent("J'ai toutes les qualités reqiuses.");
		$application1->setEmail("soulofkriver@live.fr");

		$application2 = new Application();
		$application2->setAuthor('Pierre');
		$application2->setContent("Je suis très motivé.");
		$application2->setEmail("soulofkriver@live.fr");

		$application1->setAdvert($advert);
		$application2->setAdvert($advert);

		$em = $this->getDoctrine()->getManager();
		$listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
		
		foreach ($listSkills as $skill) {
			$advertSkill = new AdvertSkill();
			$advertSkill->setAdvert($advert);
			$advertSkill->setSkill($skill);
			$advertSkill->setLevel('Expert');
			$em->persist($advertSkill);
		}
		$em->persist($advert);

		$em->persist($application1);
		$em->persist($application2);

		$em->flush();


		$image = new Image();
		$image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
		$image->setAlt('Job de rêve');

		$advert->setImage($image);

		$em = $this->getDoctrine()->getManager();

		$em->persist($advert);

		$em->flush();

		if ($request->isMethod('POST'))
		{
			$request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');

			return $this->redirectToRoute('oc_platform_view',array('id' => $advert->getId()));
		}

		return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert'=> $advert));
	}

	public function editAction($id, Request $request)
	{
		

		$em = $this->getDoctrine()->getManager();

		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
		}

		$advert->setPublished('2');
		$em->flush();

		if ($request->isMethod('POST'))
		{
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée. ');
			return $this->redirectToRoute('oc_platform_view', array('id' => 5));
		}
		
		
		return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
			'advert' => $advert
		));
	}

	public function deleteWarningAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if (null === $advert){
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}
		
		return $this->render('OCPlatformBundle:Advert:deletewarning.html.twig', array('advert' => $advert));
	}

	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if (null === $advert){
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

		foreach ($advert->getCategories() as $category) {
			$advert->removeCategory($category);
		}
		$advert->setPublished(0);
		$em->flush();

		return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert' => $advert));
	}



	public function menuAction($limit)
	{
		$em = $this->getDoctrine()->getManager();
		$listAdverts = $em->getRepository('OCPlatformBundle:Advert')->getAdvertLastOnes(5);

		return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
			'listAdverts' => $listAdverts));
	}

	public function editImageAction($advertId)
	{
		$em = $this->getDoctrine()->getManager();
		$advert=$em->getRepository('OCPlatformBundle:Advert')->find($advertId);

		$advert->getImage()->setUrl('test.png');

		$em->flush();

		return new Response('OK');
	}

}