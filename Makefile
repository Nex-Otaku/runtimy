ifneq (,$(wildcard ./.env))
    include .env
    export
endif

no-command:
	@echo Usage: make [scenario]

# Reload Nginx Config
nginx-reload:
	docker exec ${NGINX_CONTAINER} nginx -s reload

docker-rebuild: docker-down docker-build docker-up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build

# --------------- Development ----------------

# Unit Tests
test:
	docker exec -ti ${PHP_CONTAINER} php artisan test

# Build front
front:
	npm install && npm run dev


# Выполняем команду "artisan" в docker-контейнере PHP.
ARTISAN = docker exec ${PHP_CONTAINER} php artisan

migrate:
	$(ARTISAN) migrate

seed:
	$(ARTISAN) migrate:fresh --seed

tinker:
	docker exec -ti runtimy_php_container php artisan tinker

# -------------- Deploy to Prod -------------------

# Выполняем команду по SSH на удалённом хосте.
RUN_SSH_PROD = ssh ${PROD_SSH_USER}@${PROD_SSH_HOST} -p ${PROD_SSH_PORT}
RUN_SSH_DEMO = ssh ${DEMO_SSH_USER}@${DEMO_SSH_HOST} -p 22

# Выкатываем обновление на демо
deploy-demo:
	$(RUN_SSH_DEMO) './${PROJECT_DIR}/bin/deploy.sh'

# Выкатываем быстрое обновление на демо
fast-deploy-demo:
	$(RUN_SSH_DEMO) './${PROJECT_DIR}/bin/fast-deploy.sh'

# Выкатываем обновление фронтенда на демо
deploy-demo-frontend:
	$(RUN_SSH_DEMO) './${PROJECT_DIR}/bin/static-demo-deploy.sh'

# Обновляем контейнеры в демо:
rebuild-docker-on-demo:
	$(RUN_SSH_DEMO) './${PROJECT_DIR}/bin/rebuild-docker.sh'

# Публичный ключ SSH
show-ssh-key-demo:
	$(RUN_SSH_DEMO) 'cat ~/.ssh/id_rsa.pub'

# Устанавливаем проект на демо
install-demo:
	$(RUN_SSH_DEMO) '[ -d "./${PROJECT_DIR}" ] && echo "Repository exists, skipping cloning" || git clone ${BITBUCKET_USER}@bitbucket.org:${BITBUCKET_USER}/${BITBUCKET_REPO}.git ${PROJECT_DIR}'
#	$(RUN_SSH_DEMO) 'chmod +x ./${PROJECT_DIR}/bin/install.sh && ./${PROJECT_DIR}/bin/install.sh'

# Тянем изменения из Git
pull-demo:
	$(RUN_SSH_DEMO) 'cd ${PROJECT_DIR} && git pull'


# Выкатываем обновление на прод
deploy-prod:
	$(RUN_SSH_PROD) './${PROJECT_DIR}/bin/deploy.sh'

# Выкатываем быстрое обновление на прод
fast-deploy-prod:
	$(RUN_SSH_PROD) './${PROJECT_DIR}/bin/fast-deploy.sh'

# Обновляем контейнеры в проде:
rebuild-docker-on-prod:
	$(RUN_SSH_PROD) './${PROJECT_DIR}/bin/rebuild-docker.sh'

# Публичный ключ SSH
show-ssh-key-prod:
	$(RUN_SSH_PROD) 'cat ~/.ssh/id_rsa.pub'

# Устанавливаем проект на прод
install-prod:
	$(RUN_SSH_PROD) '[ -d "./${PROJECT_DIR}" ] && echo "Repository exists, skipping cloning" || git clone ${BITBUCKET_USER}@bitbucket.org:${BITBUCKET_USER}/${BITBUCKET_REPO}.git ${PROJECT_DIR}'
	$(RUN_SSH_PROD) 'chmod +x ./${PROJECT_DIR}/bin/install.sh && ./${PROJECT_DIR}/bin/install.sh'

# Тянем изменения из Git
pull-prod:
	$(RUN_SSH_PROD) 'cd ${PROJECT_DIR} && git pull'

