<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Utilisateur;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AnnonceController extends AbstractController
{

    /**
     * @Route("/", name="accueil")
     */
    public function accueil()
    {
        return $this->render('annonce/index.html.twig');
    }


    /**
     * @Route("/publier", name="publier")
     */
    public function publier(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {

        if ($request->isMethod('POST')){

        if (!empty($request->request->get('nom'))){
            $utilisateur = new Utilisateur();

            $utilisateur->setNom($request->request->get('nom'));
            $utilisateur->setPrenom($request->request->get('prenom'));
            $utilisateur->setEmail($request->request->get('email'));
            $encode=$encoder->encodePassword($utilisateur,
                $request->request->get('password'));
            $utilisateur->setPassword($encode);
            $manager->persist($utilisateur);
            $this->addFlash('success', 'Votre compte a bien été créé');
        }


            $annonce=new Annonce();

            $annonceur=$this->getUser();

            $annonce->setAnnonceur($annonceur);
            $annonce->setCategorie($request->request->get('categorie'));
            $annonce->setTitre($request->request->get('titre'));
            $annonce->setContenu($request->request->get('contenu'));

            $manager->persist($annonce);


        $manager->flush();
        $this->addFlash('success', 'Votre annonce a bien été publiée');
        return $this->redirectToRoute('annonceliste');
        }


        return $this->render('annonce/annonce-publier.html.twig');

    }

    /**
     * @Route("/annonceliste", name="annonceliste")
     */
    public function annonce_liste(AnnonceRepository $annonceRepository)
    {
        $annonces=$annonceRepository->findAll();

        return $this->render('annonce/annonce-liste.html.twig', [
            "annonces"=>$annonces

        ]);
    }


    /**
     * @Route("/annonce/{id}", name="annonce")
     */
    public function annonce($id,AnnonceRepository $annonceRepository)
    {
        $annonce=$annonceRepository->find($id);

        return $this->render('annonce/annonce.html.twig',[
            'annonce'=>$annonce
        ]);
    }



}
