# lara58a_7message

 Version: 0.9.3

 Author  : Kouji Nakashima / kuc-arc-f.com

 date    : 2020/05/19

 update  : 2020/05/30

***

Laravel 5.8, message app sample

* PWA 対応

* Google ログイン対応

* 通知APIでの、新着　自動通知可能。

***
### setup

* install Laravel

php composer.phar create-project --prefer-dist laravel/laravel lara58a "5.8.*"

* helper:

php composer.phar require laravelcollective/html "5.8.*"

* .env.local を参考に、 .env 修正下さい

***
### start

php artisan serve


***
### blog

https://knaka0209.hatenablog.com/entry/lara58_26message

* chat設定ですが、参考の .env 設定

https://knaka0209.hatenablog.com/entry/lara58_25chat


***
### UI

* receive / index
![ img-1 ](https://raw.githubusercontent.com/kuc-arc-f/screen-img/master/web/ss-message-receive-0524.png)

* create message, attache file
![ img-1 ](https://raw.githubusercontent.com/kuc-arc-f/screen-img/master/web/ss-create-file-0522b.png)

* notification display ,auto update

![ img-1 ](https://raw.githubusercontent.com/kuc-arc-f/screen-img/master/web/ss-msg-notification.png)

* replay message
![ img-1 ](https://raw.githubusercontent.com/kuc-arc-f/screen-img/master/web/ss-meg-rep-0524.png)

* message export, text file

![ img-1 ](https://raw.githubusercontent.com/kuc-arc-f/screen-img/master/web/ss-expurt-0524b.png)

***
### version / branch

* v0_9_2 : beta version

***
