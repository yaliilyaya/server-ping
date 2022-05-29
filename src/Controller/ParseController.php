<?php
namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\RecipeRepository;
use App\Repository\RecipeStreamRepository;
use App\Srevice\ParseItemInfoService;
use App\Srevice\ParseItemListService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ParseController
 * @package App\Controller
 * @see Route
 */
class ParseController extends AbstractController
{
    /**
     * @Route("/parse/list", name="parse.list")
     * @param ItemRepository $itemRepository
     * @param ParseItemListService $parseItemListService
     * @param RecipeRepository $recipeRepository
     * @param RecipeStreamRepository $recipeStreamRepository
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function list(
        ItemRepository $itemRepository,
        ParseItemListService $parseItemListService,
        RecipeRepository $recipeRepository,
        RecipeStreamRepository $recipeStreamRepository
    ): Response {

        $itemRepository->clearItem();
        $recipeRepository->clearItem();
        $recipeStreamRepository->clearItem();

        $items = $parseItemListService->parse();
        $itemRepository->saveAll($items);

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/parse/item/{id}", name="parse.item")
     * @param ItemRepository $itemRepository
     * @param ParseItemInfoService $parseItemInfoService
     * @param RecipeRepository $recipeRepository
     * @param RecipeStreamRepository $recipeStreamRepository
     * @param int $id
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function item(
        ItemRepository $itemRepository,
        ParseItemInfoService $parseItemInfoService,
        RecipeRepository $recipeRepository,
        RecipeStreamRepository $recipeStreamRepository,
        int $id
    ): Response {
        $item = $itemRepository->find($id);

        if (!$item) {
            die(__FILE__);
        }

        $recipeStreamRepository->clearRecipeStream($item->getRecipes());
        $recipeRepository->clearRecipe($item);

        $parseItemInfoService->parse($item);

        $item->setStatus(Item::RECIPE_STATUS_TYPE);
        $itemRepository->save($item);

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/parse/recursive/item", name="parse.item.recursive")
     * @param ItemRepository $itemRepository
     * @param ParseItemInfoService $parseItemInfoService
     * @param RecipeRepository $recipeRepository
     * @param RecipeStreamRepository $recipeStreamRepository
     * @return Response
     */
    public function itemRecursive(
        ItemRepository $itemRepository,
        ParseItemInfoService $parseItemInfoService,
        RecipeRepository $recipeRepository,
        RecipeStreamRepository $recipeStreamRepository
    ): Response {
        $items = $itemRepository->findBy([
            'status' => Item::NEW_STATUS_TYPE
        ]);
        /** @var Item $item */
        $item = current($items) ?: null;
        if ($item) {
            try {
                $this->item(
                    $itemRepository,
                    $parseItemInfoService,
                    $recipeRepository,
                    $recipeStreamRepository,
                    $item->getId()
                );
                return $this->redirectToRoute('parse.item.recursive');
            } catch (\Exception $exception)  {
                $item->setStatus(Item::ERROR_STATUS_TYPE);
                $itemRepository->save($item);
            }
        }

        return $this->redirectToRoute('index');
    }
}