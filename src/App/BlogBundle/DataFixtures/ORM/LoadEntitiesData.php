<?php

namespace App\BlogBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Faker\Factory;

/**
 * テストデータ投入処理
 */
class EntitiesDataFixtureLoader extends DataFixtureLoader
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ja_JP');
    }

    /**
     * YAMLを読み込んでテストデータを投入する
     */
    protected function getFixtures()
    {
        return array(
            __DIR__ . '/../../Resources/fixtures/posts.yml',
        );
    }
}
