FROM nginx:alpine

# Hier wird deine Datei 'nginx.conf' genommen und im Container
# unter dem Standardnamen 'default.conf' gespeichert.
# Links steht deine Datei, rechts der Zielort im Container.
COPY nginx.conf /etc/nginx/conf.d/default.conf 

CMD [ "nginx", "-g", "daemon off;" ]
