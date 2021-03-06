<?php
// src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\TenderType;
use AppBundle\Form\Type\ContactPersonType;
use AppBundle\Form\Type\CompanyProfileType;
use AppBundle\Form\Type\PortfolioType;
use AppBundle\Form\Type\ChangePasswordType;
use AppBundle\Form\Type\ProfileLogoType;
use AppBundle\Form\Type\EconomicUserType;
use AppBundle\Entity\Bid;
use AppBundle\Entity\Tender;
use AppBundle\Entity\Portfolio;
use AppBundle\Entity\Audit;
use AppBundle\Entity\EconomicUser;

class EconomicUserController extends Controller
{
    
    const PROVIDER_KEY_USER = 'economic_secured_area';
    
     /**
     * @Route("/index", name="user_home")
     * @Template()
     */
    public function indexAction(){
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //Audit
        $audit = new Audit();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $audit->setUsername($user->getUsername());
        $audit->setName($user->getFirstname()." ".$user->getLastname());
        $audit->setFunctionType("Economic");
        $audit->setEventType("Login");
        $em = $this->getDoctrine()->getManager();
        $em->persist($audit);
        $em->flush();
        $engine = $this->container->get('templating');
        $content = $engine->render('AppBundle:User:index.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
       return $response = new Response($content); 
    }
    
     /**
     * @Route("/associated_dossier/view", name="user_view_associated_dossier")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function associatedDossierViewAction() {
         $user = $this->get('security.token_storage')->getToken()->getUser();
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT b,c.id,c.referenceNumber,c.title,c.procedureType,c.status
                                    FROM AppBundle:Bid b JOIN b.contract c WHERE b.economicUser=:economicUser'
                                   )->setParameter('economicUser',$user);
         $dossiers = $query->getResult(); 
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
          if($dossiers){
              //Audit
             $audit = new Audit();
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("View associated dossier");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
          }
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$dossiers), 'json');
       return  new Response($jsonContent);
    }
    
    /**
     * @Route("/active_dossier/view", name="user_view_active_dossier")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function activeDossiersViewAction() {
         $em = $this->getDoctrine()->getManager();
         $dossiers = $em->getRepository('AppBundle:Contract')->findBy( array('status' => "Submission Opened"));
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
                  return $object->getId();
          });
           if($dossiers){
             //Audit
             $audit = new Audit();
             $user = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("View active dossier");
             $em->persist($audit);
             $em->flush();
          }
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$dossiers), 'json');
       return  new Response($jsonContent);
    }
    
    /**
     * @Route("/active_dossier/{id}/details/view", name="user_view_active_dossier_details")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function activeDossierDetailsViewAction($id) {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT c.id,c.referenceNumber,c.procedureType,c.status,c.title,c.description,c.paymentCurrency,c.price,c.closingDate FROM AppBundle:Contract c WHERE c.id=:id'
                                   )->setParameter('id',$id);
         $dossier = $query->getResult();
         if($dossier){
              //Audit
             $audit = new Audit();
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $user = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("View active dossier details");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
          }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:active_dossier_details.html.twig',array('dossier' => $dossier,'contract'=>$id));
       return $response = new Response($content);
    }
    
    /**
     * @Route("/associated_dossier/{id}/details/view", name="user_view_associated_dossier_details")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function associatedDossierDetailsViewAction($id) {
          $user = $this->get('security.token_storage')->getToken()->getUser();
          $em = $this->getDoctrine()->getManager();
          $bid = $em->getRepository('AppBundle:Bid')
                         ->findOneBy( array('contract' => $id,'economicUser' => $user));
          if (!$bid) {
             throw $this->createNotFoundException(
                 'No bid found for id '.$id
              );
          }
          if($bid){
              //Audit
             $audit = new Audit();
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("View associated dossier details");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
          }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:associated_dossier_details.html.twig',array('bid'=>$bid));
       return $response = new Response($content);
    }
    
    /**
     * @Route("/dossier/{id}/downloads", name="user_view_dossier_downloads")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function dossierDownloadsViewAction($id) {
          $user = $this->get('security.token_storage')->getToken()->getUser();
          $em = $this->getDoctrine()->getManager();
          $bid = $em->getRepository('AppBundle:Bid')
                         ->findOneBy( array('contract' => $id,'economicUser' => $user));
          if (!$bid) {
             throw $this->createNotFoundException(
                 'No bid found for id '.$id
              );
          }
           if($bid){
             //Audit
             $audit = new Audit();
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("View dossier downloads");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
          }
         $engine  = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:downloads.html.twig',array('bid'=>$bid));
       return $response = new Response($content);
    }
     
    /**
     * @Route("/dossier/{id}/downloads/list", name="user_list_dossier_downloads")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function dossierDownloadsListAction($id) {
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT d
                                    FROM AppBundle:ContractDocument d WHERE d.contract=:id'
                                   )->setParameter('id',$id);
         $downloads = $query->getResult(); 
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
         });
          if($downloads){
             //Audit
             $audit = new Audit();
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $user = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("View dossier downloads");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
          }
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$downloads), 'json');
       return  new Response($jsonContent);
    }
    
    /**
     * @Route("/dossier/{id}/payment", name="user_payment")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function paymentAction($id) {
        $em = $this->getDoctrine()->getManager();
        $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
        $engine = $this->container->get('templating');
        $content = $engine->render('AppBundle:User:payment.html.twig',array('contract'=> $contract));
      return $response = new Response($content);
    }
    

    
    /**
     * @Route("/confirmpayment", name="user_confirm_payment")
     * @Template()
     */
    public function confirmPaymentAction() {
        
               
        $engine = $this->container->get('templating');
        $content = $engine->render('AppBundle:User:payment.html.twig');
      return $response = new Response($content);
    }
    
    /**
     * @Route("/dossier/{id}/bid/new", name="user_new_bid")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function bidNewAction($id) {
         $user = $this->get('security.token_storage')->getToken()->getUser();
         $em = $this->getDoctrine()->getManager();
         $contract = $em->getRepository('AppBundle:Contract')
                         ->findOneBy( array('id' => $id));
         if (!$contract) {
             throw $this->createNotFoundException(
                 'No contract found for id '.$id
              );
          }
          
          $bid = $em->getRepository('AppBundle:Bid')
                         ->findOneBy( array('contract' => $id,'economicUser' => $user));
          if (!$bid) {
            if($contract->getIsPayableDocument()=="Yes"){
            return $this->redirectToRoute('user_payment',array('id'=> $id));
         }else{
             $bid = new Bid();
             $bid->setContract($contract);
             $bid->setEconomicUser($user);
             $em->persist($bid);
             $em->flush();
             //Audit
             $audit = new Audit();
             $contract = $em->getRepository('AppBundle:Contract')->findOneBy( array('id' => $id));
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("New Bid");
             $audit->setDossier($contract);
             $em->persist($audit);
             $em->flush();
        
           return $this->redirectToRoute('user_view_tender',array('bid'=> $bid,'contract'=>$contract)); 
             }
        }else{
            throw $this->createNotFoundException(
                 'You have already placed your bid for this dossier '."-".$contract->getTitle()
              );
        }
    } 
   
    /**
     * @Route("/bid/{id}/tenders", name="user_view_tender")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function tenderViewAction($id) {
          $em = $this->getDoctrine()->getManager();
          $bid = $em->getRepository('AppBundle:Bid')
                         ->findOneBy( array('id' => $id));
          if (!$bid) {
             throw $this->createNotFoundException(
                 'No bid found for id '.$id
              );
          }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:tender.html.twig',array('bid'=>$bid));
      return $response = new Response($content);
    }
   
    /**
     * @Route("/bid/{id}/tender/list", name="user_tender_loader")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function tenderListAction($id) {
         $em = $this->getDoctrine()->getManager();
         $tenders= $em->getRepository('AppBundle:Tender')->findOneBy( array('bid' => $id));
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
         });
         if($tenders){
             //Audit
             $audit = new Audit();
             $user = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Economic");
             $audit->setEventType("Load tender");
             $audit->setDossier($tenders->getBid()->getContract());
             $em->persist($audit);
             $em->flush();
         }
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$tenders), 'json');
       return  new Response($jsonContent);
    }
        
    /**
     * @Route("/bid/{id}/tender/new", name="user_tender_new")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function tenderNewAction(Request $request,$id) {
          $em = $this->getDoctrine()->getManager();
          $bid = $em->getRepository('AppBundle:Bid')->findOneBy( array('id' => $id));
          if (!$bid) {
                 throw $this->createNotFoundException(
            'No record found for bid with id'.$id
              );
            }
         $tender = new Tender();
         $tender->setBid($bid);
         $form = $this->createForm(TenderType::class, $tender);
         $form->handleRequest($request);
         if ($form->isSubmitted()&& $form->isValid()) {
          $data = $form->getData();
          $em->persist($data);
          $em->flush();
          //Audit
          $audit = new Audit();
          $user = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($user->getUsername());
          $audit->setName($user->getFirstname()." ".$user->getLastname());
          $audit->setFunctionType("Economic");
          $audit->setEventType("New tender");
          $audit->setDossier($data->getBid()->getContract());
          $em->persist($audit);
          $em->flush();
         return $this->redirect($this->generateUrl('user_view_tender',array('id'=> $id)));
         }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:new_tender.html.twig',array('form' => $form->createView()));
       return $response = new Response($content);
      }
      
    /**
     * @Route("/tender/{id}/edit", name="user_edit_tender")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function tenderEditAction(Request $request,$id) {
          $em = $this->getDoctrine()->getManager();
          $tender = $em->getRepository('AppBundle:Tender')->findOneBy( array('id' => $id));
          if (!$tender) {
                 throw $this->createNotFoundException(
            'No record found for tender with id'.$id
              );
            }
          // keep in mind that this will call all registered security voters
         $this->denyAccessUnlessGranted('edit', $tender, 'Unauthorized access!');  
         
         $form = $this->createForm(TenderType::class, $tender);
         $form->handleRequest($request);
         if ($form->isSubmitted()&& $form->isValid()) {
          $data = $form->getData();
          $em->persist($data);
          $em->flush();
          //Audit
          $audit = new Audit();
          $user = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($user->getUsername());
          $audit->setName($user->getFirstname()." ".$user->getLastname());
          $audit->setFunctionType("Economic");
          $audit->setEventType("Edit tender");
          $audit->setDossier($data->getBid()->getContract());
          $em->persist($audit);
          $em->flush();
         return $this->redirect($this->generateUrl('user_view_tender',array('id'=> $tender->getBid()->getId())));
         }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:edit_tender.html.twig',array('form' => $form->createView()));
    
       return $response = new Response($content);
      }
      
     /**
     * @Route("/tender/{id}/remove", name="user_remove_tender")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function tenderRemovalAction($id) {
          $em = $this->getDoctrine()->getManager();
          $tender = $em->getRepository('AppBundle:Tender')->findOneBy( array('id' => $id));
          if (!$tender) {
                 throw $this->createNotFoundException(
                'No record found for tender with id'.$id
              );
            }
          $em->remove($tender);
          $em->flush();
          //Audit
          $audit = new Audit();
          $user = $this->get('security.token_storage')->getToken()->getUser();
          $audit->setUsername($user->getUsername());
          $audit->setName($user->getFirstname()." ".$user->getLastname());
          $audit->setFunctionType("Economic");
          $audit->setEventType("Delete tender");
          $audit->setDossier($tender->getBid()->getContract());
          $em->persist($audit);
          $em->flush();
        return $this->redirect($this->generateUrl('user_view_tender',array('id'=> $tender->getBid()->getId())));
      }
    
    /**
     * @Route("/contact_person/edit", name="user_profile_view")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function contactPersonEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ContactPersonType::class, $user);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $em->persist($data);
           $em->flush();
          //Audit
          $audit = new Audit();
          $audit->setUsername($user->getUsername());
          $audit->setName($user->getFirstname()." ".$user->getLastname());
          $audit->setFunctionType("Economic");
          $audit->setEventType("Edit contact person details");
          $em->persist($audit);
          $em->flush();
         }
        return $this->render(
            'AppBundle:User:profile.html.twig',array('form' => $form->createView()));
     }  
    
    /**
     * @Route("/company_profile/edit", name="user_company_profile_edit")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function companyProfileEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(CompanyProfileType::class, $user);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $em->persist($data);
           $em->flush();
           //Audit
           $audit = new Audit();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("Edit company profile details");
           $em->persist($audit);
           $em->flush();
         }
        return $this->render(
            'AppBundle:User:company_profile.html.twig',array('form' => $form->createView()));
     }
    
    /**
     * @Route("/profile_attachment/view", name="user_profile_attachment_view")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileAttachmentViewAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        return $this->render(
            'AppBundle:User:profile_attachment.html.twig',array('user' => $user));
     } 
     
    /**
     * @Route("/profile_attachment/list", name="user_profile_attachment_list")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileAttachmentListAction() {
         $user = $this->get('security.token_storage')->getToken()->getUser();
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                                   'SELECT p
                                    FROM AppBundle:Portfolio p WHERE p.economicUser=:id'
                                   )->setParameter('id',$user->getId());
         $profileAttachments = $query->getResult(); 
         $encoder = new JsonEncoder();
         $normalizer = new GetSetMethodNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
         });
         if($profileAttachments){
           //Audit
           $audit = new Audit();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("Load profile attachment");
           $em->persist($audit);
           $em->flush();
         }
         $serializer = new Serializer(array($normalizer), array($encoder));
         $jsonContent = $serializer->serialize(array('data'=>$profileAttachments), 'json');
       return  new Response($jsonContent);
    }
    /**
     * @Route("/profile_attachment/new", name="user_profile_attachment_new")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileAttachmentNewAction(Request $request) {
         $em = $this->getDoctrine()->getManager();
         $user = $this->get('security.token_storage')->getToken()->getUser();
         $portfolio = new Portfolio();
         $portfolio->setEconomicUser($user);
         $form = $this->createForm(PortfolioType::class, $portfolio);
         $form->handleRequest($request);
         if ($form->isSubmitted()&& $form->isValid()) {
          $data = $form->getData();
          $em->persist($data);
          $em->flush();
           //Audit
           $audit = new Audit();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("New profile attachment");
           $em->persist($audit);
           $em->flush();
         return $this->redirect($this->generateUrl('user_profile_attachment_view'));
                 
         }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:new_profile_attachment.html.twig',array('form' => $form->createView()));
    
       return $response = new Response($content);
      }
    
    /**
     * @Route("/profile_attachment/{id}/edit", name="user_profile_attachment_edit")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileAttachmentEditAction(Request $request,$id) {
          $em = $this->getDoctrine()->getManager();
          $profileAttachment = $em->getRepository('AppBundle:Portfolio')->findOneBy( array('id' => $id));
          if (!$profileAttachment) {
                 throw $this->createNotFoundException(
            'No record found for portfolio with id'.$id
              );
            }
            
         $form = $this->createForm(PortfolioType::class, $profileAttachment);
         $form->handleRequest($request);
         if ($form->isSubmitted()&& $form->isValid()) {
           $data = $form->getData();
           $em->persist($data);
           $em->flush();
           //Audit
           $audit = new Audit();
           $user = $this->get('security.token_storage')->getToken()->getUser();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("Edit profile attachment");
           $em->persist($audit);
           $em->flush();
         return $this->redirect($this->generateUrl('user_profile_attachment_view'));
         }
         $engine = $this->container->get('templating');
         $content = $engine->render('AppBundle:User:edit_profile_attachment.html.twig',array('form' => $form->createView()));
    
       return $response = new Response($content);
      }
     /**
     * @Route("/profile_attachmen/{id}/remove", name="user_profile_attachment_remove")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileAttachmentRemoveAction($id) {
          $em = $this->getDoctrine()->getManager();
          $profileAttachment = $em->getRepository('AppBundle:Portfolio')->findOneBy( array('id' => $id));
          if (!$profileAttachment) {
                 throw $this->createNotFoundException(
            'No record found for portfolio with id'.$id
              );
            }
           $em->remove($profileAttachment);
           $em->flush();
           //Audit
           $audit = new Audit();
           $user = $this->get('security.token_storage')->getToken()->getUser();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("Delete profile attachment");
           $em->persist($audit);
           $em->flush();
        return $this->redirect($this->generateUrl('user_profile_attachment_view'));
      }
    
    /**
     * @Route("/profile_account/view", name="user_profile_account_view")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileAccountViewAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render(
            'AppBundle:User:profile_account.html.twig',array('user' => $user));
     } 
   
    /**
     * @Route("/profile_password/edit", name="user_profile_password_edit")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function passwordEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ChangePasswordType::class,$user);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $this->encodePassword($data);
           $em->persist($data);
           $em->flush();
            //Audit
           $audit = new Audit();
           $user = $this->get('security.token_storage')->getToken()->getUser();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("Edit profile password");
           $em->persist($audit);
           $em->flush();
          return $this->redirect($this->generateUrl('user_profile_account_view'));
         }
        return $this->render(
            'AppBundle:User:new_profile_password.html.twig',array('form' => $form->createView()));
     }
    
    /**
     * @Route("/profile_logo/edit", name="user_profile_logo_edit")
     * @Template()
     * @Security("has_role('ROLE_ECONOMIC')")
     */
    public function profileLogoEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ProfileLogoType::class, $user);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $data->setUpdatedAt(new \DateTime());
           $em->persist($data);
           $em->flush();
            //Audit
           $audit = new Audit();
           $audit->setUsername($user->getUsername());
           $audit->setName($user->getFirstname()." ".$user->getLastname());
           $audit->setFunctionType("Economic");
           $audit->setEventType("Edit profile logo");
           $em->persist($audit);
           $em->flush();
          return $this->redirect($this->generateUrl('user_profile_account_view'));
         }
        return $this->render(
            'AppBundle:User:profile_logo.html.twig',array('form' => $form->createView()));
     } 
    /**
     * @Route("/register", name="register_user")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $user= new EconomicUser();
        $form = $this->createForm(EconomicUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //Audit
            $audit = new Audit();
            $audit->setUsername($user->getUsername());
            $audit->setName($user->getFirstname()." ".$user->getLastname());
            $audit->setFunctionType("Contracting");
            $audit->setEventType("SignUp");
            $em->persist($audit);
            $em->flush();
          return $this->redirectToRoute('user_home');
        }
        return $this->render(
            'AppBundle:User:register.html.twig',
            array('form' => $form->createView()));
     }

    /**
     * @Route("/login", name="user_login")
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
    $content = $engine->render('AppBundle:User:login.html.twig',
        array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    return $response = new Response($content);
    }
    /**
     * @Route("/login_check", name="user_login_check")
     * @Template()
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
    
    /**
     * @Route("/logout", name="user_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
        //Audit
        $audit = new Audit();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $audit->setUsername($user->getUsername());
        $audit->setName($user->getFirstname()." ".$user->getLastname());
        $audit->setFunctionType("Economic");
        $audit->setEventType("Logout");
        $em = $this->getDoctrine()->getManager();
        $em->persist($audit);
        $em->flush();
    }
     
}
