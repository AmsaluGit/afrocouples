
<?php 
/*
running merucure on terminal
//below is correct and working.....

MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!' \
MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!' \
MERCURE_JWT_SECRET='!ChangeMe!' \
SERVER_NAME=:3000 \
 ./mercure run




 SERVER_NAME=:3000 MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!' MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!' ./mercure run -config Caddyfile.dev
//above is working correctly


MERCURE_PUBLISHER_JWT_KEY='!ChangeMe!' \
MERCURE_SUBSCRIBER_JWT_KEY='!ChangeMe!' \
MERCURE_JWT_SECRET='!ChangeMe!' \
CORS_ALLOWED_ORIGINS=http://localhost:3000 \
SERVER_NAME=:3000 \
 ./mercure run



 curl -d 'topic=product_views' -d 'data={"name": "Japan"}' -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.vhMwOaN5K68BTIhWokMLOeOJO4EPfT64brd8euJOA4M' -X POST http://localhost:3000/.well-known/mercure
  



 */