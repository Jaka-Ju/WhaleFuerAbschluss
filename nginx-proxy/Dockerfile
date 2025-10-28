FROM nginx:alpine

# Wir KOPPIEREN NICHT MEHR die fehlerhafte nginx.conf.
# Stattdessen kopieren wir die Proxy-Regeln (deine server-Blöcke) 
# in den Standardordner von Nginx.
COPY default.conf /etc/nginx/conf.d/default.conf

# Der CMD-Befehl ist Standard und kann so bleiben
CMD [ "nginx", "-g", "daemon off;" ]