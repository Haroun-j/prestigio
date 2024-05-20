<?php
    
    namespace App\Controller;
    
    use App\Entity\Product;
    use App\Repository\ProductRepository;
    use MercurySeries\FlashyBundle\FlashyNotifier;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\Routing\Annotation\Route;
    
    #[Route('/cart', name: 'cart_')]
    class CartController extends AbstractController
    {
        #[Route('/', name: 'index')]
        public function index (SessionInterface $session, ProductRepository $productRepository): Response
        {
            $panier = $session->get('panier', []);
            
            $data = [];
            $total = 0;
            
            foreach ($panier as $item) {
                $product = $productRepository->find($item['id']);
                
                $data[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                ];
                $total += $product->getPrice() * $item['quantity'];
            }
            return $this->render('cart/index.html.twig', compact('data', 'total'));
        }
        
        
        #[Route('/add/{id}', name: 'add')]
        public function add (Product $product, SessionInterface $session, FlashyNotifier $flashy, Request $request): RedirectResponse
        {
            $id = $product->getId();
            $source = $request->query->get('source', 'store');
            $size = $request->query->get('size', '40');
            $quantity = $request->query->get('quantity', 1);
            
            $panier = $session->get('panier', []);
            
            $found = false;
            
            foreach ($panier as &$item) {
                if ($item['id'] == $id && $item['size'] == $size) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $panier[] = [
                    'id' => $id,
                    'quantity' => $quantity,
                    'size' => $size,
                ];
            }
            
            $session->set('panier', $panier);
            
            $url = $this->generateUrl('cart_index');
            $flashy->success('produit ajouté avec succés - Cliquez ici pour voir votre panier', $url);
            
            if ($source === 'store') {
                
                return $this->redirectToRoute('store');
                
            } elseif ($source === 'product_details') {
                
                return $this->redirectToRoute('product_details', ['id' => $id]);
                
            }
            
            return $this->redirectToRoute('cart_index');
        }
        
        #[Route('/remove/{id}/{size}', name: 'remove')]
        public function remove (Product $product, SessionInterface $session, Request $request): RedirectResponse
        {
            $id = $product->getId();
            $size = $request->get('size');
            
            $panier = $session->get('panier', []);
            
            foreach ($panier as $key => $item) {
                if ($item['id'] == $id && $item['size'] == $size) {
                    if ($item['quantity'] > 1) {
                        $panier[$key]['quantity']--;
                    } else {
                        unset($panier[$key]);
                    }
                    break;
                }
            }
            
            $session->set('panier', $panier);
            return $this->redirectToRoute('cart_index');
        }
        
        #[Route('/delete/{id}/{size}', name: 'delete')]
        public function delete (Product $product, SessionInterface $session, Request $request): RedirectResponse
        {
            $id = $product->getId();
            $size = $request->get('size');
            
            $panier = $session->get('panier', []);
            
            foreach ($panier as $key => $item) {
                if ($item['id'] == $id && $item['size'] == $size) {
                    unset($panier[$key]);
                    break;
                }
            }
            
            $session->set('panier', $panier);
            
            return $this->redirectToRoute('cart_index');
        }
        
        #[Route('/empty', name: 'empty')]
        public function empty (SessionInterface $session): RedirectResponse
        {
            $session->remove('panier');
            
            return $this->redirectToRoute('cart_index');
        }
        
    }