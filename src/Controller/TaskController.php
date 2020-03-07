<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Gère les paths liées à l'entité Task
 */
class TaskController extends AbstractController
{
    /**
     * Affiche la liste des tasks en base de données
     *
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findAll()]);
    }

    /**
     * Affiche la liste des tasks en base de données
     *
     * @Route("/tasksfinished", name="tasksfinished")
     */
    public function listActionFinished()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findBy(array('isDone' => true))]);
    }

    /**
     * Rajoute une task en base de données
     * Accessible aux users authentifiés
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->getDoctrine()->getRepository('App:User')->findOneByUsername($this->getUser()->getUsername());
                $task->setUser($user);
                $em = $this->getDoctrine()->getManager();
    
                $em->persist($task);
                $em->flush();
    
                $this->addFlash('success', 'La tâche a été bien été ajoutée.');
    
                return $this->redirectToRoute('task_list');
            }
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Met à jour une task
     * Accessible aux users authentifiés
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('task_list');
            }
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * Mets à jour une task pour l'indiquer comme done
     * Accessible aux users authentifiés
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        if ($this->getUser()->getUsername() == ($task->getUser()->getUsername())) {

            //$task->toggle(!$task->setIsDone(true));
            $task->setIsDone(true);
            $this->getDoctrine()->getManager()->flush();
    
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
    
            return $this->redirectToRoute('task_list');
        } else {
            $this->addFlash('error', 'La tâche a pas changé.');
        }
    }

    /**
     * Supprime une task de la base de données
     * Acessible aux users authentifiés
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        if ($this->getUser()->getUsername() == ($task->getUser()->getUsername())) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
    
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', 'La tâche existe toujours.');
        }
        return $this->redirectToRoute('task_list');
    }
}
