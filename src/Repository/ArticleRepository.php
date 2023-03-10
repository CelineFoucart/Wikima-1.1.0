<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleType;
use App\Entity\Data\SearchData;
use App\Entity\User;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private PaginatorService $paginatorService;

    public function __construct(ManagerRegistry $registry, PaginatorService $paginatorService)
    {
        parent::__construct($registry, Article::class);
        $this->paginatorService = $paginatorService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Returns a pagination of articles by portals.
     */
    public function findByPortals(array $portals, int $page, int $limit = 30, bool $hidePrivate = true, ?ArticleType $type = null): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->leftJoin('a.portals', 'p')->addSelect('p')
            ->leftJoin('a.type', 't')->addSelect('t')
            ->andWhere('p.id IN (:portals)')
            ->andWhere('a.isDraft IS NULL OR a.isDraft = 0')
            ->setParameter('portals', $portals)
        ;

        if ($type) {
            $query->andWhere('t.slug = :type')->setParameter('type', $type->getSlug());
        }

        if ($hidePrivate) {
            $query->andWhere('a.isPrivate IS NULL OR a.isPrivate = 0');
        }

        return $this->paginatorService->setLimit($limit)->paginate($query, $page);
    }

    /**
     * Returns a pagination of 16 articles.
     */
    public function findPaginated(int $page, bool $hidePrivate = true): PaginationInterface
    {
        $builder = $this->createQueryBuilder('a')->andWhere('a.isDraft IS NULL OR a.isDraft = 0')->orderBy('a.title', 'ASC');

        if ($hidePrivate) {
            $builder->andWhere('a.isPrivate IS NULL OR a.isPrivate = 0');
        }

        return $this->paginatorService->setLimit(16)->paginate($builder, $page);
    }

    /**
     * Finds an article and its portals.
     */
    public function findBySlug(string $slug): ?Article
    {
        return $this->getDefaultQueryBuilder()
            ->andWhere('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->addOrderBy('s.position')
            ->addOrderBy('s.id')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findById(int $id): ?Article
    {
        return $this->getDefaultQueryBuilder()
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->orderBy('s.position')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByUser(User $user, int $page = 1, bool $hidePrivate = true): PaginationInterface
    {
        $builder = $this->getDefaultQueryBuilder()
            ->andWhere('a.author = :user')
            ->andWhere('a.isDraft IS NULL OR a.isDraft = 0')
            ->setParameter('user', $user)
        ;

        if ($hidePrivate) {
            $builder->andWhere('a.isPrivate IS NULL OR a.isPrivate = 0');
        }

        return $this->paginatorService->setLimit(12)->paginate($builder, $page);
    }

    public function findAuthorDrafts(User $user, int $page): PaginationInterface
    {
        $builder = $this->getDefaultQueryBuilder()
            ->andWhere('a.author = :user')
            ->andWhere('a.isDraft = 1')
            ->setParameter('user', $user)
        ;

        return $this->paginatorService->setLimit(12)->paginate($builder, $page);
    }

    public function search(SearchData $search, int $limit = 10, bool $hidePrivate = true): PaginationInterface
    {
        $builder = $this->getDefaultQueryBuilder();

        if (strlen($search->getQuery()) >= 3 and null !== $search->getQuery()) {
            $builder
                ->andWhere('a.title LIKE :q_1')
                ->setParameter('q_1', '%'.$search->getQuery().'%')
                ->orWhere('a.description LIKE :q_2')
                ->setParameter('q_2', '%'.$search->getQuery().'%')
                ->orWhere('a.content LIKE :q_3')
                ->setParameter('q_3', '%'.$search->getQuery().'%')
            ;
        }

        if (!empty($search->getPortals())) {
            $builder
                ->andWhere('p.id IN (:portals)')
                ->setParameter('portals', $search->getPortals())
            ;
        }

        $builder->andWhere('a.isDraft IS NULL OR a.isDraft = 0');

        if ($hidePrivate) {
            $builder->andWhere('a.isPrivate IS NULL OR a.isPrivate = 0');
        }

        return $this->paginatorService->setLimit($limit)->paginate($builder, $search->getPage());
    }

    public function findByType(ArticleType $articleType, int $page = 1, bool $hidePrivate = true, int $limit = 10): PaginationInterface
    {
        $builder = $this->getDefaultQueryBuilder();

        if ($hidePrivate) {
            $builder->andWhere('a.isPrivate IS NULL OR a.isPrivate = 0');
        }

        $builder->andWhere('a.type = :type')->setParameter('type', $articleType);

        return $this->paginatorService->setLimit($limit)->paginate($builder, $page);
    }

    private function getDefaultQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->leftJoin('a.portals', 'p')->addSelect('p')
            ->leftJoin('a.author', 'u')->addSelect('u')
            ->leftJoin('a.sections', 's')->addSelect('s')
        ;
    }

    public function findSticky(?int $portalId = null): array
    {
        $builder = $this->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->andWhere('a.isSticky = 1 AND a.isSticky IS NOT NULL')
        ;

        if ($portalId) {
            $builder
                ->leftJoin('a.portals', 'p')->addSelect('p')
                ->andWhere('p.id = :portalId')
                ->setParameter('portalId', $portalId)
            ;
        }

        return $builder->getQuery()->getResult();
    }
}
