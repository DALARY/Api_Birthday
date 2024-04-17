<?php

namespace App\Controller;

use App\Entity\Birthday;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route("/api")]
class BirthdayController extends AbstractController
{
    #[Route('/birthday', name: 'app_birthday', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException('Vous devez être connecté pour accéder à cette ressource.');
        }
        $birthdays = $doctrine->getRepository(Birthday::class)->findBy(['user' => $user]);
        $endpointUrl = $urlGenerator->generate("app_birthday");

        $arrayData = [
            'birthdays' => $birthdays,
            'endpointUrl' => $endpointUrl,
        ];

        $serializerAll = $serializer->serialize($arrayData, 'json');
        return new Response($serializerAll);
    }

    #[Route('/birthday/{id}', name: 'app_birthday_id', methods: ['GET'])]
    public function getById(ManagerRegistry $doctrine, SerializerInterface $serializer, $id, UrlGeneratorInterface $urlGenerator): Response
    {
        $user = $this->getUser();
        $birthday = $doctrine->getRepository(Birthday::class)->findOneBy(['user' => $user, 'id' => $id]);
        $endpointUrl = $urlGenerator->generate("app_birthday_id", ['id' => $id]);

        $arrayData = [
            'birthday' => $birthday,
            'endpointUrl' => $endpointUrl,
        ];
        $serializerAll = $serializer->serialize($arrayData, 'json');
        return new Response($serializerAll);
    }

    #[Route('/birthday', name: 'app_birthday_add', methods: ['POST'])]
    public function add(EntityManagerInterface $em, SerializerInterface $serializer, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $birthday = new Birthday();
        $birthday->setName($data['name']);
        $birthday->setBirthday(new \DateTime($data['birthday']));
        $birthday->setUser($user);

        $em->persist($birthday);
        $em->flush();

        $endpointUrl = $urlGenerator->generate("app_birthday_add");

        $arrayData = [
            'birthday' => $birthday,
            'endpointUrl' => $endpointUrl,
        ];

        $jsonBirthday = $serializer->serialize($arrayData, 'json');

        return new Response($jsonBirthday);
    }

    #[Route('/birthday/{id}', name: 'app_birthday_edit', methods: ['PATCH'])]
    public function edit(EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, Request $request, $id, UrlGeneratorInterface $urlGenerator): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->findOneBy(['id' => $id]);
        $user = $this->getUser();
        if ($birthday->getUser() !== $user) {
            throw new AccessDeniedHttpException("Vous n'êtes pas autorisé à modifier cet anniversaire.");
        }
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $birthday->setName($data['name']);
        }

        if (isset($data['birthday'])) {
            $birthday->setBirthday(new \DateTime($data['birthday']));
        }

        $em->persist($birthday);
        $em->flush();

        $endpointUrl = $urlGenerator->generate("app_birthday_edit", ['id' => $id]);

        $arrayData = [
            'birthday' => $birthday,
            'endpointUrl' => $endpointUrl,
        ];

        $jsonBirthday = $serializer->serialize($arrayData, 'json');

        return new Response($jsonBirthday);
    }

    #[Route('/birthday/{id}', name: 'app_birthday_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, EntityManagerInterface $manager, $id, UrlGeneratorInterface $urlGenerator): Response
    {
        $user = $this->getUser();
        $birthday = $doctrine->getRepository(Birthday::class)->findOneBy(['user' => $user, 'id' => $id]);
        $manager->remove($birthday);
        $manager->flush();

        $endpointUrl = $urlGenerator->generate("app_birthday_delete", ['id' => $id]);

        return new Response("Suppression validé à cette url: ". $endpointUrl ." !");
    }
}
