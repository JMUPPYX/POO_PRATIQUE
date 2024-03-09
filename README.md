**PRATIQUE POO**

La POO permet d'avoir un meilleur organisation grâce aux fonctions.

**Refactoriser le code sous forme de fonction :**

La factorisation permet de centraliser le code.

**index.php**
Connexion à la base de données avec la classe PDO.
Le tableau d'option indique : 
- Quand il y a une erreur renvoi moi l'erreur
- Attribut du mode exploitation par default comment afficher les données qui reviennent  de la requête SQL FETCH_ASSOC = sous forme de tableau associatif.

- Effectuer la requête SQL pour récuperer les articles

- **$pageTitle** a comme valeur "Accueil"
- **ob_start();** = est utilisé pour temporiser la sortie et permettre de contrôler ce qui est affiché avant qu’il ne soit envoyé au client. 

- **require** est dans le tampon , donc on ne peut pas afficher directement. On utilise **echo ob_get_clean()** pour afficher ce qui est dans le    
- **$pageContent = ob_get_clean();**  = lit le contenu courant du tampon de sortie et l’efface en même temps.On utilise la boucle  (**require('templates/articles/index.html.php');**) **Foreach** qui va nous permettre d'afficher notre article suite à notre requête : 

````html
<h1>Nos articles</h1>

<?php foreach ($articles as $article) : ?>
    <h2><?= $article['title'] ?></h2>
    <small>Ecrit le <?= $article['created_at'] ?></small>
    <p><?= $article['introduction'] ?></p>
    <a href="article.php?id=<?= $article['id'] ?>">Lire la suite</a>
    <a href="delete-article.php?id=<?= $article['id'] ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)">Supprimer</a>
<?php endforeach ?>
````

- **require('templates/layout.html.php');** est notre header:
````html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mon superbe blog - <?= $pageTitle ?></title>
</head>

<body>
    <?= $pageContent ?>
</body>

</html>
````

Afin d'éviter les répétitions et avoir une meilleur évolution et gestion du code nous allons centraliser les élèments qui se répetent dans un dossier nommé libraries, qui contient le fichier database.php = la connexion à la base de données 
index.php
````php
$pdo = new PDO('mysql:host=localhost;dbname=blogpoo;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

````
database.php
````php
function getPdo(): PDO 
{
$pdo = new PDO('mysql:host=localhost;dbname=blogpoo;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    return $pdo;
}
````
index.php : le require nous permet d'inclure notre fonction pour la connexion et **$pdo = getPdo()**  nous retourne le resultat de la fonction 
````php
require_once ('libraries\database.php');
$pdo = getPdo();
````
La même démarche est effectuée dans les fichers qui  vont utiliser cette fonction pour accéder à la base de données.(article,save comment,delete comment,delete article)

**index.php**
Pour l'affichage html nous allons également refactoriser le code en fonction :
````php
$pageTitle = "Accueil";
ob_start();
require('templates/articles/index.html.php');
$pageContent = ob_get_clean();

require('templates/layout.html.php');

**article.php**
$pageTitle = $article['title'];
ob_start();
require('templates/articles/show.html.php');
$pageContent = ob_get_clean();

require('templates/layout.html.php');
````
**utils.php**
Le $path = 'articles/show.php' est le chemin du fichier  qui va être utilisé.
Les variables $article, $commentaires, $article_id et $pageTitle pour que les variables soit reconnu par la fonction et non defini un array $variables = [] est crée dans la fonction 
````php
// render ('articles/show')
function render(string $path, array $variables = []){
    ob_start();
    require('templates/'. $path . '.html.php');
    $pageContent = ob_get_clean();

    require('templates/layout.html.php');
}
````
**article.php**
Lors de l'appel de fonction dans le fichier article.php nous indiquons  le $path et les variables qui seront nécéssaires pour l'affichage du fichier show.html.php sous forme de tableau associatif.
````php
render('articles/show',
    ['pageTitle'=>$pageTitle, 
     'article'=> $article,
     'commentaires'=>$commentaires, 
    'article_id' =>$article_id]);
````
Pour éviter la répétition du tableau associatif à partir du nom des variables désirées on peut utiliser la **fonction compact**:
````php
render('articles/show',compact('pageTitle', 'article','commentaires', 'article_id'));
//équivalent : compact('pageTitle = > $pageTitle, etc)
````

**utils.php**
Pour passer le tableau association en variable il faut utiliser la fonction  **extract()**,elle créera automatiquement des variables avec ces noms et les valeurs correspondantes, il sortira les clés et valeurs du tableau associatif sous forme de variable

````php
// render ('articles/show')
function render(string $path, array $variables = []){
    extract($variables);
    ob_start();
    require('templates/'. $path . '.html.php');
    $pageContent = ob_get_clean();

    require('templates/layout.html.php');
}
````
index.php : ne pas oublier le require du fichier utils .php qui contient la **fonction render()**:

````php
require_once ('libraries/utils.php');

$pageTitle = "Accueil";
render('articles/index', compact('pageTitle', 'articles'));
````

Un fonction de redirection va être créer les fichiers concernés sont delete-article,delete-comment, save-comment :
**:void indique que la fonction ne retourne rien**
````php
function redirect(string $url): void {
    header("Location: $url");
    exit();
}
````
````php
/**
 * 5. Redirection vers la page d'accueil
 */
header("Location: index.php");
exit();
````
````php
/**
 * 5. Redirection vers l'article en question
 */
header("Location: article.php?id=" . $article_id);
exit();
````
````php
// 4. Redirection vers l'article en question :
header('Location: article.php?id=' . $article_id);
exit();
````
Ne pas oublier le require sur chaque fichier concerné
````php
require_once('libraries/utils.php');
````

````php
// delete-article
redirect("index.php");
// delete-comment
redirect("article.php?id=" . $article_id);
// save-comment
redirect("article.php?id=" . $article_id);
````
**Refactoriser des requêtes SQL**:

**index.php**
````php
$resultats = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC');
// On fouille le résultat pour en extraire les données réelles
$articles = $resultats->fetchAll();
````
Nous créeons une fonction pour centraliser pour obtenir le résulat de notre requête :
 **$pdo = getPdo();** PDO sera ce que va retourner la **function Pdo**, en effet **$pdo** de notre function findAllArticles n'était pas défini
 **: array** indique que la fonction va nous retourner un tableau
**database.php**
````php
  /**function findAllArticles() qui va nous permettre de récuperer les tous les articles de la table article
   * classer par date de création
   * @return array
  */
function findAllArticles(): array {
    $pdo = getPdo();
    $resultats = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC');
    // On fouille le résultat pour en extraire les données réelles
    $articles = $resultats->fetchAll();
    return $articles;
}
````
La fonction est appelée  dans index.php et utilisée partout où il faudrait afficher toutes les publications.
De plus  **$pdo = getPdo();** peut être effacé car la fonction pour la connexion à la BDD est intégré à notre fonction
````php
$articles = findAllArticles();
````
l'index.php est beaucoup plus lisible...
````php
/**
 * CE FICHIER A POUR BUT D'AFFICHER LA PAGE D'ACCUEIL !
 * 
 * On va donc se connecter à la base de données, récupérer les articles du plus récent au plus ancien (SELECT * FROM articles ORDER BY created_at DESC)
 * puis on va boucler dessus pour afficher chacun d'entre eux
 */
require_once ('libraries/database.php');
require_once ('libraries/utils.php');

/**
 * 2. Récupération des articles
 */
$articles = findAllArticles();

/**
 * 3. Affichage
 */
$pageTitle = "Accueil";
render('articles/index' , compact('pageTitle', 'articles'));
````

article.php
nous allon effectuer la même démarche  mais avec une variable $id, ce qui permet d'afficher un seul article plutôt que contient une page unique pour afficher un seul.
````php
$query = $pdo->prepare("SELECT * FROM articles WHERE id = :article_id");
// On exécute la requête en précisant le paramètre :article_id 
$query->execute(['article_id' => $id]);
// On fouille le résultat pour en extraire les données réelles de l'article
$article = $query->fetch();
````
**database.php**
````php
/**findArticle(int $id):array = sert à recuperer un article via son id et le contenu correspondant à l'id*/
function findArticle(int $id){
    $pdo = getPdo();
    $query = $pdo->prepare("SELECT * FROM articles WHERE id = :article_id");
// On exécute la requête en précisant le paramètre :article_id 
    $query->execute(['article_id' => $id]);
// On fouille le résultat pour en extraire les données réelles de l'article
    $article = $query->fetch();
    return $article;
}
````
**article.php**
````php
 require_once ('libraries/database.php');
 require_once ('libraries/utils.php');
/**
 * 1. Récupération du param "id" et vérification de celui-ci
 */
// On part du principe qu'on ne possède pas de param "id"
$article_id = null;

// Mais si il y'en a un et que c'est un nombre entier, alors c'est cool
if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
    $article_id = $_GET['id'];
}

// On peut désormais décider : erreur ou pas ?!
if (!$article_id) {
    die("Vous devez préciser un paramètre `id` dans l'URL !");
}

/**
 * 3. Récupération de l'article en question
 * On va ici utiliser une requête préparée car elle inclue une variable qui provient de l'utilisateur : Ne faites
 * jamais confiance à ce connard d'utilisateur ! :D
 */
$article = findArticle($article_id);

/**
 * 4. Récupération des commentaires de l'article en question
 * Pareil, toujours une requête préparée pour sécuriser la donnée filée par l'utilisateur (cet enfoiré en puissance !)
 */
$commentaires = findAllComments($article_id);

/**
 * 5. On affiche 
 */
$pageTitle = $article['title'];

render('articles/show',compact ('pageTitle', 'article','commentaires', 'article_id'));
````
**delete-article.php**
````php
$query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
$query->execute(['id' => $id]);
````
**database.php**
````php
/*function qui recevra l'id de l'article et ne renvoi rien*/
function deleteArticle(int $id) : void {
    $pdo = getPdo();
    $query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
    $query->execute(['id' => $id]);
}
````
**delete-comment.php**
````php
    $query = $pdo->prepare('SELECT * FROM comments WHERE id = :id');
    $query->execute(['id' => $id]);
````
**database.php**
````php
/* Vérification de l'existence du commentaire par son id */
function findComment(int $id){
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM comments WHERE id = :id');
    $query->execute(['id' => $id]);
    $comment = $query->fetch();
    return $comment;
}
````
````php
$query = $pdo->prepare('DELETE FROM comments WHERE id = :id');
    $query->execute(['id' => $id]);
````
````php
/* fichier delete-comment function qui va permettre de recuperer 
*l'id de l'article avant de supprimer le commentaire*/
function deleteComment (int $id) : void {
    $pdo = getPdo();
    $query = $pdo->prepare('DELETE FROM comments WHERE id = :id');
    $query->execute(['id' => $id]);
}
````
**save-comment**
````php
 $query = $pdo->prepare('INSERT INTO comments SET author = :author, 
        content = :content, article_id = :article_id, created_at = NOW()');
        $query->execute(compact('author', 'content', 'article_id'));
````
**database.php**
````php
/* save-content / function qui va permettre d'inserer un commentaire dans chaque champ de type string string $author,
* string $content,string $article_id */
    function insertComment(string $author,string $content,string $article_id) : void {
        $pdo = getPdo();
        $query = $pdo->prepare('INSERT INTO comments SET author = :author, 
        content = :content, article_id = :article_id, created_at = NOW()');
        $query->execute(compact('author', 'content', 'article_id'));
    }

````



