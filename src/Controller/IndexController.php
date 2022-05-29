<?php
namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\RecipeRepository;
use App\Srevice\BuilderAtlasImagesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @see Route
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param ItemRepository $itemRepository
     * @return Response
     */
    public function index(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAll();

        return $this->render('index/list.html.twig', [
            'items' => $items
        ]);
    }
    /**
     * @Route("/category", name="category")
     * @param ItemRepository $itemRepository
     * @return Response
     */
    public function category(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAll();

        $categoryItems = [];

        /** @var Item $item */
        foreach ($items as $item)
        {
            $categoryItems[$item->getCategory()][] = $item;
        }


        return $this->render('index/category.html.twig', [
            'categoryItems' => $categoryItems
        ]);
    }

    /**
     * @Route("/recipe", name="recipe")
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    public function recipe(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('index/recipe.list.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     * @param ItemRepository $itemRepository
     * @return Response
     */
    public function detail(
        ItemRepository $itemRepository,
        int $id
    ): Response {
        $item = $itemRepository->find($id);
        if (!$item) {
            return $this->redirectToRoute('index');
        }
        dump($item->toArray());
        return $this->render('index/detail.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/atlas", name="atlas")
     * @param ItemRepository $itemRepository
     * @return Response
     */
    public function atlas(
        ItemRepository $itemRepository,
        BuilderAtlasImagesService $builderAtlasImagesService
    ): Response
    {
        $items = $itemRepository->findAll();

        $builderAtlasImagesService->create($items, 40, 40);



        die(__FILE__);
        return $this->render('index/atlas.html.twig', [
            'items' => $items
        ]);
    }

}