<?php
// Filename: /module/Blog/src/Blog/Service/PostService.php
namespace Blog\Service;

//use Blog\Model\Post;
use Blog\Mapper\PostMapperInterface;
use Blog\Model\PostInterface;

class PostService implements PostServiceInterface
{
    /**
    * @var \Blog\Mapper\PostMapperInterface
    */
    protected $postMapper;

    /**
    * @param PostMapperInterface $postMapper
    */
    public function __construct(PostMapperInterface $postMapper)
    {
      $this->postMapper = $postMapper;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllPosts()
    {
      return $this->postMapper->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function findPost($id)
    {
      $post =  $this->postMapper->find($id);

      if (!$post) {
        throw new InvalidArgumentException("No Post Found!");
      }

      return $post;
    }

    public function savePost(PostInterface $post)
    {
        return $this->postMapper->save($post);
    }
}