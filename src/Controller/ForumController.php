<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForumController extends AbstractController
{
    /**
     * @Route("/", name="forum")
     */
    public function index(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHash): Response
    {

        if (isset($_POST['submit'])) {
            
        $user = new User();
        $user->setEmail($_POST['email'])
        ->setRoles(array('ROLE_USER'))
        ->setPassword($passwordHash->hashPassword($user, $_POST['password']));
        $em->persist($user);
        $em->flush();
        }


        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
