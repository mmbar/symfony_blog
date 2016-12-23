<?php 
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;

class BlogController extends Controller
{
    
    
    /**
     * @Route("/", name="blog_home")
     */
    public function indexAction(Request $request)
    {
        
    	$posts = $this->getDoctrine()->getRepository('AppBundle:Post')
    				->getLatestPost();
    				
        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
    
    
    
    /**
     * @Route("/show/{id}", name="show_post",
     * 		requirements={
     * 				"id"="\d+"
     * 				}
     * 		)
     */
    public function showAction(Request $request,$id)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')
                    ->find($id);
        if ( $post ){
            
        	$comment = new Comment();
            $comment->setPost($post);
            $comment->setCreated(new \DateTime());
            
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
            	
                $comment->setCreated(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                
                return $this->redirectToRoute('show_post', [
                    'id' => $id
                ]);
            }
        
        }else {
        	
            throw $this->createNotFoundException();
            
        }
     
        return $this->render('blog/show_post.html.twig',[
            'post'=>$post,
            'form'=>$form->createView()
        ]);
    }
    
    
    /**
     * @Route("/list", name="list_posts")
     */
    public function listAction()
    {
    	
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')
                    ->findAllOrderByDate();
        
        return $this->render('blog/list_posts.html.twig',[
            'posts' => $posts
        ]);
        
    }
    
   public function recentlyCommentedAction()
   {
   	
   		$posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Comment')
   					->getRecentlyCommented();
   	
   		return $this->render('blog/_recently_commented.html.twig',[
   				'posts' => $posts
   		]);
   }
   
   public function mostCommentedAction()
   {
   		
   		$posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Comment')
   					->getMostCommented();
   		
   		return $this->render('blog/_most_commented.html.twig',[
   				'posts' => $posts
   		]);
   }
}
