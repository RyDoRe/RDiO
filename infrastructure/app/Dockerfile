FROM node:13-alpine

WORKDIR /app

COPY ./package.json ./
COPY ./yarn.lock ./

RUN yarn install

COPY . .

ENV NODE_ENV production

RUN yarn build

EXPOSE 3000

ADD ./.docker/entrypoint.sh ./

CMD ["sh", "entrypoint.sh"]
