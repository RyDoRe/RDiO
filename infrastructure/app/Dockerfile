FROM node:13-alpine as build-stage

WORKDIR /app

COPY packages/app /app

RUN yarn install

RUN yarn build

EXPOSE 3000

CMD ["node", "__sapper__/build"]
