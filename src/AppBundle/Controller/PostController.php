<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Form\PostFindType;

/**
 * Post controller.
 *
 * @Route("/post")
 */
class PostController extends AppController
{
    /**
     * Lists all Post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')->findAll();
        $findForm = $this->createFindForm(new Post());

        return $this->render('post/index.html.twig', array(
            'findForm' => $findForm->createView(),
            'posts' => $posts,
        ));
    }

    /**
     * Find Post entities.
     *
     * @Route("/find", name="post_find")
     * @Method({"GET", "POST"})
     */
    public function findAction(Request $request)
    {
        $entity = new Post();
        $findForm = $this->createFindForm($entity);

        // 検索フォームに入力データをセット
        if ($request->getMethod() === 'POST') {
            $findForm->handleRequest($request);
        } else {
            $findForm->submit($request->getSession()->get(Constants::SESSION_SAVE_KEYWORD));
        }

        // 入力チェック
        $hasError = false;
        // if ($request->getMethod() === 'POST') {
        //     if ($this->isEmptyByForm($findForm)) {
        //         $this->showWarningMessage('message.warning.findForm.notBlank');
        //         $hasError = true;
        //     }
        // }

        $pagination = array();
        if (!$hasError) {

            // 検索
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('AppBundle:Post')->findByForm(
                $findForm,
                array(),
                array('updatedAt' => 'DESC')
            );
            $pagination = $this->getPaginator($entities, $request->query->get('page', 1));

            // 検索結果が空の場合はメッセージを表示
            if (empty($entities)) {
                $this->showWarningMessage('message.warning.findForm.notResult');
            }
        }

        return array(
            'findForm' => $findForm->createView(),
            'pagination' => $pagination,
        );
    }

    /**
    * Creates a form to find a Post entity.
    *
    * @param Post $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFindForm(Post $entity)
    {
        $form = $this->createForm(PostFindType::class, $entity, array(
            'action' => $this->generateUrl('post_find', array('id' => $entity->getId()))
        ));

        return $form;
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a Post entity.
     *
     * @param Post $post The Post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
