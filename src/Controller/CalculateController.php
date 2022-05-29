<?php


namespace App\Controller;


use App\Entity\Item;
use App\Entity\Recipe;
use App\Repository\ItemRepository;
use App\Srevice\CalculateRecipeService;
use App\Srevice\CalculateService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculateController extends AbstractController
{
    /**
     * @Route("/calculate/item/", name="calculate.item")
     * @param ItemRepository $itemRepository
     * @param CalculateService $calculateService
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function detail(
        ItemRepository $itemRepository,
        CalculateService $calculateService,
        CalculateRecipeService $calculateRecipeService
    ): Response {
        $items = $itemRepository->findAll();
        /** @var Item $item */
        foreach ($items as $item)
        {
            $calculateService->calculate($item);
        }

        foreach ($items as $item)
        {
            /** @var Recipe $recipe */
            foreach ($item->getRecipes() as $recipe) {
                $calculateRecipeService->calculate($recipe);
            }
        }
        $itemRepository->saveAll($items);

        return $this->redirectToRoute('index');
    }
}