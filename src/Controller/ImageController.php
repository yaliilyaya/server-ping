<?php
namespace App\Controller;

use App\Repository\ItemRepository;
use App\Srevice\SaveImgService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @see Route
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/img/item/{id}", name="img.item")
     * @param ItemRepository $itemRepository
     * @param SaveImgService $saveImgService
     * @param int $id
     * @return Response
     */
    public function index(
        ItemRepository $itemRepository,
        SaveImgService $saveImgService,
        int $id
    ): Response {
        $item = $itemRepository->find($id);

        $isSuccess = $saveImgService->saveItemImg($item);
        if ($isSuccess) {
            return $this->redirect('/' . $item->getImgPath());
        }

        return $this->redirect($item->getImg());
    }
}