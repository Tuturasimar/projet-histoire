<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Character;
use App\Entity\Choice;
use App\Entity\Inventory;
use App\Entity\InventorySlot;
use App\Form\CharacterCreationFormType;
use App\Repository\ArchetypeRepository;
use App\Repository\ChapterRepository;
use App\Repository\CharacterRepository;
use App\Repository\ChoiceRepository;
use App\Repository\InventoryRepository;
use App\Repository\InventorySlotRepository;
use App\Repository\ItemRepository;
use App\Repository\PjRepository;
use App\Repository\SceneRepository;
use App\Service\Character\CharacterService;
use App\Service\Character\ChoicesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoryController extends AbstractController
{

    /**
     * @Route("/", name="app_home_page")
     */

    public function homePage()
    {
        return $this->render('story/homepage.html.twig');
    }


    /**
     * @Route("/story", name="app_story")
     */
    public function index(CharacterRepository $charaRepo, ChapterRepository $chapterRepo, RequestStack $rs): Response
    {
        if($this->getUser())
        {
            $characters = $charaRepo->getAllCharactersByUser($this->getUser());
            $creation = true;
            if(count($characters) >= 6){
                $creation = false;
            }
            $titles = [];
            foreach ($characters as $character) {
                $chapters = explode('::',$character->getStoryDone());
                $chapter = explode(';;', end($chapters));
                $title = $chapterRepo->findChapterByLabel($chapter[0])[0]->getTitle();
                $position = strpos($title,":");
                $title = substr($title,$position+2);
                array_push($titles,$title);
            }
            return $this->render('story/index.html.twig', [
                'data' => [
                    'characters' => $characters,
                    'titles' => $titles,
                ],
                'creation' => $creation
            ]);
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/story/chapters/{id}", name="app_chapters")
     */

    public function showChapters(Character $character = null, CharacterRepository $characRepo, ChapterRepository $chapterRepo)
    {
        if($this->getUser())
        {
            if($character){
                $characters = $characRepo->findBy(array('user' => $this->getUser(), 'id' => $character->getId()));
                if($characters){
                    $stories = explode('::',$character->getStoryDone());
                    $chapters = [];
                    foreach($stories as $story){
                        $story = explode(';;',$story);
                        array_push($chapters,$chapterRepo->findBy(array('label' => $story[0])));
                    }
        
                    return $this->render('story/chapters.html.twig', [
                        'chapters' => $chapters,
                        'character' => $character
                    ]);
                }
            }
            
            $this->addFlash('danger','Ce personnage est indisponible');
            return $this->redirectToRoute('app_story');
        }
        else
        {
            $this->addFlash('danger','Connexion requise');
            return $this->redirectToRoute('app_login');
        }
        
    }

    /**
     * @Route("/story/new", name="app_new_character")
     */

    public function newCharacter(Request $request, EntityManagerInterface $manager, ArchetypeRepository $aRepo, 
                                PjRepository $pjRepo, CharacterService $characService, CharacterRepository $charaRepo)
    {
        if($this->getUser())
        {
            $characters = $charaRepo->getAllCharactersByUser($this->getUser());
            if(count($characters) < 6){
                $character = new Character;
                $character->setUser($this->getUser());
                $character->setInventory(new Inventory);
                $character->setAlignment(0);
                $form = $this->createForm(CharacterCreationFormType::class, $character);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $characService->characterCreation($character);
                    $manager->persist($character);
                    $manager->flush();
                    $this->addFlash('success',"Personnage créé avec succès.");
                    return $this->redirectToRoute('app_story');
                }
                return $this->render("story/new-character.html.twig", [
                    'formCharacter' => $form->createView(),
                    'archetypes' => $aRepo->findAll(),
                    'histoires' => $pjRepo->findAll()
                ]);
            } else {
                $this->addFlash('danger','Nombre limite de personnage atteint');
                return $this->redirectToRoute('app_story');
            }
        }
        else
        {
            $this->addFlash('danger','Connexion requise');
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("story/delete/{id}", name="app_delete_character")
     */

    public function deleteCharacter(EntityManagerInterface $manager, Character $character = null, CharacterRepository $characRepo, InventoryRepository $inventoryRepo, InventorySlotRepository $inventorySlotRepo)
    {
        if($this->getUser())
        {
            if($character){
                $toDeleteCharacter = $characRepo->findBy(array('user' => $this->getUser(), 'id' => $character->getId()));
                if($toDeleteCharacter){
                    $inventory = $inventoryRepo->findBy(array('charact' => $toDeleteCharacter));
                    $inventorySlots = $inventorySlotRepo->findBy(array('inventory' => $inventory));
                    foreach ($inventorySlots as $slot){
                        $manager->remove($slot);
                    }
                    $manager->flush();
    
                    $manager->remove($character);
                    $manager->remove($inventory[0]);
                    $manager->flush();
    
                    $this->addFlash('success','Personnage supprimé avec succès');
                    return $this->redirectToRoute('app_story');
                }
    
            }
                $this->addFlash('danger','Personnage introuvable');
                return $this->redirectToRoute('app_story');
        }
        else
        {
            $this->addFlash('danger',"Connexion requise");
            return $this->redirectToRoute('app_login');
        }
        
        

        // if($character || )
    }

    /**
     * @Route("/story/read/{id}/{chapter}", name="app_read_chapters")
     */

    public function readChapters(Character $character = null, Chapter $chapter = null , SceneRepository $sceneRepo,ChoiceRepository $choiceRepo, 
                                CharacterRepository $characRepo, ChapterRepository $chapterRepo, ChoicesService $choicesService)
    {
        if($this->getUser())
        {
            $characters = $characRepo->findBy(array('user' => $this->getUser()));
            foreach($characters as $userCharacter){
                if($character && $userCharacter->getId() == $character->getId() && $chapter){
                    $stories = explode('::',$character->getStoryDone());
                    $nextChapter = [];
                    $isClosed = true;
                    $content = [];
                    $index = 0;
                    $storyContent = [];
                    foreach($stories as $iteration => $story){
                        $index= $iteration + 1;
                        $story = explode(';;',$story);
                        array_push($storyContent,$chapterRepo->findChapterByLabel($story[0]));
                        foreach($story as $key => $values){
                        if($story[0] == $chapter->getLabel()){
                            if($iteration == count($stories) - 1){
                                $isClosed = false;
                            } else {
                                $nextChapter = explode(';;',$stories[$index]);
                                $nextChapter = $chapterRepo->findBy(array('label' => $nextChapter[0]))[0];
                            }
                            if(strpos($values,'-SC-')){
                                array_push($content,$sceneRepo->findBy(array('label' => $values)));
                            } else if(strpos($values,'-CH-')){
                                array_push($content,$choiceRepo->findBy(array('label' => $values)));
                                }
                            } 
                        if(strpos($values,'-CH-')){
                            array_push($storyContent,$choiceRepo->findBy(array('label' => $values)));
                        }
                        }
                    }
                    if($content) {
                        $lastChoices = $choiceRepo->findBy(array('scene' => end($content)[0]), array('label' => 'ASC'));
                        $lastContent = $choicesService->checkConstraints($lastChoices,$character);
                    return $this->render("story/read-chapters.html.twig", array(
                        'content' => $content,
                        'choices' => $lastContent,
                        'character' => $character,
                        'isClosed' => $isClosed,
                        'nextChapter' => $nextChapter,
                        'storyContent' => $storyContent
                    ));
                    }
                }
            }
            $this->addFlash('danger','Personnage introuvable');
            return $this->redirectToRoute('app_story');
        }
        else
        {
            $this->addFlash('danger',"Connexion requise");
            return $this->redirectToRoute('app_login');
        }
        
    }

    /**
     * @Route("/story/validate/choice/{id}/{character}", name="app_choices")
     */

    public function validateChoice(Choice $choice = null, Character $character = null , EntityManagerInterface $manager, CharacterRepository $characRepo, ChoiceRepository $choiceRepo, 
    SceneRepository $sceneRepo, ChapterRepository $chapterRepo, ChoicesService $choiceService, ItemRepository $itemRepo, InventorySlotRepository $inventorySlotRepo){
        if($this->getUser())
        {
            $characters = $characRepo->findBy(array('user' => $this->getUser()));
            foreach($characters as $userCharacter){
                if($choice && $character && $userCharacter->getId() == $character->getId()){
                    $storyDone = $character->getStoryDone();
                    $story = explode('::',$storyDone);
                    $lastStoryLabel = explode(';;',end($story));
                    $lastStory = $sceneRepo->findBy(array('label' => end($lastStoryLabel)));
                    $possibleChoices = $choiceRepo->findBy(array('scene' => end($lastStory)), array('label' => 'ASC'));
                    foreach($possibleChoices as $possibleChoice){
                        if($choice->getLabel() == $possibleChoice->getLabel()){
                            $validateChoice = $choiceService->checkValidationConstraintChoice($choice,$character);
                            if($validateChoice){
                            $actualChoice = $choice->getLabel();
                            $nextStory = $choice->getNextStory();
                            $effects= $choice->getEffect();
                            if($effects){
                                $effect = explode(';;',$effects);
                                foreach($effect as $key => $values){
                                    $array = explode('::',$values);
                                    if($array[0] == 'alignment'){
                                        $character->setAlignment($character->getAlignment() + $array[1]);
                                    } else if ($array[0] == 'pv'){
                                        $character->setPv($character->getPv() + $array[1]);
                                    } else if($array[0] == 'objet'){
                                        $objetDetails = explode(',',$array[1]);
                                        $item = $itemRepo->findBy(array('name' => $objetDetails[0]));
                                    
                                        $itemSlot = $inventorySlotRepo->findBy(array('item' => $item, 'inventory' => $character->getInventory()));
                                        if(!$itemSlot){
                                            $itemSlot = new InventorySlot();
                                            $itemSlot->setInventory($character->getInventory());
                                            $itemSlot->setItem($item[0]);
                                            $itemSlot->setQuantity($objetDetails[1]);
                                        } else {
                                            $itemSlot[0]->setQuantity($itemSlot[0]->getQuantity() + $objetDetails[1]);
                                        }
                                        $manager->persist($itemSlot);
                                        $manager->flush();
                                    }
                                }   
                            }
                            $storyDone .= ";;$actualChoice;;$nextStory";
                            $chapter = $chapterRepo->findBy(array('label' => $lastStoryLabel[0]));
                            $character->setStoryDone($storyDone);
                            $manager->persist($character);
                            $manager->flush();
                            return $this->redirectToRoute('app_read_chapters', array('id' => $character->getId(), 'chapter' => $chapter[0]->getId(),  '_fragment' => 'ancre'));
                            }
                        }
                    }  
                }
            }
            $this->addFlash('danger','Choix indisponible pour cette scène.');
            return $this->redirectToRoute('app_story');
        }
        else
        {
            $this->addFlash('danger',"Connexion requise");
            return $this->redirectToRoute('app_login');
        }
        
    }

    /**
     * @Route("/story/validate/chapter/{character}", name="app_endChapter")
     */

    public function validateChapter(Character $character = null , ChapterRepository $chapterRepo, EntityManagerInterface $manager, 
    SceneRepository $sceneRepo, CharacterRepository $characRepo){

        if($this->getUser())
        {
            $characters = $characRepo->findBy(array('user' => $this->getUser()));
            foreach($characters as $userCharacter){
                if($character && $userCharacter->getId() == $character->getId()){
                    $storyDone = $character->getStoryDone();
                    $chapter = explode('::',$storyDone);
                    $chapter = explode(';;', end($chapter));
                    $trigger = $chapterRepo->findTrigger(end($chapter));
                    if($trigger){
                        $chapterData = $trigger[0];
                        $nextChapter = $chapterData->getLabel();
                        $nextScene = $sceneRepo->findBy(array('chapter' => $chapterData))[0]->getLabel();
                        $storyDone .= "::$nextChapter;;$nextScene";
                        $character->setStoryDone($storyDone);
                        $manager->persist($character);
                        $manager->flush();
                        return $this->redirectToRoute('app_chapters', ['id' => $character->getId()]);
                    }
                }
            }
            $this->addFlash('danger',"Ce chapitre ne peut être clôturé.");
            return $this->redirectToRoute('app_story');
        }
        else
        {
            $this->addFlash('danger',"Connexion requise");
            return $this->redirectToRoute('app_login');
        }
    }
}
