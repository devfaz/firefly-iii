<?php
/*
 * StoreControllerTest.php
 * Copyright (c) 2021 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Tests\Api\Models\TransactionLink;


use Faker\Factory;
use Laravel\Passport\Passport;
use Log;
use Tests\TestCase;
use Tests\Traits\CollectsValues;
use Tests\Traits\RandomValues;
use Tests\Traits\TestHelpers;

/**
 * Class StoreControllerTest
 */
class StoreControllerTest extends TestCase
{
    use RandomValues, TestHelpers, CollectsValues;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs($this->user());
        Log::info(sprintf('Now in %s.', get_class($this)));
    }


    /**
     * @param array $submission
     *
     * emptyDataProvider / storeDataProvider
     *
     * @dataProvider storeDataProvider
     */
    public function testStore(array $submission): void
    {
        if ([] === $submission) {
            $this->markTestSkipped('Empty data provider');
        }
        $route = 'api.v1.transaction_links.store';
        $this->storeAndCompare($route, $submission);
    }

    /**
     * @return array
     */
    public function emptyDataProvider(): array
    {
        return [[[]]];

    }

    /**
     * @return array
     */
    public function storeDataProvider(): array
    {
        $minimalSets  = $this->minimalSets();
        $optionalSets = $this->optionalSets();
        $regenConfig  = [
            'inward_id'  => function () {
                $faker = Factory::create();

                return (string)$faker->numberBetween(1, 10);
            },
            'outward_id' => function () {
                $faker = Factory::create();

                return (string)$faker->numberBetween(11, 20);
            },
        ];

        return $this->genericDataProvider($minimalSets, $optionalSets, $regenConfig);
    }

    /**
     * @return array
     */
    private function minimalSets(): array
    {
        $faker = Factory::create();

        return [
            'default_link_id'   => [
                'parameters' => [],
                'fields'     => [
                    'link_type_id' => (string)$faker->numberBetween(1, 4),
                    'inward_id'    => (string)$faker->numberBetween(1, 10),
                    'outward_id'   => (string)$faker->numberBetween(11, 20),
                ],
            ],
            'default_link_name' => [
                'parameters' => [],
                'fields'     => [
                    'link_type_name' => 'Related',
                    'inward_id'      => (string)$faker->numberBetween(1, 10),
                    'outward_id'     => (string)$faker->numberBetween(11, 20),
                ],
            ],
        ];
    }


    /**
     * @return \array[][]
     */
    private function optionalSets(): array
    {
        $faker = Factory::create();

        return [
            'notes' => [
                'fields' => [
                    'notes' => join(' ', $faker->words(5)),
                ],
            ],
        ];
    }

}