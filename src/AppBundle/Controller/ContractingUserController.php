<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\ContractStep1Type;
use AppBundle\Form\Type\ContractStep2Type;
use AppBundle\Form\Type\ContractStep3Type;
use AppBundle\Form\Type\ContractStep4Type;
use AppBundle\Form\Type\ContractDocumentType;
use AppBundle\Form\Type\ContractOfficerType;
use AppBundle\Form\Type\PreQualifiedType;
use AppBundle\Form\Type\BidEvaluationType;
use AppBundle\Form\Type\ContractingUserProfileType;
use AppBundle\Form\Type\ContractingUserChangePasswordType;
use AppBundle\Form\Type\ContractingUserRegistrationType;
use AppBundle\Form\Model\ContractingUserRegistration;
use AppBundle\Entity\Contract;
use AppBundle\Entity\ContractDocument;
use AppBundle\Entity\ContractOfficer;
use AppBundle\Entity\PreQualified;
use AppBundle\Entity\BidEvaluation;
use AppBundle\Entity\Audit;

class ContractingUserController extends Controller{
    
    const PROVIDER_KEY_CONTRACTING = 'contracting_secured_area';
   
    /**
     * @Route("/index", name="contracting_home")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function indexAction(){
       $authenticationUtils = $this->get('security.authentication_utils');
       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername(); 
       $engine = $this->container->get('templating');
       $content = $engine->render('@AppBundle/Resources/views/Contracting/index.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
       return $response = new Response($content);
    }
    
    /**
     * @Route("/contract/list", name="contract_notice_loader")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractListAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $contracts = $em->getRepository('AppBundle:Contract')
                        ->findBy(array(), array('updatedAt' => 'DESC')); 
        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize(array('data'=>$contracts), 'json');
        if ($contracts) {
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
                       $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Load Dossier");
          $em->persist($audit);
          $em->flush(); 
        }
       return  new Response($jsonContent);
    }
    /**
     * @Route("/contract/view", name="contract_notice_list")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractViewAction(){
       $engine = $this->container->get('templating');
       $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notices.html.twig');
       return $response = new Response($content);
    }
    /**
     * @Route("/contract/step1/create",name="contract_notice_step1_create")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractStep1CreateAction(Request $request) {
         $contractingUser = $this->get('security.token_storage')->getToken()->getUser();
         $contract= new Contract();
         $contract->setReferenceNumber(uniqid());
         $contract->setContractingUser($contractingUser);
         $contract->setStatus("Draft");
         $form = $this->createForm(new ContractStep1Type(), $contract);
         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $em = $this->getDoctrine()->getManager();
             $em->persist($data);
             $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Create notice step 1");
             $audit->setDossier($data);
             $em->persist($audit);
             $em->flush(); 
             if($form->get('save')->isClicked())
             {
                return $this->redirectToRoute('contract_notice_step1_edit',array('id'=>$contract->getId()),301);
             }else{
                return $this->redirectToRoute('contract_notice_step2_edit',array('id'=>$contract->getId()),301);
             }
         } 
        $engine = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_preliminary_question.html.twig',array('form' => $form->createView(),'contract' => $contract));
       return $response = new Response($content);
    }
    /**
     * @Route("/contract/{id}/step1/edit",name="contract_notice_step1_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractStep1EditAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
         if (!$contract) {
             throw $this->createNotFoundException(
                 'No contract found for id '.$id
              );
          }
         $form = $this->createForm(new ContractStep1Type(), $contract);
         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $em->persist($data);
             $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Edit notice step 1");
             $audit->setDossier($data);
             $em->persist($audit);
             $em->flush(); 
             if($form->get('save')->isClicked())
               {
                return $this->redirectToRoute('contract_notice_step1_edit',array('id'=>$id),301);
               }else{
                return $this->redirectToRoute('contract_notice_step2_edit',array('id'=>$id),301);
               }        
            } 
        $engine = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_preliminary_question.html.twig',array('form' => $form->createView(),'contract' => $contract));
       return $response = new Response($content);
    }
    
    /**
     * @Route("/contract/{id}/step2/edit",name="contract_notice_step2_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractStep2EditAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
         if (!$contract) {
             throw $this->createNotFoundException(
                 'No contract found for id '.$id
              );
          }
         $form = $this->createForm(new ContractStep2Type(), $contract);
         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Edit notice step 2");
             $audit->setDossier($data);
             $em->persist($audit);
             $em->flush();
           if($form->get('previousStep')->isClicked())
             {
                return $this->redirectToRoute('contract_notice_step1_edit',array('id'=>$id),301);
             }elseif ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('contract_notice_step2_edit',array('id'=>$id),301);
             }else{
                return $this->redirectToRoute('contract_notice_step3_edit',array('id'=>$id),301);
             }
        } 
        $engine = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_section_I.html.twig',array('form' => $form->createView(),'contract' => $contract));
       return $response = new Response($content);
    }
    /**
     * @Route("/contract/{id}/step3/edit",name="contract_notice_step3_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractStep3EditAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
         if (!$contract) {
             throw $this->createNotFoundException(
                 'No contract found for id '.$id
              );
          }
         $form = $this->createForm(new ContractStep3Type(), $contract);
         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $em->persist($data);
             $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Edit notice step 3");
             $audit->setDossier($data);
             $em->persist($audit);
             $em->flush();
             if($form->get('previousStep')->isClicked())
             {
                return $this->redirectToRoute('contract_notice_step2_edit',array('id'=>$id),301);
             }elseif ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('contract_notice_step3_edit',array('id'=>$id),301);
             }else{
                return $this->redirectToRoute('contract_notice_step4_edit',array('id'=>$id),301);
             }
        }
        $engine = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_section_II.html.twig',array('form' => $form->createView(),'contract' => $contract));
       return $response = new Response($content);
    }  
    /**
     * @Route("/contract/{id}/step4/edit",name="contract_notice_step4_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractStep4EditAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
         if (!$contract) {
             throw $this->createNotFoundException(
                 'No contract found for id '.$id
              );
          }
          $originalCreterion = new ArrayCollection();
          // Create an ArrayCollection of the current Contractevaluations objects in the database
          foreach ($contract->getContractEvaluation() as $contractevaluation) {
          $originalCreterion->add($contractevaluation);
          } 
        $form = $this->createForm(new ContractStep4Type(), $contract);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           // remove the relationship between the ContractEvaluation and the Contract
         foreach ($originalCreterion as $contractevaluation) {
            if (false === $contract->getContractEvaluation()->contains($contractevaluation)) {
                $em->remove($contractevaluation);
              }
            }
             $data = $form->getData();
             $em->persist($data);
             $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Edit notice step 4");
             $audit->setDossier($data);
             $em->persist($audit);
             $em->flush();
             if($form->get('previousStep')->isClicked())
             {
                return $this->redirectToRoute('contract_notice_step3_edit',array('id'=>$id),301);
             }elseif ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('contract_notice_step4_edit',array('id'=>$id),301);
            }else{
                return $this->redirectToRoute('contract_document_view',array('id'=>$id),301);
             }
        }
        $engine = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_section_III.html.twig',array('form' => $form->createView(),'contract' => $contract));
       return $response = new Response($content);
    }
   
    /**
     * @Route("/contract/{id}/contractdocument/list", name="contract_document_loader")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractDocumentListAction(Request $request,$id){
        $request->isXmlHttpRequest(); // is it an Ajax request?
        $em = $this->getDoctrine()->getManager();
        $contractdocuments = $em->getRepository('AppBundle:ContractDocument')
                        ->findBy(array('contract' => $id));
        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize(array('data'=>$contractdocuments), 'json');
        if($contractdocuments){
             //Audit
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Load contract documents");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
        }
        return  new Response($jsonContent);
    }
    
    /**
     * @Route("/contract/{id}/contractdocument/view", name="contract_document_view")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractDocumentViewAction($id){
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
        if (!$contract) {
                 throw $this->createNotFoundException(
            'No record found for contract with id'.$id
              );
            }
        $engine  = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_section_IV.html.twig',array('contract'=>$contract));
      return $response = new Response($content);
    }
    
    /**
     * @Route("/contract/{id}/contractdocument/upload", name="contract_document_create")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractDocumentCreateAction(Request $request,$id) {
          $em = $this->getDoctrine()->getManager();
          $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
          if (!$contract) {
                 throw $this->createNotFoundException(
            'No record found for contract with id'.$id
              );
            }
         $contractdocument = new ContractDocument();
         $contractdocument->setContract($contract);
         $form = $this->createForm(new ContractDocumentType(), $contractdocument);
         $form->handleRequest($request);
         if ($form->isSubmitted()&& $form->isValid()) {
          $data = $form->getData();
          $em->persist($data);
          $em->flush();
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Create contract document");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush(); 
         return $this->redirect($this->generateUrl('contract_document_view',array('id'=> $id)));
         }
         $engine = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_new_document.html.twig',array('form' => $form->createView(),'contract'=>$contract));
       return $response = new Response($content);
      }
    
    /**
     * @Route("/contractdocument/{id}/edit", name="contract_document_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractDocumentEditAction(Request $request, $id) {
          $em = $this->getDoctrine()->getManager();
          $contractdocument = $em->getRepository('AppBundle:ContractDocument')->findOneBy( array('id' => $id));
          if (!$contractdocument) {
                 throw $this->createNotFoundException(
                'No record found for contract document with id'.$id
              );
            }
         $form = $this->createForm(new ContractDocumentType(), $contractdocument);
         $form->handleRequest($request);
         if ($form->isSubmitted()&& $form->isValid()) {
          $data = $form->getData();
          $em->persist($data);
          $em->flush();
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Edit contract document");
          $audit->setDossier($contractdocument->getContract());
          $em->persist($audit);
          $em->flush();
         return $this->redirect($this->generateUrl('contract_document_view',array('id'=> $contractdocument->getContract()->getId())));
         }
         $engine = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_edit_document.html.twig',array('form' => $form->createView(),'contract'=>$contractdocument->getContract()));
       return $response = new Response($content);
      }
      
    /**
     * @Route("/contractdocument/{id}/remove", name="contract_document_remove")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractDocumentRemoveAction($id) {
       
          $em = $this->getDoctrine()->getManager();
          $contractdocument = $em->getRepository('AppBundle:ContractDocument')->findOneBy( array('id' => $id));
          if (!$contractdocument) {
                 throw $this->createNotFoundException(
                'No record found for contract document with id'.$id
              );
            }
          $em->remove($contractdocument);
          $em->flush();
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
                       $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Delete contract document");
          $audit->setDossier($contractdocument->getContract());
          $em->persist($audit);
          $em->flush();
        return $this->redirect($this->generateUrl('contract_document_view',array('id'=> $contractdocument->getContract()->getId())));
      }
      
     /**
     * @Route("/contract/{id}/validation", name="contract_validation")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractValidationAction($id){
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
        if (!$contract) {
                 throw $this->createNotFoundException(
            'No record found for contract with id'.$id
              );
            }
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Validate notice");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();    
        $validator = $this->get('validator');
        $errors = $validator->validate($contract, null, array('validationStep1','validationStep2','validationStep3','validationStep4'));
        if (count($errors) > 0) {
          $engine  = $this->container->get('templating');
          $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_validation.html.twig',array('contract'=>$contract,'errors' => $errors));
          return $response = new Response($content); 
        }
        $engine  = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/contract_notice_validation.html.twig',array('contract'=>$contract,'errors' => $errors));
       return $response = new Response($content); 
    }  
      
    /**
     * @Route("/contract/{id}/remove", name="contract_removal")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractRemoveAction($id) {
          $em = $this->getDoctrine()->getManager();
          $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
          if (!$contract) {
                 throw $this->createNotFoundException(
                'No record found for contract with id'.$id
              );
            }
          $em->remove($contract);
          $em->flush();
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Delete notice");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();
        return $this->redirect($this->generateUrl('contract_notice_list'));
      }
       /**
     * @Route("/contract/{id}/publication", name="contract_publication")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractPublicationAction($id) {
          $contractingUser = $this->get('security.token_storage')->getToken()->getUser();
          $em = $this->getDoctrine()->getManager();
          $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
          if (!$contract) {
                 throw $this->createNotFoundException(
                'No record found for contract with id'.$id
              );
            }
          $contract->setStatus("Submission Opened");
          $contractofficer = new ContractOfficer();
          $contractofficer->setContractingUser($contractingUser);
          $contractofficer->setContract($contract);
          $contractofficer->setPermission("Opener");
          $em->persist($contractofficer);
          $em->flush();
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Publish notice");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();
        return $this->redirect($this->generateUrl('contract_notice_list'));
      }
      
    /**
     * @Route("/associated_dossier/view", name="contracting_view_associated_dossiers")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function associatedDossierViewAction() {
         $contractingUser = $this->get('security.token_storage')->getToken()->getUser();
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT a,c.id,c.referenceNumber,c.title,c.procedureType,c.status
                                    FROM AppBundle:ContractOfficer a JOIN a.contract c WHERE a.contractingUser=:contractingUser'
                                   )->setParameter('contractingUser',$contractingUser);
         $dossier = $query->getResult();          
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$dossier), 'json');
         return  new Response($jsonContent);
     }  
    
     /**
     * @Route("/active_dossier/view", name="contracting_view_active_dossiers")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function activeDossierViewAction() {
         $contractingUser = $this->get('security.token_storage')->getToken()->getUser();
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT a,c.id,c.referenceNumber,c.title,c.procedureType,c.status
                                    FROM AppBundle:ContractOfficer a JOIN a.contract c WHERE a.contractingUser!=:contractingUser'
                                   )->setParameter('contractingUser',$contractingUser);
         $dossier = $query->getResult();          
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$dossier), 'json');
         return  new Response($jsonContent);
     }
    /**
     * @Route("/active_dossier/{id}/details/view", name="contracting_view_active_dossier_details")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function activeDossierDetailsViewAction($id) {
          $em = $this->getDoctrine()->getManager();
          $query = $em->createQuery(
                                   'SELECT c.id,c.referenceNumber,c.procedureType,c.status,c.title,c.description,c.price,c.closingDate FROM AppBundle:Contract c WHERE c.id=:id'
                                   )->setParameter('id',$id);
          $dossier = $query->getResult(); 
          if (!$dossier) {
                 throw $this->createNotFoundException(
                'No record found for dossier with id'." ".$id
              );
            }
          $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
          if (!$contract) {
                 throw $this->createNotFoundException(
                'No record found for contract with id'.$id
              );
            }  
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("View active dossier details");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush(); 
            
         $engine = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/active_dossier_details.html.twig',array('dossier' => $dossier,'contract' =>$id));
    
       return $response = new Response($content);
    } 
    /**
     * @Route("/dossier/{id}/details/view", name="contracting_view_dossier_details")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierDetailsViewAction($id) {
          $em = $this->getDoctrine()->getManager();
          $query = $em->createQuery(
                                   'SELECT c.id,c.referenceNumber,c.procedureType,c.status,c.title,c.description,c.price,c.closingDate FROM AppBundle:Contract c WHERE c.id=:id'
                                   )->setParameter('id',$id);
          $dossier = $query->getResult(); 
          if (!$dossier) {
                 throw $this->createNotFoundException(
                'No record found for dossier with id'." ".$id
              );
            }
            
          $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
          //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("View asscociated dossier details");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();
          
         $engine = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/dossier_details.html.twig',array('dossier' => $dossier,'contract' =>$id));
    
       return $response = new Response($content);
    } 
    
    /**
     * @Route("/dossier/{id}/associated_officers/view", name="contracting_view_dossier_associated_officers")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierAssociatedOfficersViewAction($id) {
         $engine = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/dossier_associated_officer.html.twig',array('contract'=>$id));
       return $response = new Response($content);
    }
    
     /**
     * @Route("/dossier/{id}/associated_officers/list", name="contracting_associated_officers_loader")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierAssociatedOfficersListAction($id) {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT c FROM AppBundle:ContractOfficer c WHERE c.contract=:id'
                                   )->setParameter('id',$id);
         $associatedofficers = $query->getResult(); 
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$associatedofficers), 'json');
           
          //Audit
          $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
          if ($associatedofficers) {
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("View asscociated dossier officers");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();
          }
         return  new Response($jsonContent);
    }
    
     /**
     * @Route("/dossier/{id}/associated_officers/new", name="contracting_new_dossier_associated_officer")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierAssociatedOfficersNewAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT c
                                    FROM AppBundle:Contract c WHERE c.id=:id'
                                   )->setParameter('id',$id);
         $contract = $query->getSingleResult();
         if (!$contract) {
                 throw $this->createNotFoundException(
            'No record found for contract with id number'.$id
              );
            }
         $contractofficer=new ContractOfficer();
         $contractofficer->setContract($contract);
         $contractofficer->setPermission("Opener");
         $form = $this->createForm(new ContractOfficerType(), $contractofficer);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $em->persist($data);
             $em->flush();
             
          //Audit
          $audit = new Audit();
          $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Create asscociated dossier officer");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();
        return $this->redirectToRoute('contracting_view_dossier_associated_officers',array('id'=> $id),301);
         } 
         $engine = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/new_dossier_associated_officer.html.twig',array('form' => $form->createView()));
        return $response = new Response($content);
    } 
    
    /**
     * @Route("/contract_officer/{id}/remove", name="contract_officer_removal")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function contractOfficerRemovalAction($id) {
          $em = $this->getDoctrine()->getManager();
          $contractofficer = $em->getRepository('AppBundle:ContractOfficer')->findOneBy( array('id' => $id));
          if (!$contractofficer) {
                 throw $this->createNotFoundException(
                'No record found for contract officer with id'.$id
              );
            }
          $em->remove($contractofficer);
          $em->flush();
            //Audit
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("Delete asscociated dossier officer");
          $audit->setDossier($contractofficer->getContract());
          $em->persist($audit);
          $em->flush();
        return $this->redirect($this->generateUrl('contracting_view_dossier_associated_officers',array('id'=> $contractofficer->getContract()->getId())));
      }
      
    /**
     * @Route("/dossier/{id}/prequalified/view", name="contracting_view_dossier_prequalified")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierPrequalifiedViewAction($id) {
         $em = $this->getDoctrine()->getManager();
         $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
         if (!$contract) {
             throw $this->createNotFoundException(
                 'No contract found for id '.$id
              );
          }
          if($contract->getProcedureType()=="Open"){
             throw $this->createAccessDeniedException('Prequalification is done only to restricted contracts');
          }else{
            $engine = $this->container->get('templating');
            $content = $engine->render('@AppBundle/Resources/views/Contracting/dossier_prequalified.html.twig',array('contract'=>$id));
            return $response = new Response($content); 
          }
    }
    
    /**
     * @Route("/dossier/{id}/prequalified/list", name="contracting_dossier_prequalified_loader")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierPrequalifiedListAction($id) {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT p,c FROM AppBundle:PreQualified p JOIN p.contract c WHERE p.contract=:id'
                                   )->setParameter('id',$id);
         $prequalified = $query->getResult();
         
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
          $serializer = new Serializer(array($normalizer), array($encoder));
          $jsonContent = $serializer->serialize(array('data'=>$prequalified), 'json');
            //Audit
          $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
          if($prequalified){
          $audit = new Audit();
          $contracting = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($contracting->getUsername());
          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
          $audit->setFunctionType("Contracting");
          $audit->setEventType("View pre-qualified suppliers");
          $audit->setDossier($contract);
          $em->persist($audit);
          $em->flush();
          }
         return  new Response($jsonContent);
    }
    
    /**
     * @Route("/dossier/{id}/prequalified/new", name="contracting_dossier_prequalified_new")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierPrequalifiedNewAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT c
                                    FROM AppBundle:Contract c WHERE c.id=:id'
                                   )->setParameter('id',$id);
         $contract = $query->getSingleResult();
         if (!$contract) {
                 throw $this->createNotFoundException(
            'No record found for contract with id number'.$id
              );
            }
         $prequalified=new PreQualified();
         $prequalified->setContract($contract);
         $form = $this->createForm(new PreQualifiedType(), $prequalified);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $recipient= $data->getCompanyName()->getEmail();
             $em->persist($data);
             $em->flush();
             //Audit
             $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Create pre-qualified supplier");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
             
            self::sendMailAction($recipient);
          return $this->redirectToRoute('contracting_view_dossier_prequalified',array('id'=> $id),301);
         } 
         $engine = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/new_dossier_prequalified.html.twig',array('form' => $form->createView()));
        return $response = new Response($content);
    }
    
     public function sendMailAction($recipient)
      {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $message = \Swift_Message::newInstance()
        ->setSubject('Invitation For Tender Application')
        ->setFrom($user->getEmail())
        ->setTo($recipient)
        ->setBody(
            $this->renderView(
                  '@AppBundle/Resources/views/Emails/shortlist.html.twig'
             ),
            'text/html'
        );
       $this->get('mailer')->send($message);
     }
     
    /**
     * @Route("/prequalified/{id}/remove", name="prequalified_removal")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function dossierPrequalifiedRemoveAction($id) {
          $em = $this->getDoctrine()->getManager();
          $prequalified = $em->getRepository('AppBundle:PreQualified')->findOneBy( array('id' => $id));
          if (!$prequalified) {
                 throw $this->createNotFoundException(
                'No record found for prequalifed company with id'.$id
              );
            }
             $em->remove($prequalified);
             $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Delete pre-qualified supplier");
             $audit->setDossier($prequalified->getContract());
             $em->persist($audit);
             $em->flush();
        return $this->redirect($this->generateUrl('contracting_view_dossier_prequalified',array('id'=> $prequalified->getContract()->getId())));
      }
      
     /**
      * @Route("/dossier/{id}/tenders/open", name="contracting_open_tenders")
      * @Template()
      * @Security("has_role('ROLE_CONTRACTING')")
      */
     public function tenderOpenAction($id) {
         $engine  = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/tender_opening.html.twig',array('contract'=>$id));
        return $response = new Response($content);
     }
     
    /**
     * @Route("/dossier/{id}/tenders/view", name="contracting_view_tenders")
     * @Template()
     */
    public function tendersViewAction($id) {
        $em = $this->getDoctrine()->getManager();
        $bid = $em->getRepository('AppBundle:Bid')->findBy(array('contract' => $id));
             //Audit
             $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
             //Audit
             if($bid){
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Load tender");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
             }
        $engine  = $this->container->get('templating');
        $content = $engine->render('@AppBundle/Resources/views/Contracting/tenders.html.twig',array('bid'=>$bid,'contract'=>$id));
      return $response = new Response($content);
    }
    
    /**
     * @Route("/bid/{id}/evaluate", name="contracting_evaluate_bid")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function bidEvaluationAction(Request $request,$id) {
         $em = $this->getDoctrine()->getManager();
         $bid = $em->getRepository('AppBundle:Bid')->findOneBy(array('id' => $id));
         if (!$bid) {
                 throw $this->createNotFoundException(
                 'No record found for bid with id'.$id
              );
            }
        $bidevaluation=new BidEvaluation();
        $bidevaluation->setBid($bid);
        $form = $this->createForm(new BidEvaluationType($bid->getContract()), $bidevaluation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Evaluate tender");
             $audit->setDossier($bid->getContract());
             $em->persist($audit);
             $em->flush();
        return $this->redirect($this->generateUrl('contracting_view_tenders',array('id'=> $bid->getContract()->getId())));
        }    
         $engine  = $this->container->get('templating');
         $content = $engine->render('@AppBundle/Resources/views/Contracting/bid.html.twig', array(
            'form'  =>  $form->createView()));
        return $response = new Response($content);
    }
    
    /**
     * @Route("/bidevaluation/{id}/remove", name="bidevaluation_removal")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function bidEvaluationRemoveAction($id) {
          $em = $this->getDoctrine()->getManager();
          $bidevaluation = $em->getRepository('AppBundle:BidEvaluation')->findOneBy( array('id' => $id));
          if (!$bidevaluation) {
                 throw $this->createNotFoundException(
                'No record found for bid evaluation with id'.$id
              );
            }
          $em->remove($bidevaluation);
          $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Delete tender evaluation");
             $audit->setDossier($bidevaluation->getBid()->getContract());
             $em->persist($audit);
             $em->flush();
        return $this->redirect($this->generateUrl('contracting_view_tenders',array('id'=> $bidevaluation->getBid()->getContract()->getId())));
      }
    
    /**
     * @Route("/profile/edit", name="contracting_profile_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function profileEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contractinguser = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new ContractingUserProfileType(), $contractinguser);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $em->persist($data);
           $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
                          $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Edit Profile");
             $em->persist($audit);
             $em->flush();
         }
        return $this->render(
            'AppBundle:Contracting:profile.html.twig',array('form' => $form->createView()));
     }
     
     /**
     * @Route("/profile_account/view", name="contracting_profile_account_view")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function profileAccountViewAction()
    {
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("View Profile");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
        return $this->render(
            'AppBundle:Contracting:profile_account.html.twig',array('user' => $contracting));
     } 
     
    /**
     * @Route("/profile_password/edit", name="contracting_profile_password_edit")
     * @Template()
     * @Security("has_role('ROLE_CONTRACTING')")
     */
    public function profilePasswordEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contractinguser = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new ContractingUserChangePasswordType(), $contractinguser);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $this->encodePassword($data);
           $em->persist($data);
           $em->flush();
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Edit profile password");
             $em->persist($audit);
             $em->flush();
          return $this->redirect($this->generateUrl('contracting_profile_account_view'));
         }
        return $this->render(
            'AppBundle:Contracting:new_profile_password.html.twig',array('form' => $form->createView()));
     } 
    /**
     * @Route("/register", name="register_contracting")
     * @Template()
     */
     public function registerContractingAction()
    {
        $registration = new ContractingUserRegistration();
        $form = $this->createForm(new ContractingUserRegistrationType(), $registration, array(
            'action' => $this->generateUrl('create_contracting'),
        ));

        return $this->render(
            '@AppBundle/Resources/views/Contracting/register.html.twig',
            array('form' => $form->createView()));
    }
    
    /**
     * @Route("/register/create", name="create_contracting")
     * @Template()      
     */
     public function newContractingAction(Request $request)
    {
    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(new ContractingUserRegistrationType(), new ContractingUserRegistration());
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $registration = $form->getData();
        $this->encodePassword($registration->getUser());
        $em->persist($registration->getUser());
        $em->flush();
             //Audit
             $audit = new Audit();
             $audit->setUsername($registration->getUser()->getUsername());
             $audit->setName($registration->getUser()->getFirstname()." ".$registration->getUser()->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("SignUp");
             $em->persist($audit);
             $em->flush();
        $this->authenticate($registration->getUser(),self::PROVIDER_KEY_CONTRACTING);
     return $this->redirectToRoute('contracting_home');
    }
       return $this->render(
        'AppBundle:Contracting:register.html.twig',
        array('form' => $form->createView())
       );
    }
 
     private function encodePassword(UserInterface $user)
    {
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);
    }
    
     private function authenticate(UserInterface $user,$key)
    {
        $token = new UsernamePasswordToken($user, null, $key, $user->getRoles());
        $this->get('security.context')->setToken($token);
    }
     /**
     * @Route("/login", name="contracting_login")
     * @Template()
     */
    public function loginAction()
    {
    $authenticationUtils = $this->get('security.authentication_utils');
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();
    $engine = $this->container->get('templating');
    $content = $engine->render('@AppBundle/Resources/views/Contracting/login.html.twig',
        array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    return $response = new Response($content);
    }
     /**
     * @Route("/login_check", name="contracting_login_check")
     * @Template()
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
         //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $$audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Login");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
    }
    
    /**
     * @Route("/logout", name="contracting_logout")
     * @Template()
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
             //Audit
             $audit = new Audit();
             $contracting = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($contracting->getUsername());
             $audit->setName($contracting->getFirstname()." ".$contracting->getLastname());
             $audit->setFunctionType("Contracting");
             $audit->setEventType("Logout");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
    }
}
