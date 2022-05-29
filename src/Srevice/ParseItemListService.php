<?php

namespace App\Srevice;

use App\Repository\ItemRepository;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ParseItemListService
{
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(
        HttpClientInterface $client,
        ItemRepository $itemRepository
    ) {
        $this->client = $client;
        $this->itemRepository = $itemRepository;
    }

    public function parse()
    {
        $response = $this->client->request(
            'GET',
            'https://satisfactory-calculator.com/ru/items'
        );

        $page = new Crawler($response->getContent());
        $pageItems = $page->filterXPath("//div[@class= 'card-body']");

        return $pageItems->each(function (Crawler $parentCrawler, $i) {
            $img = $parentCrawler->filterXPath('//a/img');
            $text = $parentCrawler->filterXPath('//h6/a');

            return $this->itemRepository->create()
                ->setImg($img->attr('src'))
                ->setUrl('https://satisfactory-calculator.com' . $text->attr('href'))
                ->setLabel($text->text(''));
        });
    }
}