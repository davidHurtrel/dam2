<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;
    private $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;
    }
    
    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $this->session->set('cart', $cart);
    }

    public function delete(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function clear()
    {
        $this->session->remove('cart');
    }

    public function getCart()
    {
        $sessionCart = $this->session->get('cart', []);
        $cart = [];
        foreach ($sessionCart as $id => $quantity) {
            $element = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
            $cart[] = $element;
        }
        return $cart;
    }

    public function getTotal()
    {
        $cart = $this->session->get('cart', []);
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $total += $product->getPrice() * $quantity;
        }
        return $total;
    }

    public function getNbProducts()
    {
        $cart = $this->session->get('cart', []);
        $nb = 0;
        foreach ($cart as $line) {
            $nb++;
        }
        return $nb;
    }
}
