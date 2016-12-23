<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Comment;

class AdminController extends Controller
{
    
    
    /**
     * @Route("/add", name="new_post")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newPostAction(Request $request)
    {
        $post = new Post();
        $post->setAuthor("Author");
        $post->setCreated(new \DateTime());
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $post->setCreated(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            
            
            return $this->redirectToRoute("list_posts");
        }
        
        return $this->render('admin/new_post.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    
    
    /**
     * @Route("/crud", name="blog_crud")
     * @Route("/del/", name="delete_path")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function crudAction()
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')
                    ->findAllOrderByDate();
        
        return $this->render('admin/crud.html.twig',[
            'posts'=>$posts
        ]);
    }
    
    
    
    /**
     * @Route("/edit/{id}", name="edit_post", 
     * 			requirements={
     * 				"id"="\d+"
     * 				}
     * 			)
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editPostAction(Request $request,$id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')
                    ->find($id);
        
        if ($post) {
        
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);
            
            if ($form->isSubmitted()) {
            	
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                
                return $this->redirectToRoute('blog_crud');
            }
        
        } else {
        
            throw $this->createNotFoundException();
        
        }
        
        return $this->render('admin/edit_post.html.twig',[
            'form'=> $form->createView(),
            'post'=> $post
        ]);
    }
    
    
    
    
        
    /**
     * @Route("/del/{id}", name="del_post",
     * 			requirements={
     * 				"id"="\d+"
     * 				}
     * 			)
     * @Security("has_role('ROLE_ADMIN')") 
     */
    public function delPostAction($id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')
                ->find($id);
        if ($post) {
        	
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            
        } else {
        	
            throw $this->createNotFoundException();
            
        }
        
        return $this->redirectToRoute('blog_crud');
        
    }
    
    
    
    
    
    /**
     * @Route("/del/{post_id}/{comment_id}", name="del_comment",
     * 		requirements={
     * 				"post_id"="\d+",
     * 				"comment_id"="\d+"
     * 				}
     *      )
     * @Security("has_role('ROLE_ADMIN')")
     */
    
    public function delCommentAction($post_id,$comment_id)
    {
    	
    	$comment = $this->getDoctrine()->getRepository('AppBundle:Comment')
    				->find($comment_id);
    	
    	if ($comment){
    	
    		$em = $this->getDoctrine()->getManager();
    		$em->remove($comment);
    		$em->flush();
    	
    	} else {
    		
    		throw $this->createNotFoundException();
    		
    	}
    	return $this->redirectToRoute('edit_post',['id'=>$post_id]);
    }
    
}
