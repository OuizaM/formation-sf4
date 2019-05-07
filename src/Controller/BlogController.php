<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CarType;
use App\Form\RegistrationType;
use App\Repository\CarRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\ImageHandler;
use App\Entity\Car;
use App\Entity\Image;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class BlogController extends AbstractController
{


    /**
     * @Route("/inscription", name="security_registration")

     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user=new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'notice',
                'inscription réussite!!'
            );

            return $this->redirectToRoute('security_login');
        }

        return $this->render('registration.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('login.html.twig');
    }


    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {

    }

    

    /**
     * @Route("/", name="blog")
     * @param CarRepository $carRepository
     * @return Response
     */
    public function index(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'cars' => $cars,
        ]);
    }


    /**
     * @Route("/add", name="add")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return RedirectResponse|ResponseAlias
     */
    public function add(EntityManagerInterface $manager, Request $request)
    {


        $form = $this->createForm(CarType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();


           //$path=$this->getParameter('kernel.project_dir').'/public/images';

          //    $image=$car->getImage();

             // $file=$image->getFile();

              // $name=md5(uniqid()).'.'.$image->guessExtension();

                //$file->move('../',$name);
              //  $image->setName($name);

//-----------------------------------------------



            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Une voiture est Ajoutée!!'
            );

            return $this->redirectToRoute('blog');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/show/{id}", name="show")
     * @param Car $car
     * @return Response
     */
    public function show(Car $car)
    {

        return $this->render('show.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route("/editt/{id}", name="editt")
     * @param Car $car
     * @param EntityManagerInterface $manager
     * @return ResponseAlias
     */
    public function edit(Car $car, EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash(
                'notice',
                'La voiture est bien modifiée!!'
            );

            return $this->redirectToRoute('blog');

        }
            return $this->render('editt.html.twig', [
                'car' => $car,
                'form' => $form->createView()
            ]);



    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @param Car $car
     * @param EntityManagerInterface $manager

     * @return ResponseAlias
     */
    public function delete(Car $car, EntityManagerInterface $manager)
    {

            $manager->remove($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'La voiture est bien supprimée!!'
            );

            return $this->redirectToRoute('blog');


        return $this->render('delete.html.twig', [
            'car' => $car,
            'form' => $form->createView()
        ]);



    }





}
