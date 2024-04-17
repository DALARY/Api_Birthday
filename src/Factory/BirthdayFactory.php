<?php

namespace App\Factory;

use App\Entity\Birthday;
use App\Entity\User;
use App\Repository\BirthdayRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        // Récupérer tous les IDs des utilisateurs existants
        $userRepository = $this->entityManager->getRepository(User::class);
        $userIds = $userRepository->createQueryBuilder('u')
            ->select('u.id')
            ->getQuery()
            ->getResult();

        // Extraire les IDs
        $ids = array_map(function ($user) {
            return $user['id'];
        }, $userIds);

        return [
            'user' => $this->faker()->randomElement($ids),
            'birthday' => $this->faker()->dateTime(),
            'name' => $this->faker()->name(),
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
