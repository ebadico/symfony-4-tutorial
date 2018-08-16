<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegisterController
 * @package App\Controller
 * a quicker way, doesn't  mean better.
 */
class RegisterController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->redirect('micro_post_index');
        }

        return $this->render(
            'register/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


}
