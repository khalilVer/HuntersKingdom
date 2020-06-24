<?php

namespace HuntersKingdomBundle\Controller;

use HuntersKingdomBundle\Entity\produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;


/**
 * Produit controller.
 *
 * @Route("produit")
 */
class produitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/all", name="produit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('HuntersKingdomBundle:produit')->findAll();

        $data=$this->get('jms_serializer')->serialize($produits,'json');
        $response = new Response($data);
        return $response;
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new", name="produit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        //récupérer le contenu de la requête envoyé par l'outil postman
        $data = $request->getContent();
        //deserialize data: création d'un objet 'produit' à partir des données json envoyées
        $produit = $this->get('jms_serializer') ->deserialize($data, 'HuntersKingdomBundle\Entity\produit', 'json');
        //ajout dans la base
        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        return new View("Product Added Successfully", Response::HTTP_OK);
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{reference}", name="produit_show")
     * @Method("GET")
     */
    public function showAction(produit $produit)
    {
        $data=$this->get('jms_serializer')->serialize($produit,'json');
        $response=new Response($data);
        return $response;
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{reference}/edit", name="produit_edit")
     * @Method({"PUT", "POST"})
     */
    public function editAction(Request $request, produit $produit)
    {
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository('HuntersKingdomBundle:produit')->find($produit->getId());
        $data=$request->getContent();
        $newdata=$this->get('jms_serializer')->deserialize($data,'HuntersKingdomBundle\Entity\produit','json');
        if($newdata->getPrix() != null) {
            $produit->setPrix($newdata->getPrix());
        }
        if($newdata->getDescription() != null) {
            $produit->setDescription($newdata->getDescription());
        }
        if($newdata->getLibelle() != null) {
            $produit->setLibelle($newdata->getLibelle());
        }
        if($newdata->getCategorie() != null) {
            $produit->setCategorie($newdata->getCategorie());
        }
        $em->persist($produit);
        $em->flush();
        return new View("Product Modified Successfully", Response::HTTP_OK);
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}", name="produit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, produit $produit)
    {
        $em=$this->getDoctrine()->getManager();
        $p=$em->getRepository('HuntersKingdomBundle:produit')->find($produit->getId());
        $em->remove($p);
        $em->flush();
        return new View("Product Deleted Successfully", Response::HTTP_OK);
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}