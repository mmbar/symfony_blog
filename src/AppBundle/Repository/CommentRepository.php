<?php

namespace AppBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
	public function getMostCommented()
	{
		return $this->getEntityManager()
			->createQuery(
			'SELECT p.title,p.id,count(c.created) as cc FROM AppBundle:Comment c 
				 JOIN AppBundle:Post p
				 WITH c.post = p 
			     GROUP BY p
				 ORDER BY cc DESC
				'
			)->setMaxResults(3)
			->getResult()
			;
	}
	public function getRecentlyCommented()
	{
		
		return $this->getEntityManager()
			->createQuery(
				'SELECT p.title,p.id,max(c.created) as cc FROM AppBundle:Comment c 
				 JOIN AppBundle:Post p
				 WITH c.post = p 
			     GROUP BY p
				 ORDER BY cc DESC
				'
			)->setMaxResults(3)
			->getResult()
		;
	}
}
