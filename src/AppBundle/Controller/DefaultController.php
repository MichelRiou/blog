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
        $posts = $repository->postsByDesc()->getResult();

        // replace this example code with whatever you need
        return $this->render('index.html.twig', [
            "posts" => $posts
        ]);
    }

    /**
     * @Route("/post/{id}", name="PostRoute")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function createPostAction($id ,Request $request)
    {
        $post = new Post();
        //$member=new Member();
        $repositoryType = $this->getDoctrine()->getRepository("AppBundle\Entity\Type");
        $typeDummy = $repositoryType->find($id);
        $post->setType($typeDummy);


        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $post->setCreatedAt(new \DateTime());
            $post->setMember($this->getUser());
            //

            $repositoryTheme = $this->getDoctrine()->getRepository("AppBundle\Entity\Theme");
            $themeDummy = $repositoryTheme->find(1);
            $post->setTheme($themeDummy);

            //type
            //member

            $em->persist($post);
            $em->flush();

            //Redirection pour eviter de rester en post
            return $this->redirectToRoute('homepage');
        }

        return $this->render("post.html.twig", [
            "form" => $form->createView(),
            "type" => $typeDummy
        ]);
    }
    /**
     * @Route("/modifPost/{id}", name="modifPostRoute")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function modifPostAction($id ,Request $request)
    {
        $post = new Post();
        //$member=new Member();
        $repositoryPost = $this->getDoctrine()->getRepository("AppBundle\Entity\Post");
        $post = $repositoryPost->find($id);
       // $post->setType($typeDummy);


        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            //$post->setCreatedAt(new \DateTime());
           // $post->setMember($this->getUser());
            //

           // $repositoryTheme = $this->getDoctrine()->getRepository("AppBundle\Entity\Theme");
            //$themeDummy = $repositoryTheme->find(1);
           // $post->setTheme($themeDummy);

            //type
            //member

            $em->persist($post);
            $em->flush();

            //Redirection pour eviter de rester en post
            return $this->redirectToRoute('homepage');
        }

        return $this->render("modifpost.html.twig", [
            "form" => $form->createView(),
           // "type" => $typeDummy
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
