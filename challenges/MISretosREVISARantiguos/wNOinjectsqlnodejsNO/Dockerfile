FROM node:16

# Crear directorio de trabajo
WORKDIR /usr/src/app

# Copiar package.json e instalar dependencias
COPY app/package.json /usr/src/app/
RUN npm install

# Copiar el resto de los archivos de la aplicación
COPY app/ /usr/src/app/

# Configurar permisos
RUN chown -R node:node /usr/src/app
USER node
