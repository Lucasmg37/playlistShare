<?php


namespace App\Controller\Api;

use App\Business\Usuario;
use App\Controller\Controller;
use App\Model\Entity\Like;
use App\Model\Entity\VwLike;
use App\Model\Response;

class LikeController extends Controller
{

    public function getAction($id)
    {
        $vwLike = new VwLike(['user_id' => Usuario::getUserLogged()->getIdUsuario()]);
        return $vwLike->find();
    }

    public function postAction()
    {

        $playlist_id = $this->request->getParameter("playlist_id", true, "O id da playlist não foi informado!");
        $unLike = $this->request->getParameter("unLike", false);

        $likeEntity = new Like();
        $likeEntity->setUserId(Usuario::getUserLogged()->getIdUsuario());
        $likeEntity->setPlaylistId($playlist_id);

        if ($unLike) {
            $likeEntity->setActive(1);
            $likeEntity->findAndMount();
            if ($likeEntity->getId()) {
                $likeEntity->setActive("0");
                $likeEntity->save();
                return $likeEntity;
            }

            Response::failResponse("A playlist informada não foi curtida pelo usuário.");
        }

        $likeEntity->setActive(1);
        $likeEntity->findAndMount();

        if ($likeEntity->getId()) {
            return $likeEntity;
        }
        $likeEntity->clearObject();
        $likeEntity->setUserId(Usuario::getUserLogged()->getIdUsuario());
        $likeEntity->setPlaylistId($playlist_id);
        $likeEntity->insert();
        return $likeEntity;
    }
}
