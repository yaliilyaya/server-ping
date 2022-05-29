<?php


namespace App\Srevice;

use App\Entity\Recipe;
use App\Entity\RecipeStream;

class CalculateRecipeService
{
    public function calculate(Recipe $recipe)
    {
        $countCellType = [
            RecipeStream::IN_STREAM_TYPE => 0,
            RecipeStream::OUT_STREAM_TYPE => 0,
        ];
        $categories = [];
        $recipeTime = [];
        /** @var RecipeStream $stream */
        foreach ($recipe->getRecipeStreams() as $stream)
        {
            $weight = $stream->getCount() / $stream->getItem()->getWeight();
            $countCell = ceil($weight);
            $countCellType[$stream->getType()] += $countCell;

            $stream->setTime(round(60 / ($stream->getCountTime() ?: 0.000001), 2));
            $stream->setWeight($weight)
                ->setCountCell($countCell);

            $category = $stream->getItem()->getCategory();
            $categories[$category] = $stream->getCountCell() + ($categories[$category] ?? 0);
            $recipeTime[] = $stream->getCount() * $stream->getTime();
        }

        $recipe->setSizeIn($countCellType[RecipeStream::IN_STREAM_TYPE]);
        $recipe->setSizeOut($countCellType[RecipeStream::OUT_STREAM_TYPE]);

        $categories = $this->getCategoriesWithCount($categories);
        $recipe->setCategories($categories);

        $sizeBox = $this->calculateSizeBox(
            $countCellType[RecipeStream::IN_STREAM_TYPE],
            $countCellType[RecipeStream::OUT_STREAM_TYPE]
        );
        $recipe->setSizeBox($sizeBox);

        $recipe->setTime(max($recipeTime));
    }

    /**
     * @param array $categories
     * @return array
     */
    private function getCategoriesWithCount(array $categories): array
    {
        $categoriesWithCount = [];
        foreach ($categories as $category => $count)
        {
            $categoriesWithCount[] = $category . ': ' . $count;
        }

        return $categoriesWithCount;
    }

    /**
     * @param int $in
     * @param int $out
     * @return int
     */
    private function calculateSizeBox(int $in, int $out): int
    {
        $sizeBox = $in + $out;
        $boxSizes = [2,2,2,4,4,6,6,8,8,12,12,12,12];
        return  $boxSizes[$sizeBox];
    }
}