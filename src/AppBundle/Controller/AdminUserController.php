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
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\Type\AdminUserProfileType;
use AppBundle\Form\Type\AdminUserChangePasswordType;
use AppBundle\Form\Type\AdminUserType;
use AppBundle\Entity\AdminUser;
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
        //Audit
       $audit = new Audit();
       $admin = $this->get('security.token_storage')->getToken()->getUser();
       $audit->setUsername($admin->getUsername());
       $audit->setName($admin->getFirstname()." ".$admin->getLastname());
       $audit->setFunctionType("Administrator");
       $audit->setEventType("Login");
       $em = $this->getDoctrine()->getManager();
       $em->persist($audit);
       $em->flush();
       $engine = $this->container->get('templating');
       $content = $engine->render('AppBundle:Admin:index.html.twig', array(
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
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(AdminUserProfileType::class, $user);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $em = $this->getDoctrine()->getManager();
             $em->persist($data);
             $em->flush();
             //Audit
             $audit = new Audit();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
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
    public function accountViewAction()
    {
             $user = $this->get('security.token_storage')->getToken()->getUser();
             //Audit
             $audit = new Audit();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("View Profile");
             $em = $this->getDoctrine()->getManager();
             $em->persist($audit);
             $em->flush();
        return $this->render(
            'AppBundle:Admin:profile_account.html.twig',array('user' => $user));
     } 
     
    /**
     * @Route("/profile_password/edit", name="admin_profile_password_edit")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function passwordEditAction(Request $request)
    {
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(AdminUserChangePasswordType::class, $user);
        $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $password = $this->get('security.password_encoder')->encodePassword($data, $data->getPlainPassword());
             $data->setPassword($password);
             $em = $this->getDoctrine()->getManager();
             $em->persist($data);
             $em->flush();
             //Audit
             $audit = new Audit();
             $audit->setUsername($user->getUsername());
             $audit->setName($user->getFirstname()." ".$user->getLastname());
             $audit->setFunctionType("Administrator");
             $audit->setEventType("Edit password");
             $em->persist($audit);
             $em->flush();
          return $this->redirect($this->generateUrl('admin_profile_account_view'));
         }
        return $this->render(
            'AppBundle:Admin:new_profile_password.html.twig',array('form' => $form->createView()));
     } 
    
     /**
     * @Route("/register", name="admin_user_registration")
     * @Template()
     */
     public function registerAction(Request $request)
    {
        $user= new AdminUser();
        $form = $this->createForm(AdminUserType::class, $user);
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
            $audit->setFunctionType("Administrator");
            $audit->setEventType("SignUp");
            $em->persist($audit);
            $em->flush();
          return $this->redirectToRoute('admin_home');
        }
        return $this->render(
            'AppBundle:Admin:register.html.twig',
            array('form' => $form->createView()));
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
    $content = $engine->render('AppBundle:Admin:login.html.twig',
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $audit->setUsername($user->getUsername());
        $audit->setName($user->getFirstname()." ".$user->getLastname());
        $audit->setFunctionType("Administrator");
        $audit->setEventType("Logout");
        $em = $this->getDoctrine()->getManager();
        $em->persist($audit);
        $em->flush();
    }
}
