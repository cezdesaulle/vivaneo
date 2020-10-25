<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     *
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {


        if ($request->isMethod('POST')){

            $utilisateur = new Utilisateur();


            $utilisateur->setNom($request->request->get('nom'));
            $utilisateur->setPrenom($request->request->get('prenom'));
            $utilisateur->setEmail($request->request->get('email'));
            $encode=$encoder->encodePassword($utilisateur,
                $request->request->get('password'));
            $utilisateur->setPassword($encode);
            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash('success', 'Votre compte a bien été créé');


            return $this->redirectToRoute('accueil');

        }

        return $this->render('utilisateur/inscription.html.twig', [
        ]);

    }

    /**
     * @Route("/tableaudebord", name="tableaudebord")
     */
    public function tableaudebord()
    {
        return $this->render('utilisateur/tableaudebord.html.twig');
    }

    /**
     * @Route("modifmail", name="modifmail")
     */
    public function mailmodif(AuthenticationUtils $authenticationUtils,UtilisateurRepository $repository, Request $request, EntityManagerInterface $manager)
    {
        $utilisateur=$repository->find($this->getUser()->getId());



            $utilisateur->setEmail($request->request->get('email'));
            $manager->persist($utilisateur);
            $manager->flush();
            $this->addFlash('success', 'Email modifié avec succès');
            return $this->redirectToRoute('tableaudebord');

    }

    /**
     * @Route("tableaumail", name="tableaumail")
     */
    public function tableaumail()
    {
        $utilisateur= $this->getUser();

        return $this->render("utilisateur/tableaudebord-email.html.twig",[
            "utilisateur"=>$utilisateur
        ]);
    }


    /**
     * @Route("modifidentite", name="modifidentite")
     */
    public function identitemodif(UtilisateurRepository $repository, Request $request, EntityManagerInterface $manager)
    {
        $utilisateur=$repository->find($this->getUser()->getId());



        $utilisateur->setNom($request->request->get('nom'));
        $utilisateur->setPrenom($request->request->get('prenom'));
        $manager->persist($utilisateur);
        $manager->flush();
        $this->addFlash('success', 'Nom et Prénom modifiés avec succès');
        return $this->redirectToRoute('tableaudebord');

    }

    /**
     * @Route("tableauidentite", name="tableauidentite")
     */
    public function tableauidentite()
    {
        $utilisateur= $this->getUser();

        return $this->render("utilisateur/tableaudebord-identite.html.twig",[
            "utilisateur"=>$utilisateur
        ]);
    }

    /**
     * @Route("modifpassword", name="modifpassword")
     */
    public function passwordmodif( UserPasswordEncoderInterface $encoder,UtilisateurRepository $repository, Request $request, EntityManagerInterface $manager)
    {
        $utilisateur=$repository->find($this->getUser()->getId());



        $encode=$encoder->encodePassword($utilisateur,
            $request->request->get('password'));
        $utilisateur->setPassword($encode);
        $manager->persist($utilisateur);
        $manager->flush();
        $this->addFlash('success', 'Mot de passe modifié avec succès');
        return $this->redirectToRoute('tableaudebord');

    }

    /**
     * @Route("tableaupassword", name="tableaupassword")
     */
    public function tableaupassword()
    {
        $utilisateur= $this->getUser();

        return $this->render("utilisateur/tableaudebord-motdepasse.html.twig",[
            "utilisateur"=>$utilisateur
        ]);
    }

}
