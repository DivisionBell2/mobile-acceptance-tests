#!/bin/bash
#

echo "Удаляем папку vendor, тем самым мы установим все самое актуальное..."
rm -rf vendor/
echo "Папка vendor удалена"

# Install Composer
curl -sS https://getcomposer.org/installer | php;

# Initialize project

echo "Очищаем кэш Composer и устанавливаем необходимые зависимости..."
php composer.phar clearcache;
php composer.phar install;

#echo "Копируем git hooks в локальную .git папку..."
#cp git_hooks/* .git/hooks/
