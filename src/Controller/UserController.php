<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Gère les paths liées à l'entité User
 */
class UserController extends AbstractController
{
    /**
     * Affiche la liste des entités User
     *
     * @IsGranted("ROLE_ADMIN")
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App:User')->findAll()]);
    }

    /**
     * Rajoute un user en base de données
     *
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->createRole();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);

                //$user->setRole("Utilisateur");
                //$user->setRoles(explode( "" , $user->getRole()));
                $user->setRoles(array( $user->getRole()));

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', "L'utilisateur a bien été ajouté.");

                return $this->redirectToRoute('user_list');
            }
        }
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Met à jour un user
     * Accessible aux users authentifiés
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user->createRole();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $password = $user->getPassword();
                $hash = $encoder->encodePassword($user, $user->getPassword());

                $user->setPassword($password);
                $user->setRoles(explode(",", $user->getRole()));

                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");

                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
