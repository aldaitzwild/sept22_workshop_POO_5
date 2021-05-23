# Travaux d'Héraclès #5 : la biche de Cérynie
 
Prérequis : cloner ce *repository*.

Fais un `composer install`

La prochaine mission d'Héraclès est de capturer la fantastique biche de Cérynie, mais sans lui faire aucun mal au risque de provoquer la colère d'Artémis.

Pour ce nouvel atelier, tu reprends là encore où tu t'étais arrêté à l'étape précédente. Le héros peut se déplacer et attaquer les monstres sur la carte.

## C'est la tuile

La carte justement, elle est un peu triste. Tu te trouves sur la colline de Cérynie, entouré d'herbes, d'arbustes et de cours d'eau. Il va falloir représenter tout cela.

Commence par créer une nouvelle classe `Tile`. Une tuile va avoir des coordonnées `$x` et `$y` ainsi qu'une `$image` pour la représenter (avec les *getters* et *setters* correspondants. 
Tu remarqueras que la classe `Fighter` possède également ces mêmes méthodes. C'est logique puisque les tuiles ou les combattants doivent pouvoir être affichés et positionnés sur une carte (arène). Notre arène manipule donc des objets cartographiables et qui **doivent** impérativement l'être. Pous s'en assurer, créé une interface "Mappable" contenant ces 6 méthodes, et fait en sorte que `Tile` et `Fighter` l'implémente (attention pour `getImage()` n'oublie pas de concaténer le chemin complet vers l'image).

> Rappel : une interface ne contient que des signatures de méthodes (pense au typages), tu ne dois donc y mettre ni propriétés, ni "corps" pour les méthodes.

Dans `Tile` ajoute un constructeur permettant de spécifier les coordonnées de la tuiles en x et y.

Sur ta carte, tu vas avoir plusieurs type de tuiles représentant les différent éléments du paysage (herbe, eau, arbre...), chacune ayant ses propres spécifités (traversable, modifiable...). Tu ne seras jamais amené à instancier directement une classe `Tile`, mais toujours quelques chose de plus précis. Tu l'auras reconnus, nous avons affaire ici à une classe abstaite ! Modifie `Tile` en conséquence.

Tu vas créer autant de nouvelle classe que de type de tuile. Créé pour commencer une classe `Grass` et une `Water` toutes deux héritant de `Tile`. Pour le moment, spécifie uniquement une valeur par défaut à la propriété `$image` (à passer en *protected* par la même occasion), respectivement *grass.png* et *water.jpg*

Dans la classe `Arena`, ajoute une propriété `$tiles` de type *array*. Ajoute également un troisième paramètre `$tiles` optionnel, dans le constructeur.

Actualise la page, tu devrais voir apparaître de l'herbe et de l'eau.

# Méfie toi de l'eau qui dort

Si Héraclès peut se déplacer sans problème sur l'herbe (ou sur un sol sans tuile définie), il ne peut pas se déplacer sur l'eau.

Commence par ajouter une propriété `$isThrowable` (booléen *true* par défaut) à `Tile`.

Dans la classe `Water`, passe `$isThrowable` à *false*;

Maintenant, tu vas modifier légèrement le comportement de la méthode `move()` d'`Arena`.
1. Créer une méthode privée `getTile($x, $y)` permettant de récupérer une tuile en fonction de ses coordonéés
2. Récupère la tuile correspondant à la destination potentielle du héros.
3. Vérifie que la tuile est traversable grace à la nouvelle propriété `$isThrowable`.
4. Si oui, le déplacement continue, si non lance une exception.

# Remplissage de la carte

Ajoute une nouvelle classe `Bush`, fille de `Tile`, non traversable et affichant *bush.png*.

Réinitialise le jeu, tu vois que la carte commence à bien se remplir !

# Biche, ô ma biche

La biche est innaccessible cachée derrière tous ces arbustes. Héraclès se cache, à l'affut, attendant qu'elle sorte de là. Pour cela, il faudrait déjà que celle-ci puisse bouger ! Pour cela, ajoutons un peu de code à la méthode `move()` d'`Arena`.

1. Pour commencer, simplifions un peu l'instanciation des monstres en ajouts `$x` et `$y` comme paramètres dans le constructeur de `Fighter`. De plus, enlève `$image` des paramètres (car il ya déjà une valeur par défaut). Cependant pour que l'image du `Hero` s'affiche toujours, ajoute une propriété `$image` pour indiquer l'images d'Héraclès à afficher (attention à utiliser la bonne visiblité pour la propriété).

2. Modifie en conséquence *index.php*. Tu n'as plus besoin d'utilise les *setters* pour $x et $y ni de préciser l'image pour Heracles.

3. La biche va avoir un comportement un peu particulier, notamment car elle va pouvoir bouger. Crée un class `Hind` (pour Biche) fille de `Monster`. Ajoute là encore une propriété `$image` avec pour valeur *hind.svg*.

> Réflechissons un instant à l'interface `Mappable`. Celle-ci contient les getters ET les setters pour les coordonnées. Or, pour afficher un élément sur une carte, seuls les *getters* sont réellement utilies. Par contre, les *setters* seront nécessaires pour modifier ces coordonnéesen dehors de la position initiale à l'instanciation. C'est ce qui arrive si l'élément peut bouger. Tachons de respecter le **I** de SOLID (Interface ségregation).

5. Créer une nouvelle interface `Movable` et tu vas y transférer uniquement `setX()` et `setY()`. Attention cependant, un élément Movable est forcément Mappable ! Donc Movable doit également étendre Mappable. Fais en sorte que `Hero` et `Hind` l'implémente. Cela signifie que seuls les Héraclès et la biche peuvent bouger, pas d'autres monstres éventuels. 

6. A chaque fois que le héros a fini de bouger, les monstres ayant la capacité de bouger vont le faire. Dans la méthode `move()`, une fois le héro déplacé (donc après l'utilisation de `setX()` et `setY()`), boucle sur les monstres implémentant `Movable`. Pour chacun de ces monstres, choisi une direction aléatoirement (appuie toi sur la fonction PHP array_rand() et la constantes DIRECTIONS), puis bouge le monstre dans la direction en réutilisant la méthode `move()`. Ainsi, toutes les vérifications déjà réalisée pour le déplacement du héros (limites de la carte, case Throwable, etc.) sont réutilisées !

7. Dans les paramètres de `move()` type plutot avec Movable que Fighter, afin de s'assurer justement que le mouvement puisse s'effectuer.

8. Ici, `move()` est lancée par le Hero une première fois, puis par tous les monstres. Donc à chaque mouvement d'un monstre, une nouvelle boucle est effectuée sur les monstres, *etc.* entraînant un comportement anormal. Pour éviter cela, il faut seulement s'assurer que la boucle sur les monstres se fasses uniquement dans le cas où ce qui est déplacé est une instance de `Hero`.

> Ajoute un Monster à la carte en plus de Hind, si tout s'est bien passé, tu verras que seul la biche bouge !

Dernier point, notre biche bouge mais est bloquée derrière les buissons. Modifions cela. Si Héraclès ne sait comment traverser les épineux buissons de la forêt, cela n'est pas un problème pour la biche. Nous allons donc faire en sorte que la tuile Bush soit non traversable, **sauf** pour un objet Hind ! 

Pour cela, modifie la méthode getIsThrowable de Tile, pour qu'elle prenne en paramètre une instance de Movable. Dans Arena, lorsque tu utilise getIsThrowable(), passe en paramètre ce qui est en train d'essayer de bouger sur cette tuile.

Dans Bush maitenant, redéfini getIsThrowable() afin que la fonctionne revoie $isThrowable (qui doit être à *false*) mais qu'il renvoie aussi *true* si et seulement si $movable est une instance de Hind. En d'autres terme, la tuile n'est pas traversable sauf si c'est une biche qui essaie ! 

Effectue plusieurs mouvements, tu vas voir que la biche devrait finir par traverser les buissons, c'est le moment tant attendu, attrape là !

> Nous ne chercherons pas à implémenter le fait qu'Héraclès capture effectivement la Biche car cela demanderait pas mal d'efforts supplémentaires, mais comme toujours, si tu souhaites essayer, n'hésite pas !