<?php


namespace App\Srevice;


use App\Entity\Item;
use App\Entity\RecipeStream;
use App\Repository\RecipeStreamRepository;

class CalculateService
{
    /**
     * @var RecipeStreamRepository
     */
    private $recipeStreamRepository;

    public function __construct(
        RecipeStreamRepository $recipeStreamRepository
    ) {
        $this->recipeStreamRepository = $recipeStreamRepository;
    }

    public function calculate(Item $item)
    {
        $item->setStackSize( $item->getStackSize() ?: 100);
        $item->setWeight($item->getStackSize() / 10);
        $streams = $this->recipeStreamRepository->findBy(['item' => $item]);
        if ($streams) {
            usort($streams, function (RecipeStream $stream1, RecipeStream $stream2) {
                return $stream2->count - $stream1->count ;
            });
            $item->setMaxCount(current($streams)->getCount());
        }
    }
}