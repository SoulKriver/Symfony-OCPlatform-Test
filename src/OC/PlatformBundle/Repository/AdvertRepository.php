<?php

namespace OC\PlatformBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
	public function getAdvertWithCategories(array $categoryNames){

		$qb = $this->createQueryBuilder('a');

		$qb->innerJoin('a.categories','c')->addSelect('c');

		$qb-> where($qb->expr()->in('c.name',$categoryNames));

		return $qb->getQuery()->getResult();
	}


	public function getAdvertWithSkills(array $skillNames)
	{
		$qb = $this->createQueryBuilder('a');
		$qb->innerJoin('a.categories','s')->addSelect('s');
		$qb-> where($qb->expr()->in('s.name',$skillNames));

		return $qb->getQuery()->getResult();
	}

	public function getAdvertLastOnes($nb){
		$qb = $this->createQueryBuilder('a')->where('a.published=1');
		$qb->orderBy('a.id', 'DESC')->setMaxResults($nb);
		return $qb->getQuery()->getResult();
	}

	public function getAdverts(){
		$qb = $this->createQueryBuilder('a')->where('a.published=1')->orderBy('a.date', 'DESC')
		->leftJoin('a.image','img')->addSelect('img')
		->leftJoin('a.categories','cat')->addSelect('cat')
		->leftJoin('a.advertSkills','advskills')->addSelect('advskills');


		return $qb->getQuery()->getResult();
	}

	public function getAdverts2($page, $nbPerPage) {
		$query = $this->createQueryBuilder('a')->where('a.published=1')->leftJoin('a.image', 'img')->addSelect('img')
		->leftJoin('a.categories', 'c')->addSelect('c')
		->orderBy('a.date', 'DESC')->getQuery();

		$query->setFirstResult(($page-1) * $nbPerPage) ->setMaxResults($nbPerPage);
		return new Paginator($query, true);
	}

}
