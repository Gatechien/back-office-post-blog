<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/list/user", name="list_user")
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    public function home(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/home.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/add/user", name="add_user", methods={"POST","GET"})
     *
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function add(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $plaintextPassword = $newUser->getPassword();
            $hashedPassword = $hasher->hashPassword(
                $newUser,
                $plaintextPassword
            );
            $newUser->setPassword($hashedPassword);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($newUser);
            $entityManager->flush();

            return $this->redirectToRoute('list_user');
        } else {
            return $this->render('user/add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }   

    /**
    * @Route("/update/user/{id}", name="update_user", methods={"POST","GET"}, requirements={"id"="\d+"})
    *
    * @param UserRepository $userRepository
    * @param User $user
    * @param Request $request
    * @return void
    */
    public function updateUser(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {   
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $user->getPassword();
            $hashedPassword = $hasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $userRepository->add($user, true);

            return $this->redirectToRoute('list_user');
        }
        return $this->render('user/update.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/user/{id}", name="delete_user")
     *
     * @param [type] $id
     * @param ManagerRegistry $doctrine
     * @param UserRepository $userRepository
     * @return Response
     */
    public function delete($id, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        $user = $userRepository->find($id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('list_user');
    }
}
