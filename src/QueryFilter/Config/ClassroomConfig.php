<?php


namespace App\QueryFilter\Config;


use Artprima\QueryFilterBundle\QueryFilter\Config\BaseConfig;

class ClassroomConfig extends BaseConfig
{
    public function __construct()
    {
        $this->setSearchAllowedCols([
            'o.name',
        ]);

        $this->setSortCols(
            [
                'o.id',
            ],
            ['o.id' => 'desc'] // default
        );
    }
}