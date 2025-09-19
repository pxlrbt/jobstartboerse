<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

function getModels(): Collection
{
    $autoload = include \base_path('/vendor/composer/autoload_classmap.php');

    $models = collect($autoload)
        ->map(fn ($path, $className) => $className)
        ->filter(fn ($class) => str_starts_with($class, 'App\\') || str_starts_with($class, 'Domain\\'))
        ->filter(function ($class) {
            if (class_exists($class)) {
                $reflection = new ReflectionClass($class);

                return $reflection->isSubclassOf(Model::class) && ! $reflection->isAbstract();
            }

            return false;
        });

    return $models->values();
}

test('all models have morph map', function () {
    $map = Relation::morphMap();

    $models = getModels();
    $models->each(fn ($className) => expect($map)->toContain($className));
});
