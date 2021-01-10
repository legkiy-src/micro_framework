<?php

class KktController extends Controller
{
    public function getAction()
    {
        $model = new Kkt();
        $user = new User();
        $headers = $this->request->getHeaders();

        if (!$userData = $user->findUser($headers['login'], $headers['inn'])) {
            return false;
        }

        $result = $model->get(['user_id' => $userData['id']]);

        if(!$result) {
            return false;
        }

        return $this->view->renderJson($result);
    }
}
