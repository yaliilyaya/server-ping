<?php

namespace App\Srevice;

use App\Entity\Item;
use App\Entity\Recipe;
use App\Entity\RecipeStream;
use App\Repository\ItemRepository;
use App\Repository\RecipeRepository;
use App\Repository\RecipeStreamRepository;
use Exception;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ParseItemInfoService
{
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var RecipeStreamRepository
     */
    private $recipeStreamRepository;
    /**
     * @var ParameterBagInterface
     */
    private $params;
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(
        HttpClientInterface $client,
        RecipeRepository $recipeRepository,
        RecipeStreamRepository $recipeStreamRepository,
        ParameterBagInterface $params,
        ItemRepository $itemRepository
    ) {
        $this->client = $client;
        $this->recipeRepository = $recipeRepository;
        $this->recipeStreamRepository = $recipeStreamRepository;
        $this->params = $params;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param Item $item
     */
    public function parse(Item $item)
    {
        $page = $this->getPage($item);

        $detail = $page->filterXPath("//div[@class= 'media-body']");
        $detailList = $page->filterXPath("//div[@class='row']/div");
        $sourceRecipeList = $page->filterXPath("//div[contains(@class,'card-header')]//div/strong[contains(. ,'Рецепты')]/../../../../div[contains(@class,'card-body')]");
        $altRecipe = $page->filterXPath("//div[contains(@class,'card-header')]//div/strong[contains(. ,'Альтернативные рецепты')]/../../../../div[contains(@class,'card-body')]");

        $item->setDetail($detail->filterXPath("//div[@class= 'card-body']")->text(''))
            ->setCategory($detailList->filterXPath("//span[contains(. , 'Категория')]/../span[2]")->text(''))
            ->setStackSize($detailList->filterXPath("//span[contains(. , 'Размер стека')]/../span[2]")->text(''))
            ->setSalvaging($detailList->filterXPath("//span[contains(. , 'Очки утилизации')]/../span[2]")->text(''));

        $recipeList = $this->parseRecipe($sourceRecipeList);

        foreach ($recipeList as $recipeInfo)
        {
            $recipe = $this->recipeRepository->create()
                ->setItem($item)
                ->setType(Recipe::MAIN_RECIPE_TYPE)
                ->setLabel($recipeInfo['label'])
                ->setFactory($recipeInfo['factory']);

            $recipe->addRecipeStreams(array_merge(
                array_map([$this, 'crateStreamByTypeIn'], $recipeInfo['in']),
                array_map([$this, 'crateStreamByTypeOut'], $recipeInfo['out'])
            ));

            $item->getRecipes()->add($recipe);
        }

        $altRecipe = $this->parseAltRecipe($altRecipe);

        foreach ($altRecipe as $recipeInfo)
        {
            $recipe = $this->recipeRepository->create()
                ->setItem($item)
                ->setType(Recipe::ALT_RECIPE_TYPE)
                ->setLabel($recipeInfo['label'])
                ->setFactory($recipeInfo['factory']);

            $recipe->addRecipeStreams(array_merge(
                array_map([$this, 'crateStreamByTypeIn'], $recipeInfo['in']),
                array_map([$this, 'crateStreamByTypeOut'], $recipeInfo['out'])
            ));

            $item->getRecipes()->add($recipe);
        }
    }

    /**
     *
     * Примеры
     * (25 / min)10xКремнезём
     * (15m³ / min)6m³Азотная кислота
     *
     * @param Crawler $parentCrawler
     * @param $i
     * @return array
     */
    private function parseRecipeItemInfo(Crawler $parentCrawler, $i): array
    {
        $countTimeText = $parentCrawler->filterXPath('//span')->text('');

        $countTime = false;
        if (preg_match('/([\d,]+)(m³)\s*\/\s*([a-z]+)/u', $countTimeText, $matches))
        {
            $countTime = [
                'full' => $matches[0],
                'count' => (float)str_replace(',', '.', $matches[1]),
                'timeType' => $matches[3],
                'type' => $matches[2],
            ];
        }
        elseif (preg_match('/([\d,]+(?:m³)?)\s*\/\s*([a-z]+)/u', $countTimeText, $matches))
        {
            $countTime = [
                'full' => $matches[0],
                'count' =>  (float)str_replace(',', '.', $matches[1]),
                'timeType' => $matches[2],
                'type' => 'шт',
            ];
        }

        $countText = $parentCrawler->text('');
        $countText = preg_replace('/\(.+\)/', '', $countText);
        preg_match('/(\d+)(?:x|(m³))/u', $countText, $countMatches);


        return [
            'message' => $parentCrawler->text(''),
            'label' => $parentCrawler->filterXPath('//a')->text(''),
            'countTime' => $countTime,
            'count' => $countMatches[1],
            'type' => $countMatches[2] ?? 'шт'
        ];
    }

    /**
     * @param $recipe
     * @return array
     */
    private function parseRecipe($recipe): array
    {
        return $recipe->each(function (Crawler $parentCrawler, $i) {
            return [
                'label' => $parentCrawler->filterXPath('div/div[1]//h5')->text(''),
                'factory' => $parentCrawler->filterXPath('div/div[1]//em//a')->text(''),
                'in' => $parentCrawler->filterXPath('div/div[2]/div[1]/div')->each(function (Crawler $parentCrawler, $i){
                    return $this->parseRecipeItemInfo($parentCrawler, $i);
                }),
                'out' => $parentCrawler->filterXPath('div/div[2]/div[2]/div')->each(function (Crawler $parentCrawler, $i){
                    return $this->parseRecipeItemInfo($parentCrawler, $i);
                }),
            ];
        });
    }

    /**
     * @param object $altRecipe
     * @return array
     */
    private function parseAltRecipe(object $altRecipe): array
    {
        return $altRecipe->each(function (Crawler $parentCrawler, $i){
            return [
                'label' => $parentCrawler->filterXPath('div/div[1]//h5')->text(''),
                'factory' => $parentCrawler->filterXPath('div/div[1]//em//a')->text(''),
                'in' => $parentCrawler->filterXPath('div/div[2]/div[1]/div')->each(function (Crawler $parentCrawler, $i){
                    return $this->parseRecipeItemInfo($parentCrawler, $i);
                }),
                'out' => $parentCrawler->filterXPath('div/div[2]/div[2]/div')->each(function (Crawler $parentCrawler, $i){
                    return $this->parseRecipeItemInfo($parentCrawler, $i);
                }),
            ];
        });
    }

    /**
     * @param array $streamInfo
     * @return RecipeStream
     */
    private function crateStreamByTypeIn(array $streamInfo): RecipeStream
    {
        $item = $this->itemRepository->findByLabel($streamInfo['label']);
        if (!$item)
        {
            //dump($streamInfo);
            throw new RuntimeException('Не найден элемент - ' . $streamInfo['label']);
        }
        return $this->recipeStreamRepository->create()
            ->setType(RecipeStream::IN_STREAM_TYPE)
            ->setItem($item)
            ->setLabel($streamInfo['label'])
            ->setMessage($streamInfo['message'])
            ->setCountTimeType($streamInfo['countTime']['timeType'])
            ->setCountTime($streamInfo['countTime']['count'])
            ->setCount($streamInfo['count'])
            ->setItemType($streamInfo['type']);
    }

    /**
     * @param array $streamInfo
     * @return RecipeStream
     */
    private function crateStreamByTypeOut(array $streamInfo): RecipeStream
    {
        $item = $this->itemRepository->findByLabel($streamInfo['label']);
        if (!$item)
        {
            //dump($streamInfo);
            throw new RuntimeException('Не найден элемент - ' . $streamInfo['label']);
        }

        return $this->recipeStreamRepository->create()
            ->setType(RecipeStream::OUT_STREAM_TYPE)
            ->setItem($item)
            ->setLabel($streamInfo['label'])
            ->setMessage($streamInfo['message'])
            ->setCountTimeType($streamInfo['countTime']['timeType'])
            ->setCountTime($streamInfo['countTime']['count'])
            ->setCount($streamInfo['count'])
            ->setItemType($streamInfo['type']);
    }

    /**
     * @param $item
     * @return Crawler
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getPage($item): Crawler
    {
        $cachePath = implode(
            [
                $this->params->get('kernel.cache_dir'),
                'page_xml',
                md5($item->getUrl()) . '.xml'
            ],
            DIRECTORY_SEPARATOR
        );

        if (file_exists($cachePath)) {
            return new Crawler(file_get_contents($cachePath));
        }

        $response = $this->client->request('GET', $item->getUrl());

        $content = $response->getContent();
        file_put_contents($cachePath, $content);

        return new Crawler($content);
    }
}