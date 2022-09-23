<?php

declare(strict_types=1);

namespace Core\Infrastructure\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Core\Application\UseCase\CalculateProductDiscountUseCase;
use Core\Domain\Entity\Price;
use Core\Domain\Entity\Product;
use Core\Domain\Request\AddProductRequest;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Shared\Infrastructure\Tests\Behat\Assert\Assert;

final class ProductContext implements Context
{
    private ObjectManager $entityManager;
    private CalculateProductDiscountUseCase $calculateProductDiscountUseCase;
    private $productRepository;

    public function __construct(
        ManagerRegistry $doctrine,
        CalculateProductDiscountUseCase $calculateProductDiscountUseCase
    )
    {
        $this->entityManager = $doctrine->getManager();
        $this->productRepository = $this->entityManager->getRepository(Product::class);
        $this->calculateProductDiscountUseCase = $calculateProductDiscountUseCase;
    }

    /**
     * @Given there are the following products:
     *
     * @throws \Exception
     */
    public function thereAreTheFollowingProducts(TableNode $products): void
    {
        foreach ($products as $product) {
            $discount = $this->calculateProductDiscountUseCase->execute(AddProductRequest::create(
                (string) $product['Sku'],
                (string) $product['Name'],
                (string) $product['Category'],
                (int) $product['Price']
            ));

            $price = Price::create(
                (int) $product['Price'],
                $discount
            );

            $newProduct = Product::create(
                (string) $product['Sku'],
                (string) $product['Name'],
                (string) $product['Category'],
                $price
            );

            $this->productRepository->save($newProduct);

            $productFromDB = $this->productRepository->find(
                $product['Sku']
            );

            Assert::eq($product['Sku'], $productFromDB->sku());
        }
    }

    /**
     * @Then product with sku :id not exists
     *
     * @throws \Exception
     */
    public function productWithSkuNotExists(string $sku): void
    {
        $this->entityManager->clear();
        $productFromDB = $this->productRepository->find($sku);
        Assert::null($productFromDB);
    }


    /**
     * @Then product with sku :id exists
     *
     * @throws \Exception
     */
    public function productWithIdExists(string $sku): void
    {
        $this->entityManager->clear();
        $productFromDB = $this->productRepository->find($id);
        Assert::isInstanceOf($productFromDB, Product::class);
    }

}
