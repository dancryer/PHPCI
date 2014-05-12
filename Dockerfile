FROM ubuntu:14.04
MAINTAINER Dan Cryer dan.cryer@block8.co.uk

RUN echo "deb http://ppa.launchpad.net/ondrej/php5/ubuntu trusty main" >> /etc/apt/sources.list
RUN echo "deb http://archive.ubuntu.com/ubuntu/ precise universe" >> /etc/apt/sources.list
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys C300EE8C E5267A6C 0xcbcb082a1bb943db
RUN apt-get update

# Install PHP:
RUN apt-get install -qy php5-common php5-cli php5-curl php5-imap php5-mcrypt php5-mysqlnd

ADD ./ /phpci

CMD /phpci/daemonise phpci:daemonise
