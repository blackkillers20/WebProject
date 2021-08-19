<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductFormType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use function PHPUnit\Framework\throwException;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product.detail")
     */
    public function index(Product $product, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUptadedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        }

        return $this->render('product/details.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/product_index", name="view_product")
     */
    public function viewProduct()
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render(
            'product/index.html.twig', 
            [
            'products' => $product,
            ]
        );
    }
    /**
     * @Route("/product_create", name="create_product")
     */
    public function createProduct(Request $request) {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Images = $product -> getImages();

            $fileName = md5(uniqid());
            $fileExtension = $Images->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            
            try {
                $Images->move(
                    $this->getParameter('product_image'), $imageName
                );
            } catch (FileException $e) {
                throwException($e);
            }
            $product->setImages($imageName);


            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash("Success","Create product succeed !");
            return $this->redirectToRoute("view_product");
        }
            

        return $this->render( 
            'product/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
    /**
     * @Route("/product/update/{id}", name="update_product")
     */
    public function updateProduct(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash("Success","Update product succeed !");
            return $this->redirectToRoute("view_product");
        } 
        return $this->render( 
            'product/update.html.twig',
            [
                'form' => $form->createView()
            ]
        );    
    
    }
    /**
     * @Route("/product/delete/{id}", name="delete_product")
     */
    public function deleteProduct($id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if ($product == null) {
            $this->addFlash("Error","Invalid Product ID");
            return $this->redirectToRoute("view_product");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        $this->addFlash("Success","Product book succeed !");
        return $this->redirectToRoute('view_product');
    }

}
