<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'security_registration')]
    public function registration(Request $request){
        $user=new User();

        $form =$this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $Manager = $this->getDoctrine()->getManager();


            $Manager->persist($user);
            $Manager->flush();

        }

        return $this->render('security/registration.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
