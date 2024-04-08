<?php

namespace App\Controller;

use App\Entity\Birthday;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BirthdayController extends AbstractController
{
    #[Route('/birthday', name: 'app_birthday', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->findAll();
        $serializerAll = $serializer->serialize($birthday, 'json');
        return new Response($serializerAll);
    }

    #[Route('/birthday/{id}', name: 'app_birthday', methods: ['GET'])]
    public function getById(ManagerRegistry $doctrine, SerializerInterface $serializer, $id): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->findOneBy(['id' => $id]);
        $serializerAll = $serializer->serialize($birthday, 'json');
        return new Response($serializerAll);
    }

    #[Route('/birthday', name: 'app_birthday_add', methods: ['POST'])]
    public function add(EntityManagerInterface $em, SerializerInterface $serializer, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $birthday = new Birthday();
        $birthday->setName($data['name']);
        $birthday->setBirthday(new \DateTime($data['birthday']));

        $em->persist($birthday);
        $em->flush();

        $jsonBirthday = $serializer->serialize($birthday, 'json');

        return new Response($jsonBirthday);
    }

    #[Route('/birthday/{id}', name: 'app_birthday_edit', methods: ['PATCH'])]
    public function edit(EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, Request $request, $id): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), $birthday);

        return new Response($data);

        // $birthday->setName($data['name']);
        // $birthday->setBirthday(new \DateTime($data['birthday']));

        // $em->persist($birthday);
        // $em->flush();

        // $jsonBirthday = $serializer->serialize($birthday, 'json');

        // return new Response($jsonBirthday);
    }

    #[Route('/birthday/{id}', name: 'app_birthday_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, EntityManagerInterface $manager, $id): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->findOneBy(['id' => $id]);
        $manager->remove($birthday);
        $manager->flush();

        return new Response("Suppression validé !");
    }
}
