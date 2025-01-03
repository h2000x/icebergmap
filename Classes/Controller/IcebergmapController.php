<?php

namespace Justabunchof\Icebergmap\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
class IcebergmapController extends ActionController
{
    public function showAction(): ResponseInterface
    {
        $itemId = "22";
        $this->view->assign('item', "Item $itemId");

        return $this->htmlResponse();

    }
}