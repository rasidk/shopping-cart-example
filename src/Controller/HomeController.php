<?php

namespace App\Controller;

use App\Manager\ProductManager;
use App\Manager\PurchasedProductsManager;
use App\Manager\SalesManager;
use App\Manager\UserManager;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class HomeController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ProductManager
     */
    private $productManager;
    /**
     * @var SalesManager
     */
    private $salesManager;
    /**
     * @var PurchasedProductsManager
     */
    private $purchasedProductsManager;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        ProductRepository $productRepository,
        ProductManager $productManager,
        SalesManager $salesManager,
        PurchasedProductsManager $purchasedProductsManager,
        UserManager $userManager,


    ) {
        $this->productRepository = $productRepository;
        $this->productManager = $productManager;
        $this->salesManager = $salesManager;
        $this->purchasedProductsManager = $purchasedProductsManager;
        $this->userManager = $userManager;
    }


    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function home(Request $request)

    {
        $keyword = $request->get('keyword') ?? '';
        $products = $this->productRepository->findByName($keyword);

        return $this->render('home.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/cart", name="cart", methods={"GET"})
     */
    public function cart()
    {
        $session = $this->get('session');
        $products = $this->productManager->getCartProducts($session);
        $totalAmount = $this->productManager->getTotalAmount($products, $session);
        return $this->render('cart.html.twig', ['products' => $products, 'totalAmount' => $totalAmount]);
    }


    /**
     * @Route("/add-to-cart", name="add_to_cart", methods={"POST"})
     */
    public function addToCart(Request $request)
    {
        if ($request->isMethod('post')) {

            $productId = $request->request->get('productId');
            $size = $request->request->get('size');
            $qty = $request->request->get('qty') ?? 1;
            $session = $this->get('session');
            $cart = $session->get('cart');
            $cart[$productId] = [
                "size" => $size,
                "qty" => $qty
            ];
            $session->set('cart', $cart);
            return new JsonResponse(["status" => "success", "message" => "Products added to cart"]);
        }
    }


    /**
     * @Route("/update-cart", name="update_cart", methods={"POST"})
     */
    public function updateCart(Request $request)
    {
        if ($request->isMethod('post')) {

            $productId = $request->request->get('productId');
            $size = $request->request->get('size');
            $qty = $request->request->get('qty') ?? 1;
            $session = $this->get('session');
            $cart = $session->get('cart');
            $cart[$productId] = [
                "size" => $size,
                "qty" => $qty
            ];
            $session->set('cart', $cart);
            $products = $this->productManager->getCartProducts($session);
            $totalAmount = $this->productManager->getTotalAmount($products, $session);
            return new JsonResponse(["status" => "success", "message" => "Cart updated", 'totalAmount' => $totalAmount]);
        }
    }

    /**
     * @Route("/remove-cart-item", name="remove_cart_item", methods={"GET"})
     */
    public function removeCartItem(Request $request)
    {
        $productId = $request->get('productId');
        $session = $this->get('session');
        $cart = $session->get('cart');
        unset($cart[$productId]);
        array_filter($cart);
        $session->set('cart', $cart);
        $products = $this->productManager->getCartProducts($session);
        $totalAmount = $this->productManager->getTotalAmount($products, $session);
        return new JsonResponse(["status" => "success", "message" => "Products removed", 'totalAmount' => $totalAmount]);
    }


    /**
     * @Route("/order/place-order", name="place_order", methods={"GET"})
     */
    public function placeOrder(MailerInterface $mailer)
    {
        $user = $this->getUser();
        $session = $this->get('session');
        $cart = $session->get('cart');
        if (count($cart) > 0) {
            try {
                //Get all products in the cart
                $products = $this->productManager->getCartProducts($session);
                //Get total amount
                $totalAmount = $this->productManager->getTotalAmount($products, $session);
                if ($user->getCredits() >= $totalAmount) {
                    //Deduct user credit and update 
                    $balance = $user->getCredits() - $totalAmount;
                    $this->userManager->updateCredit($user, $balance);
                    //Create Sale
                    $sales = $this->salesManager->createSale($user, $totalAmount);
                    foreach ($products as $product) {
                        $getProduct = $this->productRepository->findOneBy(['id' => $product['id']]);
                        //Add purchased products
                        $this->purchasedProductsManager->savePurchasedProduct($sales, $getProduct, $cart[$product['id']]);
                    }
                    $session->set('cart', []);
                    //SendEmail

                    $email = (new TemplatedEmail())
                        ->from('noreply@example.com')
                        ->to($user->getEmail())
                        ->subject('Order Placed #'.$sales->getId())
                        ->htmlTemplate('email/order.html.twig')
                        ->context(["orderId"=>$sales->getId()]);

                    $mailer->send($email);
                    return new JsonResponse(["status" => "success", "message" => "Order placed"]);
                }
            } catch (\Exception $e) {
                return new JsonResponse(["status" => "error", "message" => "Something wen't wrong"]);
            }
            return new JsonResponse(["status" => "error", "message" => "You don't have enogugh credit to place order"]);
        }
        return new JsonResponse(["status" => "error", "message" => "Your cart is empty"]);
    }

    /**
     * @Route("/order/success", name="order_success", methods={"GET"})
     */
    public function orderSucess()
    {
        return $this->render('order_success.html.twig');
    }
}
