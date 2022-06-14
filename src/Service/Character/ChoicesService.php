<?php

namespace App\Service\Character;

use App\Entity\InventorySlot;
use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;

class ChoicesService {

    public function checkEffect($choice, $character){
        // $effects= $choice->getEffect();
        // if($effects){
        //     $effect = explode(';;',$effects);
        //     foreach($effect as $key => $values){
        //         $array = explode('::',$values);
        //         if($array[0] == 'alignment'){
        //             $character->setAlignment($character->getAlignment() + $array[1]);
        //         } else if ($array[0] == 'pv'){
        //             $character->setPv($character->getPv() + $array[1]);
        //         } else if($array[0] == 'objet'){
        //             $objetDetails = explode(',',$array[1]);
        //             dd($character->getInventory());
        //         }
        //     }   
        // }
    }

    public function checkConstraints($lastChoices, $character){
        $lastContent = [];
        foreach($lastChoices as $choice){
            if($choice->getConstraints() !== ""){
                $constraints = explode(";;",$choice->getConstraints());
                foreach($constraints as $constraint){
                    $constraint = explode('::',$constraint);
                    if($constraint[0] == "archetype" && $constraint[1] == strtolower($character->getArchetype()->getName())){
                        array_push($lastContent, $choice);
                    }
                } 
            } else {
                array_push($lastContent, $choice);
            }
        }
        return $lastContent;
    }

    public function checkValidationConstraintChoice($choice,$character){
        $validateChoice = false;
        if($choice->getConstraints() !== ""){
            $constraints = explode(';;',$choice->getConstraints());
            foreach($constraints as $constraint){
                $constraint = explode('::',$constraint);
                if($constraint[0] == 'archetype' && $constraint[1] == strtolower($character->getArchetype()->getName())){
                    $validateChoice = true;
                }
            }
        } else {
            $validateChoice = true;
        }
        return $validateChoice;
    }

}