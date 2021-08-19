<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryFormType;
use Container2gd7xLk\getDoctrine_QuerySqlCommandService;
use Doctrine\Migrations\Query\Query;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category_create", name="create_category")
     */
     public function createCategory(Request $request) {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash("Success","Create new Category succeed !");
            return $this->redirectToRoute("view_category");
        }
        
        return $this->render( 
            'category/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
    /**
     * @Route("/category_index", name="view_category")
     */
    public function viewCategory()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render(
            'category/index.html.twig', 
            [
            'categories' => $category,
            ]
        );
    }
    /**
     * @Route("/category/update/{id}", name="update_category")
     */
    public function updateCategory(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class,$category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash("Success","Update category succeed !");
            return $this->redirectToRoute("view_category");
        } 
        return $this->render( 
            'category/update.html.twig',
            [
                'form' => $form->createView()
            ]
        );    
    }
    /**
     * @Route("/category/delete/{id}", name="delete_category")
     */
    public function removeCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        
        

        if ($category == null) {
            $this->addFlash("Error","Invalid Category ID");
            return $this->redirectToRoute("view_category");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        $this->addFlash("Success","Category removing succeed !");
        return $this->redirectToRoute('view_category');
    }
    /**
     * @Route("/category/{id}", name="category_detail")
     */
    public function detailcategory ($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        
        return $this->render(
            "category/details.html.twig",
            [
              'category' => $category
            ]
        );
    }
   
    
}