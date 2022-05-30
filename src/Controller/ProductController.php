<?php

namespace App\Controller;

use DateTime;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $manager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->manager = $managerRegistry->getManager();
    }

    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function create(Request $request, SluggerInterface $slugger)
    {
        $product = new Product();
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $product->setSlug($slugger->slug($product->getName()));
            $product->setCreatedAt(new \DateTimeImmutable());
            $this->manager->persist($product);
            $this->manager->flush();
            $this->addFlash('success', 'Le produit a bien été créé');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/create.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }
}
