Installation neo4j 

	wget -O - https://debian.neo4j.org/neotechnology.gpg.key | sudo apt-key add -
	echo 'deb https://debian.neo4j.org/repo stable/' | sudo tee /etc/apt/sources.list.d/neo4j.list 
 	sudo apt-get update
 	sudo apt-get install neo4j

Configuration

	Aller dans application/librairie/Neo.php et modifier "login" et "mdp"
	Aller dans application/config/config.php et modifier $config['base_url'] pour l'adresse du site par rapport au serveur
	Si vous changez le nom du dossier, il faut aussi le changer dans le .htaccess à la racine du site