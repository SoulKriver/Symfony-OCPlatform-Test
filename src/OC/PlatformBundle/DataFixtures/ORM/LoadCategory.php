<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Category;

class LoadCategory extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$names = array(
			'Développement web',
			'Développement mobile',
			'Graphisme',
			'Intégration',
			'Réseau'
		);

		foreach ($names as $key => $name) {
			$category = new Category();
			$category->setName($name);

			$manager->persist($category);
		}

		$manager->flush();
	}
	public function getOrder(){
		return 2;
	}
}