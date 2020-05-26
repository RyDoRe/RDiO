#!/usr/bin/env sh

find '/app/__sapper__/build' -name '*.js' -exec sed -i -e 's,API_BASE_URL,'"$API_BASE_URL"',g' {} \;
node __sapper__/build
