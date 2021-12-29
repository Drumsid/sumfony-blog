<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsController extends AbstractController
{
    private PostRepository $postRepository;
    private ManagerRegistry $managerRegistry;

    public function __construct(PostRepository $postRepository, ManagerRegistry $managerRegistry)
    {
        $this->postRepository = $postRepository;
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/posts', name: 'blog_posts')]
    public function posts(): Response
    {
        $posts = $this->postRepository->findAll();
        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('posts/new', name: 'new_blog_post')]
    public function addPost(Request $request, SluggerInterface $slugger): \Symfony\Component\HttpFoundation\RedirectResponse|Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugger->slug($post->getTitle()));
            $post->setCreatedAt(new \DateTime());

            $em = $this->managerRegistry->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('blog_posts');
        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('posts/{slug}/edit', name: 'blog_post_edit')]
    public function edit(Request $request, Post $post, SluggerInterface $slugger): \Symfony\Component\HttpFoundation\RedirectResponse|Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugger->slug($post->getTitle()));

            $em = $this->managerRegistry->getManager();
            $em->flush();

            return $this->redirectToRoute('blog_show', [
                'slug' => $post->getSlug()
            ]);
        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('posts/{slug}/delete', name: 'blog_post_delete')]
    public function delete(Post $post): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('blog_posts');
    }

    #[Route('posts/{slug}', name: 'blog_show')]
    public function post(Post $post): Response
    {
        return $this->render('posts/show.html.twig', [
            'post' => $post
        ]);
    }

}
