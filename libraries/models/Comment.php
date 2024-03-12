<?php
namespace Models;

class Comment extends Model {
  protected  $table = "comments";
/** article.php findAllComments nous permet de recuperer les commentaires 
* qui correspondent à l'identifiant de l'article 
* @return array
*/
public function findAllWithArticle(int $article_id) : array {
    $query = $this->pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id");
    $query->execute(['article_id' => $article_id]);
    $commentaires = $query->fetchAll();
    return $commentaires;
}

/**  save-comment / function qui va permettre d'inserer un commentaire dans la base de données
* @param string $author
* @param string $content
* @param integer $article_id
*/
    public function insert(string $author,string $content,string $article_id) : void {
        $query = $this->pdo->prepare('INSERT INTO comments SET author = :author, 
        content = :content, article_id = :article_id, created_at = NOW()');
        $query->execute(compact('author', 'content', 'article_id'));
    }

}