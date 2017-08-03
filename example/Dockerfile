FROM php:7.1-apache

RUN apt-get update -y \
  && apt-get install -y libxml2-dev php-soap \
  && apt-get clean -y \
  && docker-php-ext-install soap