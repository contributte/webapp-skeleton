loc-postgres: loc-postgres-stop
	docker run -it -d -p 5432:5432 --name nutella_postgres -e POSTGRES_PASSWORD=nutella -e POSTGRES_USER=nutella postgres:10

loc-postgres-stop:
	docker stop nutella_postgres || true
	docker rm nutella_postgres || true

loc-adminer: loc-adminer-stop
	docker run -it -d -p 9999:80 --name nutella_adminer dockette/adminer:dg

loc-adminer-stop:
	docker stop nutella_adminer || true
	docker rm nutella_adminer || true

loc-web:
	NETTE_DEBUG=1 NETTE_ENV=dev php -S 0.0.0.0:8000 -t www

loc-web-prod:
	NETTE_ENV=prod php -S 0.0.0.0:8000 -t www
