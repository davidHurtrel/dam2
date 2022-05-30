<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Service\CartService;
use Stripe\Checkout\Session;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route('/', name: 'payment_index')]
    public function index(Request $request, RequestStack $requestStack, ProductRepository $productRepository): Response
    {
        if ($request->headers->get('referer') !== 'https://127.0.0.1:8000/cart/') { // redirection si on ne vient pas du panier
            return $this->redirectToRoute('cart_index');
        }

        $cart = $requestStack->getSession()->get('cart'); // récupération du panier en session
        $stripeCart = []; // initialisation du panier pour Stripe

        foreach ($cart as $id => $quantity) { // tranformation du panier session en panier Stripe
            $product = $productRepository->find($id);
            $stripeElement = [
                'amount' => $product->getPrice() * 100,
                'quantity' => $quantity,
                'currency' => 'EUR',
                'name' => $product->getName()
            ];
            $stripeCart[] = $stripeElement;
        }

        $stripe = new StripeClient($this->getParameter('stripe_sk'));

        $stripeSession = $stripe->checkout->sessions->create([
            'line_items' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'https://127.0.0.1:8000/payment/success',
            'cancel_url' => 'https://127.0.0.1:8000/payment/cancel',
            'payment_method_types' => ['card']
        ]);

        return $this->render('payment/index.html.twig', [
            'sessionId' => $stripeSession->id
        ]);
    }

    #[Route('/success', name: 'payment_success')]
    public function success(Request $request, CartService $cartService): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {  // redirection si on ne vient pas de Stripe
            return $this->redirectToRoute('cart_index');
        }
        $cartService->clear();
        return $this->render('payment/success.html.twig');
    }

    #[Route('/cancel', name: 'payment_cancel')]
    public function cancel(Request $request): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') { // redirection si on ne vient pas de Stripe
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('payment/cancel.html.twig');
    }
}
