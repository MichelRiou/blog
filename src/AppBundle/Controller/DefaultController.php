<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Member;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository("AppBundle\Entity\Post");
        $posts = $repository->findAll();

        // replace this example code with whatever you need
        return $this->render('index.html.twig', [
            "posts" => $posts
        ]);
    }

    /**
     * @Route("/post", name="PostRoute")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createPostAction(Request $request)
    {
        $post = new Post();
        $member=new Member();


        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $post->setCreatedAt(new \DateTime());
            //theme
            //type
            //member

            $em->persist($post);
            $em->flush();

            //Redirection pour eviter de rester en post
            return $this->redirectToRoute('homepage');
        }

        return $this->render("post.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/member-login",name="member_login_route")
     */
    public function memberLoginAction()
    {
        //Récupération des erreurs éventuelles
        $securityUtils = $this->get('security.authentication_utils');
        return $this->render("login-form.html.twig",
            ["action" => $this->generateUrl("member_check_route"),
                "error" => $securityUtils->getLastAuthenticationError(),
                "userName" => $securityUtils->getLastUsername()]
        );

    }
}
