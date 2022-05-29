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
     * @return Response
     */
    public function index(): Response
    {
        $items = [];

        return $this->render('index/list.html.twig', [
            'items' => $items
        ]);
    }
}