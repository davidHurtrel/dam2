<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'cart_index')]
    public function index(CartService $cartService, ProductRepository $productRepository): Response
    {
        $cart = $cartService->getCart();
        $total = $cartService->getTotal();
        $latestProducts = $productRepository->findBy([], ['createdAt' => 'DESC'], 4);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
            'latestProducts' => $latestProducts
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add')]
    public function add(CartService $cartService, int $id): Response
    {
        $cartService->add($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(CartService $cartService, int $id): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'cart_delete')]
    public function delete(CartService $cartService, int $id): Response
    {
        $cartService->delete($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/clear', name: 'cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        return $this->redirectToRoute('cart_index');
    }
}
