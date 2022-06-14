<?php

namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Item;
use App\Entity\Scene;
use App\Entity\User;
use App\Form\AdminChoicesType;
use App\Form\AdminItemsType;
use App\Form\AdminScenesType;
use App\Form\AdminUsersType;
use App\Form\RechercheType;
use App\Repository\ChoiceRepository;
use App\Repository\ItemRepository;
use App\Repository\SceneRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */

    public function adminUsers(UserRepository $userRepo, EntityManagerInterface $manager){
        $colonnes = $manager->getClassMetadata(User::class)->getFieldNames();
        $users = $userRepo->findAll();

        return $this->render("admin/admin-users.html.twig", [
            'colonnes' => $colonnes,
            'users' => $users
        ]);
    }

    /**
     * @Route("admin/user/edit/{id}", name="admin_user_edit")
     */

    public function formUser(EntityManagerInterface $manager, User $user = null, Request $request)
    {
        if(!$user)
        {
            $this->addFlash('danger','Cet utilisateur est inexistant.');
            return $this->redirectToRoute('admin_users');
        } 
        else 
        {
            $form = $this->createForm(AdminUsersType::class, $user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success','Utilisateur modifié avec succès');

                return $this->redirectToRoute('admin_users');
            }

            return $this->render("admin/form_user.html.twig", [
                'formUser' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     */

    public function adminUserDelete(User $user = null, EntityManagerInterface $manager)
    {
        if(!$user)
        {
            $this->addFlash('danger',"Cet utiliseur n'existe pas.");
            return $this->redirectToRoute('admin_users');
        } else {
            $pseudo = $user->getPseudo();
            $manager->remove($user);
            $manager->flush();

            $this->addFlash('success',"L'utilisateur $pseudo a bien été supprimé.");
            return $this->redirectToRoute('admin_users');
        }
    }

    /**
     * @Route("/admin/scenes", name="admin_scenes")
     */

    public function adminScenes(SceneRepository $sceneRepo, EntityManagerInterface $manager, Request $request){

        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $saisie = $form->get('recherche')->getData();
            $scenes = $sceneRepo->getLikeSceneByLabel($saisie);

            if(!$scenes)
            {
                $this->addFlash('danger',"Aucune scène disponible avec ce filtre.");
                return $this->redirectToRoute('admin_scenes');
            }
        }
        else 
        {
            $scenes = $sceneRepo->findAll();
        }

        $colonnes = $manager->getClassMetadata(Scene::class)->getFieldNames();

        return $this->render("admin/admin-scenes.html.twig", [
            'colonnes' => $colonnes,
            'scenes' => $scenes,
            'rechercheForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/scene/edit/{id}", name="admin_scene_edit")
     */

    public function formScene(EntityManagerInterface $manager, Scene $scene = null, Request $request)
    {
        if(!$scene)
        {
            $this->addFlash('danger','Cette scène est inexistante.');
            return $this->redirectToRoute('admin_scenes');
        }
        else
        {
            $form = $this->createForm(AdminScenesType::class, $scene);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($scene);
                $manager->flush();
                $this->addFlash('success',"Scène corrigée avec succès");
                return $this->redirectToRoute('admin_scenes');
            }

            return $this->render("admin/form_scene.html.twig", [
                'formScene' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("admin/choices", name="admin_choices")
     */

    public function adminChoices(ChoiceRepository $choiceRepo, EntityManagerInterface $manager, Request $request){

        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $saisie = $form->get('recherche')->getData();
            $choices = $choiceRepo->getChoiceByLabel($saisie);

            if(!$choices)
            {
                $this->addFlash('danger',"Aucun choix dispnible avec ce label.");
                return $this->redirectToRoute("admin_choices");
            }
        }
        else
        {
            $choices = $choiceRepo->findAll();
        }

        $colonnes = $manager->getClassMetadata(Choice::class)->getFieldNames();

        return $this->render("admin/admin-choices.html.twig", [
            'colonnes' => $colonnes,
            'choices' => $choices,
            'rechercheForm' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/choice/edit/{id}", name="admin_choice_edit")
     */

    public function formChoice(EntityManagerInterface $manager, Choice $choice = null, Request $request)
    {
        if(!$choice)
        {
            $this->addFlash('danger','Ce choix est inexistant.');
            return $this->redirectToRoute('admin_choices');
        }
        else
        {
            $form = $this->createForm(AdminChoicesType::class, $choice);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($choice);
                $manager->flush();
                $this->addFlash('success',"Choix corrigé avec succès");
                return $this->redirectToRoute('admin_choices');
            }

            return $this->render("admin/form_choice.html.twig", [
                'formChoice' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("admin/items", name="admin_items")
     */

    public function adminItem(ItemRepository $itemRepo, EntityManagerInterface $manager )
    {
        $colonnes = $manager->getClassMetadata(Item::class)->getFieldNames();
        $items = $itemRepo->findAll();

        return $this->render("admin/admin-items.html.twig", [
            'colonnes' => $colonnes,
            'items' => $items
        ]);
    }

    /**
     * @Route("admin/item/edit/{id}", name="admin_item_edit")
     */

    public function formItem(Item $item = null, EntityManagerInterface $manager, Request $request)
    {
        if(!$item)
        {
            $this->addFlash('danger','Cet objet est inexistant.');
            return $this->redirectToRoute('admin_items');
        }
        else
        {
            $form = $this->createForm(AdminItemsType::class, $item);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($item);
                $manager->flush();
                $this->addFlash('success',"Objet corrigé avec succès");
                return $this->redirectToRoute('admin_items');
            }

            return $this->render("admin/form_item.html.twig", [
                'formItem' => $form->createView()
            ]);
        }
    }
}
