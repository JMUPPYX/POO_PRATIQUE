<?php
namespace Models;

abstract class Model {
    protected $pdo;
    protected $table;
    public function __construct() {
    $this->pdo = \Database::getPdo();
  }

  /** index.php : function findAllArticles() qui va nous permettre 
  * de récuperer les tous les articles de la table article
  * classer par date de création
  * @return array
  */
  public function findAll(?string $order = "") : array {
    $sql = "SELECT * FROM {$this->table}";
    if($order){
        $sql .= " ORDER BY "  . $order;
    }
    $resultats = $this->pdo->query($sql);
    // On fouille le résultat pour en extraire les données réelles
    $articles = $resultats->fetchAll();
    return $articles;
}

  /** article.php = findArticle(int $id):array = sert a recuperer 1 article 
    * par  son id et le contenu correspondant à l'id 
    * @param integer $id
    */
public function find(int $id){
    $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
  // On exécute la requête en précisant le paramètre :article_id 
    $query->execute(['id' => $id]);
  // On fouille le résultat pour en extraire les données réelles de l'article
    $item = $query->fetch();
    return $item;
  }

    /**  delete-comment function qui va permettre de recuperer l'id de 
        *l'article avant de le supprimer et qui ne renvoi rien
        * @param integer $id
        * @return void */ 
public function delete(int $id) : void {
    $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $query->execute(['id' => $id]);
  }
}