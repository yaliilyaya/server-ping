<?php
namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\ItemRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @uses Route
 */
class ExportController extends AbstractController
{
    /**
     * @Route("/export/item/{id}", name="export.item")
     * @param ItemRepository $itemRepository
     * @param int $id
     * @return Response
     */
    public function item(
        ItemRepository $itemRepository,
        int $id
    ): Response {
        $item = $itemRepository->find($id);

        if (!$item) {
            die(__FILE__);
        }

        return $this->json($item->toArray());
    }

    /**
     * @Route("/export/recipe", name="export.recipes")
     * @return Response
     */
    public function recipes(
        RecipeRepository $recipeRepository
    ): Response {
        $recipes = $recipeRepository->findAll();

        if (!$recipes) {
            die(__FILE__);
        }

        return $this->json(array_map(function (Recipe $recipe) {
            return $recipe->toArray();
        }, $recipes));
    }

    /**
     * @Route("/export/recipe/{id}", name="export.recipe")
     * @param int $id
     * @return Response
     */
    public function recipe(
        RecipeRepository $recipeRepository,
        int $id
    ): Response {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            die(__FILE__);
        }

        return $this->json($recipe->toArray());
    }

}