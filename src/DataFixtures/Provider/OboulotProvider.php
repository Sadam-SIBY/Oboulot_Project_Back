<?php

namespace App\DataFixtures\Provider;

class OboulotProvider
{

    private $groups = [
        'Abricot',
        'Airelle',
        'Alkékenge',
        'Amande',
        'Amélanche',
        'Ananas',
        'Arbouse',
        'Argouse',
        'Asimine',
        'Avocat',
        'Banane',
        'Bergamote',
        'Cassis',
        'Cerise',
        'Châtaigne',
        'Citron',
        'Clémentine',
        'Cynorrhodon',
        'Datte',
        'Mûre',
        'Myrte',
        'Noisette',
        'Figue',
        'Figue de barbarie',
        'Fraise',
        'Framboise',
        'Grenade',
        'Olive',
        'Orange',
        'Raisin' 
    ];

    private $level = [
        'Sixième',
        'Cinquième',
        'Quatrième',
        'Troisième',
        'Seconde',
        'Première',
        'Terminale',
        'Licence',
        'Master',
    ];





    public function group_rand()
    {

        $rand = rand(0, 29);

        return $this->groups[$rand];
    }


    public function level_rand()
    {

        $rand = rand(0, 8);

        return $this->level[$rand];
    }


}