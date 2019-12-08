#!/usr/bin/env bash
# Перед использованием этого скрипта, собери локально docker-image, в котором будут запускаться тесты

pwd=`pwd`

TAG="sakharovmaksim/acceptance_ui_tests_base_image:latest"
echo "Локальный запуск тестов, используя docker-image $TAG"

CONTAINER_NAME="my_local_acceptance_run"

# Устанавливаем обработчик сигнала, чтобы контейнер удалился, даже есть скрипт завершают досрочно
trap 'docker rm -f $CONTAINER_NAME' 2 15

docker pull $TAG

docker run -di --net=host --name=$CONTAINER_NAME -v $pwd:/local_project $TAG

# Копируем код тестов из --volume папки в папку с предустановленным vendor, чтобы не требовать vendor на хосте
docker exec $CONTAINER_NAME rsync -a /local_project/. /acceptance/ --exclude vendor --exclude tests_results --exclude tmp --exclude .git

# Запуск тестов из папки, в которой предустановлен vendor
docker exec $CONTAINER_NAME php /acceptance/vendor/bin/atcorerun run

# Копируем test_result обратно в --volume-папку local_project
docker exec $CONTAINER_NAME rsync -a /acceptance/tests_results/ /local_project/tests_results/

echo "Останавливается и удаляется docker-контейнер $CONTAINER_NAME"
docker rm -f $CONTAINER_NAME