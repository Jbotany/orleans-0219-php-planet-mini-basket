<?php
namespace App\Controller;

use App\Model\FaqManager;
use App\tools\CleanData;

class FaqAdminController extends AbstractController
{
    public function index()
    {
        $faqManager = new FaqManager();
        $faqs = $faqManager->selectAll();
        return $this->twig->render('FaqAdmin/index.html.twig', ['faqs' => $faqs]);
    }

    public function edit(int $id): string
    {
        $faqManager = new FaqManager();
        $faqs = $faqManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $faqs['question_name'] = $_POST['question_name'];
            $faqs['answer_name'] = $_POST['answer_name'];
            $faqManager->update($faqs);
            header('Location:/FaqAdmin/index/');
            exit();
        }
        return $this->twig->render('FaqAdmin/edit.html.twig', ['faqs' => $faqs]);
    }
    
  /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $faqManager = new FaqManager();
        $faqManager->delete($id);
        header('Location:/FaqAdmin/index');
        exit();
    }

    public function add()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cleanData = new CleanData($_POST);
            $data = $cleanData->trimData();
            $faqManager = new FaqManager();
            if (empty($data['question_name'])) {
                $errors['question_name'] = 'Veuillez entrer votre question';
            }
            if (empty($data['answer_name'])) {
                $errors['answer_name'] = 'Veuillez entrer votre réponse';
            } else {
                $faq = [
                    'question_name' => $data['question_name'],
                    'answer_name' => $data['answer_name']
                ];
                $id = $faqManager->insert($faq);
                header('Location:/FaqAdmin/index/');
                exit();
            }
        }
        return $this->twig->render('FaqAdmin/add.html.twig', ['error' => $errors]);
    }
}
