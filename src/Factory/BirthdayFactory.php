<?php

namespace App\Factory;

use App\Entity\Birthday;
use App\Repository\BirthdayRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Birthday>
 *
 * @method        Birthday|Proxy                     create(array|callable $attributes = [])
 * @method static Birthday|Proxy                     createOne(array $attributes = [])
 * @method static Birthday|Proxy                     find(object|array|mixed $criteria)
 * @method static Birthday|Proxy                     findOrCreate(array $attributes)
 * @method static Birthday|Proxy                     first(string $sortedField = 'id')
 * @method static Birthday|Proxy                     last(string $sortedField = 'id')
 * @method static Birthday|Proxy                     random(array $attributes = [])
 * @method static Birthday|Proxy                     randomOrCreate(array $attributes = [])
 * @method static BirthdayRepository|RepositoryProxy repository()
 * @method static Birthday[]|Proxy[]                 all()
 * @method static Birthday[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Birthday[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Birthday[]|Proxy[]                 findBy(array $attributes)
 * @method static Birthday[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Birthday[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class BirthdayFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'birthday' => self::faker()->date(),
            'name' => self::faker()->name(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Birthday $birthday): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Birthday::class;
    }
}
