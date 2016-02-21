<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

/**
 * Description of AdminUserController
 *
 * @author lambert
 */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\AdminUserProfileType;
use AppBundle\Form\Type\AdminUserChangePasswordType;
use AppBundle\Form\Type\AdminUserRegistrationType;
use AppBundle\Form\Model\AdminUserRegistration;
use AppBundle\Entity\Audit;

class AdminUserController extends Controller{
    
     const PROVIDER_KEY_ADMIN = 'admin_secured_area';
   
    /**
     * @Route("/index", name="admin_home")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(){
       $authenticationUtils = $this->get('security.authentication_utils');
       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername(); 
       $engine = $this->container->get('templating');
       $content = $engine->render('@AppBundle/Resources/views/Admin/index.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
       return $response = new Response($content);
    }
    
    /**
     * @Route("/profile/edit", name="admin_profile_edit")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function profileEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $adminuser = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new AdminUserProfileType(), $adminuser);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $em->persist($data);
           $em->flush();
             //Audit
             $audit = new Audit();
             $admin = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($admin->getUsername());
                          $audit->setName($admin->getFirstname()." ".$admin->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("Edit Profile");
             $em->persist($audit);
             $em->flush();
         }
        return $this->render(
            'AppBundle:Admin:profile.html.twig',array('form' => $form->createView()));
     }
     
     /**
     * @Route("/profile_account/view", name="admin_profile_account_view")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function profileAccountViewAction()
    {
             $adminuser = $this->get('security.token_storage')->getToken()->getUser();
             //Audit
             $audit = new Audit();
             $admin = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($admin->getUsername());
             $audit->setName($admin->getFirstname()." ".$admin->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("View Profile");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
        return $this->render(
            'AppBundle:Admin:profile_account.html.twig',array('user' => $adminuser));
     } 
     
    /**
     * @Route("/profile_password/edit", name="admin_profile_password_edit")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function profilePasswordEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $adminuser = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new AdminUserChangePasswordType(), $adminuser);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $this->encodePassword($data);
           $em->persist($data);
           $em->flush();
             //Audit
             $audit = new Audit();
             $admin = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($admin->getUsername());
             $audit->setName($admin->getFirstname()." ".$admin->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("Edit profile password");
             $em->persist($audit);
             $em->flush();
          return $this->redirect($this->generateUrl('admin_profile_account_view'));
         }
        return $this->render(
            'AppBundle:Admin:new_profile_password.html.twig',array('form' => $form->createView()));
     } 
    
     /**
     * @Route("/register", name="register_admin")
     * @Template()
     */
     public function registerAdminAction()
    {
        $registration = new AdminUserRegistration();
        $form = $this->createForm(new AdminUserRegistrationType(), $registration, array(
            'action' => $this->generateUrl('create_admin'),
        ));

        return $this->render(
            '@AppBundle/Resources/views/Admin/register.html.twig',
            array('form' => $form->createView()));
    }
    
      /**
     * @Route("/register/create", name="create_admin")
     * @Template()      
     */
     public function newAdminAction(Request $request)
    {
    
    $em = $this->getDoctrine()->getManager();

    $form = $this->createForm(new AdminUserRegistrationType(), new AdminUserRegistration());

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
             $audit->setFunctionType("Administrator");
             $audit->setEventType("SignUp");
             $em->persist($audit);
             $em->flush();
        
        $this->authenticate($registration->getUser(),self::PROVIDER_KEY_ADMIN);
        
        return $this->redirectToRoute('admin_home');
    }

       return $this->render(
        'AppBundle:Admin:register.html.twig',
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
     * @Route("/login", name="admin_login")
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
    $content = $engine->render('@AppBundle/Resources/views/Admin/login.html.twig',
        array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    
    return $response = new Response($content);
    }
     /**
     * @Route("/login_check", name="admin_login_check")
     * @Template()
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
             //Audit
             $audit = new Audit();
             $admin = $this->get('security.token_storage')->getToken()->getUser();
             $$audit->setUsername($admin->getUsername());
             $audit->setName($admin->getFirstname()." ".$admin->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("Login");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
    }
    
    /**
     * @Route("/logout", name="admin_logout")
     * @Template()
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
             //Audit
             $audit = new Audit();
             $admin = $this->get('security.token_storage')->getToken()->getUser();
             $audit->setUsername($admin->getUsername());
             $audit->setName($admin->getFirstname()." ".$admin->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("Logout");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
    }
}
