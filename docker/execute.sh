printf "=====> Copy conf project to Nginx Internal...\n"
docker cp $PWD/docker/api-pu.conf ms-api-pu:/etc/nginx/conf.d/

printf "=====> Copy conf project to Nginx Default...\n"
docker cp $PWD/docker/nginx/api-pu.conf nginx:/etc/nginx/conf.d/

printf "=====> Nginx Restart Internal...\n"
docker exec ms-api-pu bash service nginx restart
printf "\n"

printf "=====> Nginx Restart Default...\n"
docker restart nginx

printf "=====> end sh Execute...\n"
