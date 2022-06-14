<?php

namespace App\Service\Character;

class CharacterService
{
    public function characterCreation($character){
        $character->setPv($character->getArchetype()->getPv() + $character->getPj()->getPv());
        $character->setPm($character->getArchetype()->getPm() + $character->getPj()->getPm());
        $character->setStrength($character->getArchetype()->getStrength() + $character->getPj()->getStrength());
        $character->setAgility($character->getArchetype()->getAgility() + $character->getPj()->getAgility());
        $character->setIntelligence($character->getArchetype()->getIntelligence() + $character->getPj()->getIntelligence());
        if($character->getPj()->getName() == "Agatha Burnwood") {
            $character->setStoryDone('AG-CH1;;AG-SC-01a');
        } else if ($character->getPj()->getName() == "Valdwyn d'Aprecourt") {
            $character->setStoryDone('VA-CH1;;VA-SC-01a');
        } else if ($character->getPj()->getName() == "Alastor") {
            $character->setStoryDone('AL-CH1;;VA-SC-01a');
        }
    }
}