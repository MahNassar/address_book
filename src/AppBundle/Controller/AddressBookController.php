<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Address;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Service\FileUploader;

class AddressBookController extends Controller
{
    private function getRepository()
    {
        return $this->getDoctrine()->getRepository(Address::class);
    }


    public function indexAction()
    {
        $address_items = $this->getRepository()->findAll();

        return $this->render('address_book/index.html.twig', [
            'address_items' => $address_items
        ]);
    }

    public function viewAction($id)
    {
        $address_item = $this->getRepository()->find($id);
        if (!$address_item) {
            $this->addFlash("error", "Address Not Found ");
            return $this->redirectToRoute("addresses_list");
        } else {
            return $this->render('address_book/edit.html.twig', [
                "address_item" => $address_item
            ]);
        }

    }

    public function updateAction($id, Request $request, FileUploader $fileUploader)
    {

        $address_item = $this->getRepository()->find($id);
        $inputs = $request->request->all();
        $file = $request->files->get("photo");
        $entityManager = $this->getDoctrine()->getManager();

        $inputs['imageName'] = $address_item->getImage();
        if ($file) {
            $imageName = $fileUploader->upload($file);
            $inputs['imageName'] = $imageName;
        }
        $this->setData($address_item, $inputs);

        $entityManager->flush();

        $this->addFlash("success", "Address Edit  Successfully ");

        return $this->redirectToRoute("addresses_list");
    }

    public function createAction(Request $request)
    {
        return $this->render('address_book/create.html.twig');
    }

    public function storeAction(Request $request, FileUploader $fileUploader)
    {
        $inputs = $request->request->all();
        $file = $request->files->get("photo");
        $imageName = "";
        if ($file) {
            $imageName = $fileUploader->upload($file);
        }
        $inputs["imageName"] = $imageName;
        $entityManager = $this->getDoctrine()->getManager();

        $address_book = new Address();

        $address_book = $this->setData($address_book, $inputs);

        $entityManager->persist($address_book);

        $entityManager->flush();

        $this->addFlash("success", "Address Add Successfully ");

        return $this->redirectToRoute("addresses_list");
    }

    public function deleteAction($id)
    {
        $address_item_deleted = $this->getRepository()->delete($id);
        if ($address_item_deleted) {
            $this->addFlash("success", "Address Deleted Successfully ");
        } else {
            $this->addFlash("error", "Some Error Happened");
        }

        return $this->redirectToRoute("addresses_list");
    }

    private function setData($address_book, $inputs)
    {
        $address_book->setFirstName($inputs["firstName"]);
        $address_book->setLastName($inputs["lastName"]);
        $address_book->setStreetNumber($inputs["streetNumber"]);
        $address_book->setBirthday($inputs["birthday"]);
        $address_book->setCity($inputs["city"]);
        $address_book->setCountry($inputs["country"]);
        $address_book->setPhoneNumber($inputs["phoneNumber"]);
        $address_book->setZip($inputs["zip"]);
        $address_book->setEmail($inputs["email"]);
        $address_book->setImage($inputs["imageName"]);

        return $address_book;
    }


}
