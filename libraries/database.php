<?php
    /**
 * 1. Connexion à la base de données avec PDO
 * Attention, on précise ici deux options :
 * - Le mode d'erreur : le mode exception permet à PDO de nous prévenir violament quand on fait une connerie ;-)
 * - Le mode d'exploitation : FETCH_ASSOC veut dire qu'on exploitera les données sous la forme de tableaux associatifs
 * Return une connexion à la  BDD 
 * @return PDO quelque chose de type
 */
function getPdo(): PDO 
{
$pdo = new PDO('mysql:host=localhost;dbname=blogpoo;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    return $pdo;
}
  /**index.php : function findAllArticles() qui va nous permettre de récuperer les tous les articles de la table article
   * classer par date de création
   * @return array
  */
function findAllArticles() : array {
    $pdo = getPdo();
    $resultats = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC');
    // On fouille le résultat pour en extraire les données réelles
    $articles = $resultats->fetchAll();
    return $articles;
}
/** article.php = findArticle(int $id):array = sert a recuperer 1 article via son id et le contenu correspondant à l'id 
 * sous forme de tableau */
function findArticle(int $id){
    $pdo = getPdo();
    $query = $pdo->prepare("SELECT * FROM articles WHERE id = :article_id");
// On exécute la requête en précisant le paramètre :article_id 
    $query->execute(['article_id' => $id]);
// On fouille le résultat pour en extraire les données réelles de l'article
    $article = $query->fetch();
    return $article;
}
/** article.php findAllComments nous permet de recuperer les commentaires qui corresponde à l'identifiant de l'article 
 * @return array
*/
function findAllComments(int $article_id) : array {
    $pdo = getPdo();
    $query = $pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id");
    $query->execute(['article_id' => $article_id]);
    $commentaires = $query->fetchAll();
    return $commentaires;
}
/*delete-article.php function qui recevra l'id de l'article et ne renvoi rien*/
function deleteArticle(int $id) : void {
    $pdo = getPdo();
    $query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
    $query->execute(['id' => $id]);
}
/* Vérification de l'existence du commentaire par son id */
function findComment(int $id){
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM comments WHERE id = :id');
    $query->execute(['id' => $id]);
    $comment = $query->fetch();
    return $comment;
}

/* delete-comment function qui va permettre de recuperer l'id de 
*l'article avant de le supprimer et qui ne renvoi rien*/
    function deleteComment (int $id) : void {
        $pdo = getPdo();
        $query = $pdo->prepare('DELETE FROM comments WHERE id = :id');
        $query->execute(['id' => $id]);
    }
/* save-content / function qui va permettre d'inserer un commentaire dans chaque champ de type string string $author,
* string $content,string $article_id */
    function insertComment(string $author,string $content,string $article_id) : void {
        $pdo = getPdo();
        $query = $pdo->prepare('INSERT INTO comments SET author = :author, 
        content = :content, article_id = :article_id, created_at = NOW()');
        $query->execute(compact('author', 'content', 'article_id'));
    }