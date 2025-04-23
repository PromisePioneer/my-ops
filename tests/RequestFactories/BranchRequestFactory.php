<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class BranchRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->unique()->numberBetween(1, 999),
            'address' => $this->faker->address,
            'parent_id' => null,
        ];
    }
}
