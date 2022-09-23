<?php

declare(strict_types=1);

namespace Core\Infrastructure\Persistence\Doctrine\Repository;


use Core\Domain\Entity\Product;
use Core\Domain\Exception\ProductNotFoundException;
use Core\Domain\Exception\ProductAlreadyExistsException;
use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\Filter\ProductFilterInterface;
use Shared\Infrastructure\Filter\PaginateInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method null|Product find($id, $lockMode = null, $lockVersion = null)
 * @method null|Product findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->em = $this->getEntityManager();
    }

    public function get(string $sku): Product
    {
        $product = $this->find($sku);
        if (!$product) {
            throw ProductNotFoundException::create($sku);
        }

        return $product;
    }

    public function save(Product $product): void
    {
        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw ProductAlreadyExistsException::create($product->sku());
        }
    }

    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    private function filterQuery(
        QueryBuilder $query,
        ProductFilterInterface $filter,
        PaginateInterface|null $paginate = null
    ): QueryBuilder
    {
        $query->where('1=1');
        if ($category = $filter->category()) {
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }
        if ($priceLessThan = $filter->priceLessThan()) {
            $query->andWhere('p.price.priceOriginal < :priceLessThan')
                ->setParameter('priceLessThan', $priceLessThan);
        }
        if ($paginate) {
            $query->setFirstResult(($paginate->page() - 1) * $paginate->size());
            if ($paginate->size() !== 0) {
                $query->setMaxResults($paginate->size());
            }
        }

        return $query;
    }

    public function filter(
        ProductFilterInterface $filter,
        PaginateInterface|null $paginate = null
    ): Paginator
    {
        $query = $this->createQueryBuilder('p');
        $query = $this->filterQuery(
            $query,
            $filter,
            $paginate
        );

        return new Paginator($query, $fetchJoinCollection = false);
    }


}