FROM node:12.13-alpine

WORKDIR /app

ARG TZ='UTC'

ENV DEFAULT_TZ ${TZ}

RUN apk upgrade --update \
  && apk add -U tzdata \
  && cp /usr/share/zoneinfo/${DEFAULT_TZ} /etc/localtime \
  && apk del tzdata \
  && rm -rf \
  /var/cache/apk/*

RUN apk update && apk add --no-cache \
    shadow

RUN npm install -g pm2

#------IF host UID:GID is 1000:1000
# RUN chown -R node:node /app

#------ ELSE
RUN addgroup -g 994 www

RUN usermod -aG www node

RUN chown -R node:www /app

#------ END

#------ IF host UID:GID is 1000:1000
# USER node
#------ ELSE
USER node:www
#------ END

COPY --chown=node:www ["package.json", "package-lock.json*", "server.js", "echo.json","./"]

RUN npm install --production --silent

COPY --chown=node:www . .

EXPOSE 6001

CMD ["pm2-runtime", "echo.json"]
