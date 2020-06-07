FROM node:13-alpine as build-stage

WORKDIR /app

COPY . .

RUN  npx apidoc -i routes/ -o apidoc/

FROM nginx
COPY --from=build-stage /app/apidoc /usr/share/nginx/html

CMD ["nginx", "-g", "daemon off;"]
