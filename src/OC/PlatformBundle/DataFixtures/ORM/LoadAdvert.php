<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Skill;
use OC\PlatformBundle\Entity\AdvertCategory;
use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\DataFixtures\ORM\LoadSkill;

class LoadAdvert extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$titles = array('Chef développement Symfony',' Expert design Photoshop','Offre de stage webdesigner');
		$authors = array('Marie',' Paul','Jacques');
		$authorsapp = array('Pierre', 'Marine');
		$contents = array("Nous recherchons chef développeur Symfony pour la région parisienne. Blablabla","Nous recherchons un expert design Photoshop pour Lyon. Blablabla","Nous recherchons un web designer pour un stage débutant sur Montpellier. Blablabla");
		$emails = array("marie@gmail.com","paul@gmail.com","jacques@gmail.com");
		

		for ($i=0; $i < 3; $i++) { 
			$advert = new Advert();
			$advert->setTitle($titles[$i]);
			$advert->setAuthor($authors[$i]);
			$advert->setContent($contents[$i]);
			$advert->setEmail($emails[$i]);

			

			$application1 = new Application();
			$application1->setAuthor($authorsapp[0]);
			$application1->setContent("J'ai toutes les qualités requises.");
			$application1->setEmail("pierre@gmail.com");

			$application2 = new Application();
			$application2->setAuthor($authorsapp[1]);
			$application2->setContent("Je suis très motivé.");
			$application2->setEmail("marine@gmail.com");

			$application1->setAdvert($advert);
			$application2->setAdvert($advert);

			$listSkills = $manager->getRepository('OCPlatformBundle:Skill')->findAll();
			//print_r($listSkills);

			$listCategories = $manager->getRepository('OCPlatformBundle:Category')->findAll();


			foreach ($listSkills as $skill) {
				$advertSkill = new AdvertSkill();
				$advert->addAdvertSkill($advertSkill);
				$advertSkill->setSkill($skill);
				$advertSkill->setLevel('Expert');
				$manager->persist($advertSkill);

			}

			$advert->addCategory($listCategories[$i]);
			




			$image = new Image();
			$image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
			$image->setAlt('Job de rêve');

			$advert->setImage($image);



			$manager->persist($advert);

			$manager->persist($application1);
			$manager->persist($application2);

			$manager->persist($image);
			
			$manager->flush();

		}
	}
	public function getOrder(){
		return 3;
	}
}


